<?php

namespace torrefuerte\Http\Controllers\Biblia;

use Illuminate\Http\Request;
use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;

use Carbon\Carbon;
use Session;


use torrefuerte\Models\Cronograma;
use torrefuerte\Models\Libro;
use torrefuerte\Models\Lectura;
use torrefuerte\Models\Plan;
use torrefuerte\Models\Versiculo;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index()
    {
    	Session::put('menu-item', 'Biblia');
        Session::put('menu-subitem', 'Planes de Lectura');

        return view('biblia.plan.index');
    }

    /*
    * Accion para mostrar la lista de planes que tengo y las opciones para crear
    */
    public function misPlanes()
    {
    	Session::put('menu-item', 'Biblia');
        Session::put('menu-subitem', 'Plan de Lectura Propio');

        //lista de planes
        $planes = Plan::where('user_id', current_user()->id)
    					->orderBy('created_at', 'DESC')
    					->get();

        return view('biblia.plan.plan_propio', compact('planes'));
    }

    /*
    * Accion para leer plan
    */
    public function ver($slug)
    {
        try
        {
            $plan = Plan::whereSlug($slug)->first();

            if(is_null($plan) || (count($plan) < 1)){
                abort(404);//Pagina no encontrada
            }

            $fecha_calendar = strtotime(Carbon::today()) * 1000;
            //Si el plan esta pendiente:
            //se muestra desde la primera fecha pendiente
            //caso contrario se muestra desde la primera fecha de cronograma
            if($plan->estado == 'pendiente')
            {
                //Mostrar el cronograma desde la primera fecha pendiente
                $primer_cronograma_pendiente = Cronograma::where('plan_id', $plan->id)
                                                            ->where('estado', 'pendiente')
                                                            ->orderBy('id', 'ASC')
                                                            ->first();
                if( !is_null($primer_cronograma_pendiente) && (count($primer_cronograma_pendiente)==1))
                {
                    $fecha_calendar = strtotime(new Carbon($primer_cronograma_pendiente->fecha)) * 1000;
                }
            }
            else
            {
                $primer_cronograma = $plan->cronogramas->first();
                $fecha_calendar = strtotime(new Carbon($primer_cronograma->fecha)) * 1000;
            }  
            

            $libros_de_lectura = collect([]);
            $cronogramas = $plan->cronogramas;
            $cadena_libros = 'Todos';
            if($plan->tipo == 'propio')
            {
            	foreach ($cronogramas as $cronograma) {
	                $lecturas = $cronograma->lecturas;
	                foreach ($lecturas as $lectura) {
	                    $nombre = $lectura->libro_nombre;
	                    if( ! $libros_de_lectura->contains($nombre) )
	                    {
	                        $libros_de_lectura->push($nombre);
	                    }
	                }
	            }

	            $cadena_libros = implode(', ', $libros_de_lectura->toArray()) ;
            }

            $datos_grafico = json_encode($this->procesar_grafico($plan));

            return view('biblia.plan.plan_propio_ver', compact('plan', 'cadena_libros', 'fecha_calendar', 'datos_grafico'));
        }
        catch(\Exception $ex)
        {
            $errors = procesar_excepcion('ERROR AL VER PLAN.', $ex);

            return redirect()->back()->withErrors($errors);
        }
    	
    }

    /*
    * Accion para ver el detalle
    */
    public function detalle(Request $request)
    {
        $data = $request->all();
    	$plan = Plan::whereSlug($data['plan_slug'])->first();

    	$cronogramas = $plan->cronogramas;
    	$eventos = array();
    	foreach ($cronogramas as $cronograma) {
    		$fecha = new Carbon($cronograma->fecha);//strtotime($cronograma->fecha) . '000';
    		$lecturas = Lectura::where('cronograma_id', $cronograma->id)
                                    ->orderBy('id', 'ASC')
                                    ->get();

            $hora = 6;
    		foreach ($lecturas as $lectura) {
    			$eventos[] = array(
    				'start' =>	strtotime($fecha->setTime($hora, 0, 0)) * 1000,
    				'end'	=>	strtotime($fecha->setTime($hora++, 0, 0)) * 1000,
    				'url'	=>	url('biblia/mis-planes-de-lectura/leer/' . $lectura->slug),
    				'title'	=>	get_title_lectura($lectura),
    				'class'	=> $this->establecer_clase($lectura)
    			);
                $hora++;
    		}
    	}

    	return response()->json([
    		'success'	=> 1,
    		'result'	=>	$eventos
    	]);
    }

    /*
    * Accion para leer
    */
    public function leer($slug)
    {
        try
        {
            $lectura = Lectura::whereSlug($slug)->first();

            if(is_null($lectura) || (count($lectura) < 1)){
                abort(404);//Pagina no encontrada
            }

            $lista = Versiculo::where('libro_id', $lectura->libro_id)
                                        ->where('numero_capitulo', $lectura->capitulo)
                                        ->whereBetween('numero_versiculo', [$lectura->inicio, $lectura->final])
                                        ->orderBy('id', 'ASC')
                                        ->get();

            $libros_referencia = Libro::lists('nombre', 'id');

            return view('biblia.plan.plan_propio_leer', compact('lectura', 'lista', 'libros_referencia'));
        }
        catch(\Exception $ex)
        {
            $errors = procesar_excepcion('ERROR AL LEER PLAN.', $ex);

            return redirect()->back()->withErrors($errors);
        }

    }

    /*
    * Accion para Finalizar Lectura
    */
    public function finalizar(Request $request)
    {
        $data = $request->all();

        try 
        {
            //INICIA LA TRANSACCION
            \DB::beginTransaction();

            //validaciones
            $rules = [
                'lectura_slug' => 'required|exists:lectura,slug'
            ];

            $validator = \Validator::make($data, $rules);
            if($validator->fails())
            {
                return redirect()->back()
                        ->withErrors($validator->errors())
                        ->withInput();
            }

            $lectura = Lectura::whereSlug($data['lectura_slug'])->first();

            $fecha_actual = Carbon::today();
            $fecha_asignada = new Carbon($lectura->cronograma->fecha);

            $lectura->estado = 'finalizado';
            $lectura->fecha_leida = $fecha_actual;
            $lectura->calificacion = $this->establecer_calificacion($fecha_asignada, $fecha_actual);
            $lectura->save();

            //Verificar Si se esta finalizando las lecturas de un cronograma
            $total_lecturas = Lectura::where('cronograma_id', $lectura->cronograma_id)->count();
            $lecturas_finalizadas = Lectura::where('cronograma_id', $lectura->cronograma_id)
                                                ->where('estado', 'finalizado')
                                                ->count();

            //Si se terminaron todas las lecturas de un cronograma => cambiar estado de cronograma
            if($total_lecturas == $lecturas_finalizadas)
            {
                $cronograma = $lectura->cronograma;
                $cronograma->estado = 'finalizado';
                $cronograma->fecha_termino = $fecha_actual;
                $cronograma->save();
            }

            //Verificar Si se esta terminando el plan
            $total_cronogramas = Cronograma::where('plan_id', $lectura->cronograma->plan_id)->count();
            $cronogramas_finalizados = Cronograma::where('plan_id', $lectura->cronograma->plan_id)
                                                ->where('estado', 'finalizado')
                                                ->count();

            //Si se terminaron todos los cronogramas de un plan => cambiar estado de plan
            if($total_cronogramas == $cronogramas_finalizados)
            {
                $plan = $lectura->cronograma->plan;
                $plan->estado = 'finalizado';
                $plan->fecha_termino = $fecha_actual;
                $plan->save();
            }

            //SI TODO ESTA OK => CONFIRMAMOS LA TRANSACCION
            \DB::commit();

            //Retornar en case de exito;
            Session::flash('msg_success', 'Tu lectura ha sido finalizada de forma correcta.');

            return redirect('biblia/mis-planes-de-lectura');
        }
        catch (\Exception $ex) 
        {
            \DB::rollBack();
            $errors = procesar_excepcion('ERROR AL FINALIZAR LECTURA.', $ex);

            return redirect('biblia/mis-planes-de-lectura')->withErrors($errors);     
        }
        
    }

    /*
    * Accion para eliminar plan
    */
    public function eliminar($slug)
    {
        try
        {
            $plan = Plan::whereSlug($slug)
                            ->where('user_id', current_user()->id)
                            ->first();

            if(is_null($plan))
            {
                $errors = collect(['El plan no existe o no te pertenece.']);
                return redirect('biblia/mis-planes-de-lectura')->withErrors($errors);
            }

            $plan->delete();

            Session::flash('msg_success', 'El plan: '.$plan->nombre.' ha sido eliminado de forma correcta.');

            return redirect('biblia/mis-planes-de-lectura');
        }
        catch(\Exception $ex)
        {
            $errors = procesar_excepcion('ERROR AL ELIMINAR PLAN DE LECTURA.', $ex);
            return redirect('biblia/mis-planes-de-lectura')->withErrors($errors);
        }
    }

    /*
    * Accion para descargar plan de lectura
    */
    public function descargar($slug)
    {
        try
        {
            $plan = Plan::whereSlug($slug)
                            ->where('user_id', current_user()->id)
                            ->first();

            $libros_de_lectura = collect([]);
            $cronogramas = $plan->cronogramas;
            $cadena_libros = 'Todos';
            if($plan->tipo == 'propio')
            {
                foreach ($cronogramas as $cronograma) {
                    $lecturas = $cronograma->lecturas;
                    foreach ($lecturas as $lectura) {
                        $nombre = $lectura->libro_nombre;
                        if( ! $libros_de_lectura->contains($nombre) )
                        {
                            $libros_de_lectura->push($nombre);
                        }
                    }
                }

                $cadena_libros = implode(', ', $libros_de_lectura->toArray()) ;
            }        

            $contador = 1;
            
            $view = \View::make('biblia.plan.plan_pdf', compact('plan', 'cadena_libros', 'contador'));
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');

            //return $pdf->stream('plan_de_lectura.pdf');
            return $pdf->download('plan_de_lectura.pdf');
        }
        catch(\Exception $ex)
        {
            $errors = procesar_excepcion('ERROR AL DESCARGAR PLAN DE LECTURA.', $ex);
            return redirect('biblia/mis-planes-de-lectura')->withErrors($errors);
        }    	
    }

    /*++++++++++++ METODOS PRIVADOS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

    /*
    * Establecer la clase del evento
    */
    private function establecer_clase($lectura)
    {
        $clase = '';
        if($lectura->cronograma->plan->estado == 'finalizado')
        {
            $calificacion = $lectura->calificacion;
            switch ($calificacion) {
                case 'correcto':
                    $clase = 'event-hoy';
                    break;
                case 'atrasado':
                    $clase = 'event-atrasado';                    
                    break;
                case 'adelantado':
                    $clase = 'event-finalizado';
                    break;
            }
        }
        else
        {
            if($lectura->estado == 'finalizado')
            {
                $clase = 'event-finalizado';
            }
            else
            {
                $fecha_hoy = Carbon::today();
                $fecha_lectura = new Carbon($lectura->cronograma->fecha);
                if($fecha_hoy == $fecha_lectura)
                {
                    $clase = 'event-hoy';
                }
                elseif ($fecha_hoy > $fecha_lectura) 
                {
                    $clase = 'event-atrasado';
                }
                elseif ($fecha_hoy < $fecha_lectura) 
                {
                    $clase = 'event-pendiente';
                }
                
            }
        }

        return $clase;
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

    /*
    * Obtener datos para generar grafico
    */
    private function procesar_grafico($plan)
    {
        $data = array();

        if($plan->estado == 'pendiente')
        {
            $fecha_hoy = Carbon::today();
            $pendientes = 0;
            $finalizadas = 0;
            $atrasadas = 0;

            foreach ($plan->cronogramas as $cronograma) 
            {
                foreach ($cronograma->lecturas as $lectura) 
                {
                    $fecha = $cronograma->fecha;
                    if($lectura->estado == 'finalizado')
                    {
                        $finalizadas++;
                    }
                    else
                    {
                        if($fecha < $fecha_hoy)
                        {
                            $atrasadas++;
                        }
                        else
                        {
                            $pendientes++;
                        }
                    }
                }                
            }

            $data = [
                [
                    'name'  => 'L. Atrasadas',
                    'data'  => [$atrasadas],
                    'color' => '#d9534f'
                ],
                [
                    'name'  => 'L. Pendientes',
                    'data'  => [$pendientes],
                    'color' => '#f0ad4e'
                ],
                [
                    'name'  => 'L. Finalizadas',
                    'data'  => [$finalizadas],
                    'color' => '#2babcf'
                ]
            ];
        }
        else
        {
            $correctas = 0;
            $atrasadas = 0;
            $adelantadas = 0;

            foreach ($plan->cronogramas as $cronograma) 
            {
                foreach ($cronograma->lecturas as $lectura) 
                {
                    $calificacion = $lectura->calificacion;
                    switch ($calificacion) {
                        case 'correcto':
                            $correctas++;
                            break;
                        case 'atrasado':
                            $atrasadas++;
                            break;
                        case 'adelantado':
                            $adelantadas++;
                            break;
                    }
                }                
            }

            $data = [
                [
                    'name'  => 'F. correctas',
                    'data'  => [$correctas],
                    'color' => '#5cb85c'
                ],
                [
                    'name'  => 'F. con atraso',
                    'data'  => [$atrasadas],
                    'color' => '#d9534f'
                ],
                [
                    'name'  => 'F. con adelanto',
                    'data'  => [$adelantadas],
                    'color' => '#2babcf'
                ]
            ];

        }

        return $data;
    }

}
