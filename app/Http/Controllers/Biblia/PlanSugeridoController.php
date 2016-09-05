<?php

namespace torrefuerte\Http\Controllers\Biblia;

use Illuminate\Http\Request;

use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;

use Carbon\Carbon;
use Log;
use Session;
use Illuminate\Support\Str as Str;

use torrefuerte\Models\Cronograma;
use torrefuerte\Models\Lectura;
use torrefuerte\Models\Libro;
use torrefuerte\Models\Plan;
use torrefuerte\Models\Versiculo;

class PlanSugeridoController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

    /*
    * Accion para mostrar el formulario nuevo
    */
    public function nuevo()
    {    	
        $planes_activos = Plan::where('user_id', current_user()->id)
                                ->where('estado', 'pendiente')
                                ->count();
        $tiene_planes_activos = ($planes_activos > 0) ? true : false;

    	return view('biblia.plan.plan_sugerido_nuevo', compact('tiene_planes_activos'));
    }

    /*
    * Accion para guardar el plan sugerido
    */
    public function guardar(Request $request)
    {
    	try
    	{
    		\DB::beginTransaction();

    		$data = $request->all();
    		$fecha_actual = Carbon::today()->format('d/m/Y');

    		//reglas de validacion
	    	$rules = [
	    		'nombre'	=>	'required|min:3',
	    		'tipo'		=>	'required',
	    		'dias'		=>	'required',
	    		'inicio'	=>	'required|date_format:d/m/Y|after:' . $fecha_actual
	    	];

	    	$validator = \Validator::make($data, $rules);
	    	if($validator->fails())
	    	{
	    		return redirect('biblia/plan-lectura-sugerido/nuevo')
	    						->withErrors($validator->errors())
	    						->withInput();
	    	}

	    	$cadena_dias = implode(';', $data['dias']);
	    	$fecha_inicio = Carbon::createFromFormat('d/m/Y', $data['inicio']);

	    	$array_lectura = $this->generar_lecturas($data);
	    	$plan_lectura = collect($array_lectura);

	    	$ultimo = $plan_lectura->last();
	    	$fecha_final = Carbon::createFromFormat('d/m/Y', $ultimo['fecha']);

	    	// 1. Creamos el objeto Plan
	    	$newPlan = Plan::create([
	    		'user_id'		=>	current_user()->id,
	    		'nombre'		=>	$data['nombre'],
	    		'fecha_inicio'	=>	$fecha_inicio,
	    		'fecha_final'	=>	$fecha_final,
	    		'dias_lectura'	=>	$cadena_dias,
                'tipo'          =>  $data['tipo']
	    	]);
	    	$newPlan->slug=Str::slug($newPlan->nombre . ' ' . $newPlan->id);
	    	$newPlan->save();

	    	//2. Insertamos el Cronograma
	    	$fechas_del_cronograma = $plan_lectura->unique('fecha')->values()->all();
	    	foreach ($fechas_del_cronograma as $item_cronograma) {
	    		$fecha_item = $item_cronograma['fecha'];

	    		$newCronograma = Cronograma::create([
	    			'plan_id'	=>	$newPlan->id,
	    			'fecha'		=>	Carbon::createFromFormat('d/m/Y', $fecha_item)
	    		]);

	    		$lectura_por_fecha = $plan_lectura->where('fecha', $fecha_item);

	    		//4. Insertamos lectura por cada fecha de cronograma
	    		foreach ($lectura_por_fecha as $item_lectura) {
	    			
	    			$newLectura = Lectura::create([
	    				'cronograma_id'	=>	$newCronograma->id,
	    				'libro_id'		=>	$item_lectura['libro_id'],
	    				'libro_nombre'	=>	$item_lectura['libro_nombre'],
	    				'capitulo'		=>	$item_lectura['capitulo'],
	    				'inicio'		=>	$item_lectura['verso_inicio'],
	    				'final'			=>	$item_lectura['verso_final']
	    			]);

                    //Guardamos el slug
                    $newLectura->slug = Str::slug('lectura '
                                                    . $newLectura->id . ' '
                                                    . $item_lectura['libro_nombre'] . ' '
                                                    . 'capitulo ' . $item_lectura['capitulo']
                                                    . ' versiculos ' . $item_lectura['verso_inicio']
                                                    . ' ' . $item_lectura['verso_final']
                                                    );
                    $newLectura->save();
	    		}
	    	}

	    	\DB::commit();

	    	//Retornar en case de exito;
	    	Session::flash('msg_success', 'Tu plan ha sido creado de forma correcta.');

	    	return redirect('biblia/mis-planes-de-lectura');

    	}
    	catch(\Exception $ex)
    	{
    		\DB::rollBack();

    		$errors = procesar_excepcion('ERROR AL GENERAR PLAN DE LECTURA.', $ex);

    		return redirect('biblia/plan-lectura-sugerido/nuevo')
	    						->withErrors($errors)
	    						->withInput();
    	}
    }

    //++++++++ METODOS PRIVADOS +++++++++++++++++++++++++++++++++++++++++++++++++

    /*
    * Generar array con los libros leidos del archivo segun tipo de plan
    */
    private function generar_lecturas($data)
    {
    	$tipo = $data['tipo'];
    	$dias = collect($data['dias']);
    	$inicio = Carbon::createFromFormat('d/m/Y', $data['inicio']);

	    $array_archivo = file(storage_path('app/' . $tipo . '.txt'));

	    $array_lectura = array();

	    if($tipo == 'variado')
	    {
	    	$clase_anterior = 'epistola';
	    	
	    	for($i = 0; $i < count($array_archivo); $i++)
	    	{
	    		/*
		    	* item : tiene la siguiente estructura
	    		* [0]	=> clase (epistola, ley, historia, salmos, poesia, profecia, evangelio)
	    		* [1]	=> libro_id
	    		* [2]	=> numero_capitulo
	    		* [3]	=> versiculo de inicio
	    		*/
	    		$cadena = trim($array_archivo[$i]);
		    	$item = explode(';', $cadena);

		    	//Nombre del Libro
		    	$libro = Libro::find(intval($item[1]));

		    	$libro_nombre = $libro->nombre;

		    	//Ultimo versiculo
		    	$ultimo_verso = Versiculo::where('libro_id', intval($item[1]))
		    									->where('numero_capitulo', intval($item[2]))
		    									->orderBy('numero_versiculo', 'DESC')
		    									->first();

		    	$ultimo = $ultimo_verso->numero_versiculo;

		    	//Verificamos por clase para cambiar de fecha
		    	$clase_actual = $item[0];

		    	//Si fecha pertenece a los dias seleccionados (Entraria solo la primera vez)
		    	if(!$dias->contains($inicio->dayOfWeek))
		    	{
		    		while(!$dias->contains($inicio->dayOfWeek))
		    		{
		    			$inicio = $inicio->addDay();
		    		}
		    	}

		    	//Cuando cambia de clase => cambiar fecha
		    	if($clase_anterior != $clase_actual)
		    	{
		    		$inicio = $inicio->addDay();
		    		if(!$dias->contains($inicio->dayOfWeek))
			    	{
			    		while(!$dias->contains($inicio->dayOfWeek))
			    		{
			    			$inicio = $inicio->addDay();
			    		}
			    	}
			    	$clase_anterior = $clase_actual;
		    	}

		    	$fecha = new Carbon($inicio);
		    	$fecha = $fecha->format('d/m/Y');

		    	$array_lectura[] = array(
	                'fecha' => $fecha,
	                'libro_id'  => intval($item[1]),
	                'libro_nombre'  => $libro_nombre,
	                'capitulo'  => intval($item[2]),
	                'verso_inicio'  => intval($item[3]),
	                'verso_final'   =>  $ultimo
	            );
	    	}

	    }
	    else
	    {
	    	$dia = 1;
		    $contador = 1;
		    for ($i = 0; $i < count($array_archivo); $i++) 
		    {
		    	//Cada item debe tener 4 indices, si el ultimo esta vacio => 
		    	//colocar el ultimo versiculo del capitulo
		    	$cadena = trim($array_archivo[$i]);
		    	$item = explode(';', $cadena);

		    	if(is_null($item[3]) || ($item[3] == '') )
		    	{
		    		$ultimo_verso = Versiculo::where('libro_id', intval($item[0]))
		    									->where('numero_capitulo', intval($item[1]))
		    									->orderBy('numero_versiculo', 'DESC')
		    									->first();

		    		$item[3] = $ultimo_verso->numero_versiculo;
		    	}
		    	$libro = Libro::find(intval($item[0]));

		    	$libro_nombre = $libro->nombre;

		    	//Si fecha pertenece a los dias seleccionados (Entraria solo la primera vez)
		    	if(!$dias->contains($inicio->dayOfWeek))
		    	{
		    		while(!$dias->contains($inicio->dayOfWeek))
		    		{
		    			$inicio = $inicio->addDay();
		    		}
		    	}	    	

		    	//Agregar solo tres capitulos por dia
		    	if($contador > 3)
		    	{
		    		$inicio = $inicio->addDay();
		    		if(!$dias->contains($inicio->dayOfWeek))
			    	{
			    		while(!$dias->contains($inicio->dayOfWeek))
			    		{
			    			$inicio = $inicio->addDay();
			    		}
			    	}
		    		$contador = 1;

		    	}

		    	$fecha = new Carbon($inicio);
		    	$fecha = $fecha->format('d/m/Y');

		    	$array_lectura[] = array(
	                'fecha' => $fecha,
	                'libro_id'  => intval($item[0]),
	                'libro_nombre'  => $libro_nombre,
	                'capitulo'  => intval($item[1]),
	                'verso_inicio'  => intval($item[2]),
	                'verso_final'   =>  intval($item[3])
	            );

	            $contador++;

		    }
	    }	    

	    return $array_lectura;
	    
    }
}
