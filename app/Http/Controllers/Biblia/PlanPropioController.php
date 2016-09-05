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

class PlanPropioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function nuevo()
    {
    	$libros = Libro::lists('nombre', 'id');
        $planes_activos = Plan::where('user_id', current_user()->id)
                                ->where('estado', 'pendiente')
                                ->count();
        $tiene_planes_activos = ($planes_activos > 0) ? true : false;

    	return view('biblia.plan.plan_propio_nuevo', compact('libros', 'tiene_planes_activos'));
    }

    /* 
    * Accion para guardar el plan de lectura 
    */

    public function guardar(Request $request)
    {
    	$data = $request->all();
    	$fecha_actual = Carbon::today()->format('d/m/Y');

    	try
    	{
    		//INICIA LA TRANSACCION
    		\DB::beginTransaction();

    		//reglas de validacion
	    	$rules = [
	    		'nombre'	=>	'required|min:3',
	    		'libros'	=>	'required|exists:libro,id',
	    		'dias'		=>	'required',
	    		'inicio'	=>	'required|date_format:d/m/Y|after:' . $fecha_actual,
	    		'fin'		=>	'required|date_format:d/m/Y|after:inicio'
	    	];

	    	$validator = \Validator::make($data, $rules);
	    	if($validator->fails())
	    	{
	    		return redirect('biblia/plan-lectura-propio/nuevo')
	    						->withErrors($validator->errors())
	    						->withInput();
	    	}

	    	$cadena_dias = implode(';', $data['dias']);
	    	$inicio = Carbon::createFromFormat('d/m/Y', $data['inicio']);
	    	$fin = Carbon::createFromFormat('d/m/Y', $data['fin']);
            //Como minimo la duracion debe ser una semana
            $f_inicio = Carbon::createFromFormat('d/m/Y', $data['inicio']);
            $semana = new Carbon($f_inicio->addWeek());
            if($fin < $semana){
                $errors = collect(['La duraci&oacute;n deber ser como m&iacute;nimo una semana.']);
                return redirect('biblia/plan-lectura-propio/nuevo')
                                ->withErrors($errors)
                                ->withInput();
            }

	    	// 1. Generamos el plan de lectura
	    	$plan_lectura = $this->generar_plan_lectura($data);
	    	$plan_lectura_colection = collect($plan_lectura);

	    	// 2. Creamos el objeto Plan
	    	$newPlan = Plan::create([
	    		'user_id'		=>	current_user()->id,
	    		'nombre'		=>	$data['nombre'],
	    		'fecha_inicio'	=>	$inicio,
	    		'fecha_final'	=>	$fin,
	    		'dias_lectura'	=>	$cadena_dias,
                'tipo'          =>  'propio'
	    	]);
	    	$newPlan->slug=Str::slug($newPlan->nombre . ' ' . $newPlan->id);
	    	$newPlan->save();

	    	//3. Insertamos el Cronograma
	    	$fechas_del_cronograma = $plan_lectura_colection->unique('fecha')->values()->all();
	    	foreach ($fechas_del_cronograma as $item_cronograma) {
	    		$fecha_item = $item_cronograma['fecha'];

	    		$newCronograma = Cronograma::create([
	    			'plan_id'	=>	$newPlan->id,
	    			'fecha'		=>	Carbon::createFromFormat('d/m/Y', $fecha_item)
	    		]);

	    		$lectura_por_fecha = $plan_lectura_colection->where('fecha', $fecha_item);

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

	    	//SI TODO ESTA OK, SE EJECUTA LA TRANSACCION
	    	\DB::commit();

	    	//Retornar en case de exito;
	    	Session::flash('msg_success', 'Tu plan ha sido creado de forma correcta.');

	    	return redirect('biblia/mis-planes-de-lectura');

    	}
    	catch(\Exception $ex)
    	{
    		//SI HUBO ERROR DESHACEMOS LA TRANSACCION
    		\DB::rollBack();

    		$errors = procesar_excepcion('ERROR AL GENERAR PLAN DE LECTURA.', $ex);

    		return redirect('biblia/plan-lectura-propio/nuevo')
	    						->withErrors($errors)
	    						->withInput();
    	}
    	
    }    

    /*
    * +++++++++++++++ Metodos Privados ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    */

    /*
    * Funcion para generar el plan de lectura, devuelve un array con las fechas
    * y los textos que se leeran por fecha
    */
    private function generar_plan_lectura($data = array())
    {
    	//coleccion con los libros y dias seleccionados
    	$libros = collect($data['libros']);
    	$dias = collect($data['dias']);

    	//creamos la fecha inicial y la fecha final
    	$fecha_inicial = Carbon::createFromFormat('d/m/Y', $data['inicio']);
    	$fecha_final = Carbon::createFromFormat('d/m/Y', $data['fin']);

    	//1. Creamos el array con las fechas de lectura
    	$inicio = new Carbon($fecha_inicial);
    	$final = new Carbon($fecha_final->addDay());
    	$array_fechas = array();
    	while ($inicio->ne($final)) 
    	{
    		if($dias->contains($inicio->dayOfWeek))
    		{
    			$array_fechas[] = new Carbon($inicio);
    		}
    		$inicio = $inicio->addDay();   		
    	}    	

    	//2. Array con los versiculos de los libros seleccionados
    	$array_versiculos = Versiculo::whereIn('libro_id', $libros->all())->orderBy('id', 'ASC')->get();

    	//3. Array versiculos por fecha
    	$versiculos_por_fecha = $this->get_versiculos_por_fecha($array_fechas, $array_versiculos);
    	
    	//4. Array textos biblicos por fecha
    	$plan_lectura = $this->cita_biblica_por_fecha($array_fechas, $versiculos_por_fecha);

    	return $plan_lectura;
    }

    /*
    * Funcion que genera un array (fecha, versiculo_id, libro_id, libro_nombre, capitulo, versiculo)
    */
    private function get_versiculos_por_fecha($array_fechas, $array_versiculos)
    {
    	//1. Numero total de dias de lectura
    	$total_dias = count($array_fechas);

    	//2. Cantidad de versiculos por dia
    	$versiculos_por_dia = count($array_versiculos) / $total_dias;
    	$residuo = count($array_versiculos) % $total_dias;

    	//3. Crear el array que contendra las fechas y los versiculos por cada fecha
    	$versiculos_por_fecha = array();
    	$items_agregados = 1;
    	$contador = 0;
    	for ($i=0; $i < count($array_fechas); $i++) 
    	{ 
    		$fecha_item = new Carbon($array_fechas[$i]);
    		$fecha_item = $fecha_item->format('d/m/Y');
    		for ($j=$contador; $j < count($array_versiculos); $j++) 
    		{ 
    			if( $i == (count($array_fechas) - 1) )
    			{
    				$versiculos_por_fecha[] = array(
    							'fecha' 		=> $fecha_item,
    							'versiculo_id' 	=> $array_versiculos[$j]->id,
    							'libro_id'		=> $array_versiculos[$j]->libro_id,
    							'libro_nombre'	=> $array_versiculos[$j]->libro->nombre,
    							'capitulo'		=> $array_versiculos[$j]->numero_capitulo,
    							'versiculo'		=> $array_versiculos[$j]->numero_versiculo
    						);
    			}else{
    				if( $items_agregados < $versiculos_por_dia )
    				{
    					$versiculos_por_fecha[] = array(
    							'fecha' => $fecha_item,
    							'versiculo_id' => $array_versiculos[$j]->id,
    							'libro_id'		=> $array_versiculos[$j]->libro_id,
    							'libro_nombre'	=> $array_versiculos[$j]->libro->nombre,
    							'capitulo'		=> $array_versiculos[$j]->numero_capitulo,
    							'versiculo'		=> $array_versiculos[$j]->numero_versiculo
    						);

    					$items_agregados++;
    					$contador++;

    				}else{
    					$items_agregados = 1;
    					break;
    				}
    			}
    		}
    	}

    	return $versiculos_por_fecha;
    }

    /*
    * Funcion que genera un array:
    * (fecha, versiculo_id, libro_id, libro_nombre, capitulo, verso_inicio, verso_final)
    */
    private function cita_biblica_por_fecha($array_fechas, $versiculos_por_fecha)
    {
    	$colect_versos_x_fecha = collect($versiculos_por_fecha);
    	$plan_lectura = array();
    	$libro_anterior = '';
        $libros_completos = array();

    	for ($i=0; $i < count($array_fechas); $i++) 
        { 
    		$fecha_filtro = $array_fechas[$i]->format('d/m/Y');
	    	$filtro_x_fecha = $colect_versos_x_fecha->where('fecha', $fecha_filtro);
	    	$filtro_libros_x_fecha = $filtro_x_fecha->unique('libro_id')->values()->all();
	    	foreach ($filtro_libros_x_fecha as $item) 
	    	{
	    		$libro_id_item = $item['libro_id'];
	    		$libro_nombre_item = $item['libro_nombre'];
	    		$filtro_x_libro = $filtro_x_fecha->where('libro_id', $libro_id_item);

	    		$filtro_capitulos_x_libro = $filtro_x_libro->unique('capitulo')->values()->all();
	    		foreach ($filtro_capitulos_x_libro as $item_capitulo) 
                {                    
	    			$capitulo = $item_capitulo['capitulo'];

	    			$filtro_x_capitulo = $filtro_x_libro->where('capitulo', $capitulo);

	    			$primero = $filtro_x_capitulo->first();
	    			$ultimo = $filtro_x_capitulo->last();

	    			$verso_inicio = $primero['versiculo'];
	    			$verso_final = $ultimo['versiculo'];

                    $ultimo_versiculo_del_capitulo = Versiculo::where('libro_id', $libro_id_item)
                                                                ->where('numero_capitulo', $capitulo)
                                                                ->orderBy('numero_versiculo', 'DESC')
                                                                ->first();
                    //Si falta menos de 10 versiculos para terminar el capitulo
                    //entonces se agrega hasta el ultimo versiculo
                    $termino_capitulo = false;
                    $ultimo_versiculo = $ultimo_versiculo_del_capitulo->numero_versiculo;
                    $libro_capitulo = $libro_nombre_item . '_' . $capitulo;                  

                    if( !in_array($libro_capitulo, $libros_completos) )
                    {
                        if( ($ultimo_versiculo - $verso_final) < 10 && ($ultimo_versiculo - $verso_final) > 0 )
                        {
                            $verso_final = $ultimo_versiculo;                        
                            $libros_completos[] = $libro_capitulo;
                        }

                        $plan_lectura[] = array(
                            'fecha' => $fecha_filtro,
                            'libro_id'  => $libro_id_item,
                            'libro_nombre'  => $libro_nombre_item,
                            'capitulo'  => $capitulo,
                            'verso_inicio'  => $verso_inicio,
                            'verso_final'   =>  $verso_final
                        );
                    }    			
	    		}
	    	}
    	}

    	return $plan_lectura;
    }

    /*
    * Establecer la calificacion de la lectura (atrasado, correcto, adelantado)
    */
    private function establecer_calificacion($fecha_asignada, $fecha_realizada)
    {
        $calificacion = '';

        if($fecha_asignada->eq($fecha_realizada))
        {
            $calificacion = 'correcto';
        }
        elseif ($fecha_realizada > $fecha_asignada) {
            $calificacion = 'atrasado';
        }
        else{
            $calificacion = 'adelantado';
        }

        return $calificacion;
    }    

}
