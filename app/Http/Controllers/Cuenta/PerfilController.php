<?php

namespace torrefuerte\Http\Controllers\Cuenta;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;
use torrefuerte\Models\Articulo;
use torrefuerte\Models\Cronograma;
use torrefuerte\Models\Libro;
use torrefuerte\Models\Pais;
use torrefuerte\Models\Plan;
use torrefuerte\Models\User;
use torrefuerte\Models\Versiculo;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    * Accion Index
    */
    public function index()
    {
    	try
    	{
    		Session::put('menu-item', 'Mi Cuenta');
        	Session::put('menu-subitem', 'Mi Perfil');

    		$user = current_user();

    		return redirect('cuenta/notificaciones');
    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL ENTRAR EN PERFIL.', $ex);

            return redirect('cuenta/notificaciones');
    	}
    }

    /*
    * Accion para mostrar notificaciones
    */
    public function ver_notificaciones()
    {
    	Session::put('menu-item', 'Mi Cuenta');
    	Session::put('menu-subitem', 'Mi Perfil');

		$user = current_user();

		$fecha_hoy = Carbon::today();

		//Verso especial
		$versiculos_especiales = Versiculo::whereEspecial('Si')->get();
		$versiculo = $versiculos_especiales->random();

		//Verificamos si tiene lecturas atrasadas
		$plan = Plan::whereEstado('pendiente')->where('user_id', $user->id)->first();
		
        $pendiente_hoy = 0;
        $atrasadas = 0;
		if( !is_null($plan) )
		{
			$pendiente_hoy = Cronograma::where('plan_id', $plan->id)
											->where('estado', 'pendiente')
											->where('fecha', $fecha_hoy)
											->count();

			$atrasadas = Cronograma::where('plan_id', $plan->id)
											->where('estado', 'pendiente')
											->where('fecha', '<', $fecha_hoy)
											->count();
		}

		//Verificamos si tiene articulos dentro del TOPTEN
		$articulos = Articulo::where('numero_visitas' , '>', 0)
								->orderBy('numero_visitas', 'DESC')
								->take(10)->get();

		$mis_articulos_top = $articulos->where('user_id', $user->id);

		return view('cuenta.notificaciones', compact(
			'user',
			'versiculo',
			'plan', 
			'pendiente_hoy', 
			'atrasadas', 
			'mis_articulos_top'
		));
    }

    /*
    * Accion para mostrar formulario con los datos para modificar
    */
    public function modificar_datos()
    {
    	try
    	{

    		$user = current_user();

    		$paises = Pais::lists('nombre', 'id');

    		return view('cuenta.modificar_datos', compact('user', 'paises'));
    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL MOSTRAR FORMULARIO DE MODIFICAR DATOS.', $ex);

            return redirect('cuenta/notificaciones');
    	}
    }

    /*
    * Accion para actualizar datos
    */
    public function actualizar_datos(Request $request)
    {
    	try
    	{
    		$data = $request->all();

    		$datos_actuales = User::find(current_user()->id);

    		$reglas = [
	            'nombres' => 'required|min:3|max:100',
	            'apellidos' => 'required|min:3|max:100',
	            'sexo' => 'required|in:Hombre,Mujer',
	            'pais' => 'required|integer|exists:pais,id',
	            'celular' => 'numeric|min:9',
	            'email' => 'required|email|max:255|unique:users,email,'.current_user()->id,
	            'foto' => 'image'
	        ];

	        $validator = \Validator::make($data, $reglas);
	        if($validator->fails())
	        {
	        	return redirect()->back()
	        			->withErrors($validator->errors())
	        			->withInput();
	        }

	        //Establecemos los valores
	        $datos_actuales->nombres = $data['nombres'];
	        $datos_actuales->apellidos = $data['apellidos'];
	        $datos_actuales->sexo = $data['sexo'];
	        $datos_actuales->pais_id = $data['pais'];
	        $datos_actuales->celular = $data['celular'];
	        $datos_actuales->email = $data['email'];

	        $email = $datos_actuales->email;

	        //archivos
	        if($request->hasFile('foto')){
	            $file = $request->file('foto');
	            $extension = $file->getClientOriginalExtension();
	            $size = $file->getClientSize();
	            $fecha = Carbon::now()->toDateString();
	            $segundo = Carbon::now()->second;
	            $nombre = $email.'_'.$fecha.'_'.$segundo.'.'.$extension;

	            $datos_actuales->foto = $nombre;
	            
	        }

	        //Actualizamos los datos del usuario
	        $datos_actuales->save();

	        //Subimos el archivo de la foto.
	        if($request->hasFile('foto')){
	            $foto = $request->file('foto');
	            \Storage::disk('local')->put($datos_actuales->foto, \File::get($foto));
	        }

	        Session::flash('msg_success', 'Tus datos se modificaron de forma correcta.');

	    	return redirect('cuenta/notificaciones');
    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL MODIFICAR DATOS.', $ex);

            return redirect()->back()->withErrors($errors);
    	}
    }

    /*
    * Accion para mostrar formulario donde se cambiara el password
    */
    public function cambiar_password()
    {
    	try
    	{
    		$user = current_user();

    		return view('cuenta.cambiar_password', compact('user'));

    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL MOSTRAR FORMULARIO DE MODIFICAR PASSWORD.', $ex);

            return redirect('cuenta/notificaciones');
    	}
    }

    /*
    * Accion para actualizar password
    */
    public function actualizar_password(Request $request)
    {
    	try
    	{
    		$data = $request->all();
    		$user = User::find(current_user()->id);

    		$reglas = [
    			'password'	=>	'required|confirmed|min:6'
    		];

    		$validator = \Validator::make($data, $reglas);
    		if($validator->fails())
    		{
    			return redirect()->back()
    								->withErrors($validator->errors())
    								->withInput();
    		}

    		$user->password = bcrypt($data['password']);
    		$user->save();

    		Session::flash('msg_success', 'Tus datos se modificaron de forma correcta.');

	    	return redirect('cuenta/notificaciones');

    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL MODIFICAR PASSWORD.', $ex);

            return redirect('cuenta/notificaciones');
    	}
    }
}
