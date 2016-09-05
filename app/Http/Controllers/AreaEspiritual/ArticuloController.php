<?php

namespace torrefuerte\Http\Controllers\AreaEspiritual;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use Session;
use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;
use torrefuerte\Models\Articulo;
use torrefuerte\Models\ArticuloComentario;
use torrefuerte\Models\User;

class ArticuloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['todos', 'leer']]);
    }
    /*
    * Accion para listar todos los articulo
    */
    public function todos()
    {
    	Session::put('menu-item', '&Aacute;rea Espiritual');
        Session::put('menu-subitem', 'Art&iacute;culos B&iacute;blicos');

        $articulos = Articulo::orderBy('created_at', 'DESC')->paginate(10);
        
        return view('area_espiritual.articulo.todos', compact('articulos'));
    }

    /*
    * Accion para listar solo mis articulo
    */
    public function misArticulos()
    {
    	$autor = current_user()->id;
    	$articulos = Articulo::where('user_id', $autor)
    							->orderBy('created_at', 'DESC')->paginate(10);

    	return view('area_espiritual.articulo.propios', compact('articulos'));
    }

    public function crear()
    {
    	return view('area_espiritual.articulo.crear');
    }

    /*
    * Accion para guardar articulo
    */
    public function guardar(Request $request)
    {
    	try
    	{
    		$data = $request->all();

    		//reglas de validacion
    		$reglas = [
    			'titulo'	=>	'required|unique:articulo,titulo|min:5|max:150',
    			'contenido'	=>	'required|min:10'
    		];

    		$validator = \Validator::make($data, $reglas);

    		if($validator->fails())
    		{
    			return redirect()->back()
    						->withErrors($validator->errors())
    						->withInput();
    		}

    		//procesar contenido
    		$contenido = $this->procesar_contenido($data['contenido']);

    		$articulo = Articulo::create([
    				'user_id'	=>	current_user()->id,
    				'titulo'	=>	$data['titulo'],
    				'contenido'	=>	$contenido,
    				'slug'		=>	Str::slug($data['titulo'])
    		]);

    		//Retornar en caso de exito;
	    	Session::flash('msg_success', 'Tu art&iacute;culo ha sido creado de forma correcta.');

	    	return redirect('area-espiritual/mis-articulos');

    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL GUARDAR ARTICULO.', $ex);

            return redirect()->back()->withErrors($errors);
    	}
    }

    /*
    * Accion para leer articulo
    */
    public function leer($slug)
    {
    	try
    	{
    		$articulo = Articulo::whereSlug($slug)->first();

    		if(is_null($articulo) || (count($articulo) < 1))
    		{
                abort(404);//Pagina no encontrada
            }

            $articulo->numero_visitas = $articulo->numero_visitas + 1;
            $articulo->save();

            $lista_comentarios = ArticuloComentario::where('articulo_id', $articulo->id)
    												->orderBy('created_at', 'DESC')
    												->get();

            Session::put('articulo_seleccionado', $articulo->id);

    		return view('area_espiritual.articulo.leer', compact('articulo', 'lista_comentarios'));

    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL LEER ARTICULO.', $ex);

            return redirect('area-espiritual/todos-los-articulos');
    	}
    }

    /*
    * Accion para eliminar articulo
    */
    public function eliminar($slug)
    {
    	try
    	{
    		$articulo = Articulo::whereSlug($slug)
                            ->where('user_id', current_user()->id)
                            ->first();

    		if(is_null($articulo) || (count($articulo) < 1))
    		{
                $errors = collect(['El art&iacute;culo no existe o no te pertenece.']);
                return redirect('area-espiritual/mis-articulos')->withErrors($errors);
            }

            $articulo->delete();

            Session::flash('msg_success', 'El art&iacute;culo: '.$articulo->titulo.' ha sido eliminado de forma correcta.');

            return redirect('area-espiritual/mis-articulos');
    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL ELIMINAR ARTICULO.', $ex);

            return redirect('area-espiritual/mis-articulos');
    	}
    }

    /*
    * Accion para mostrar formulario de editar articulo
    */
    public function editar($slug)
    {
    	try
    	{
    		$articulo = Articulo::whereSlug($slug)
                            ->where('user_id', current_user()->id)
                            ->first();

    		if(is_null($articulo) || (count($articulo) < 1))
    		{
                $errors = collect(['El art&iacute;culo no existe o no te pertenece.']);
                return redirect('area-espiritual/mis-articulos')->withErrors($errors);
            }

            Session::put('articulo_seleccionado', $articulo->id);

            return view('area_espiritual.articulo.editar', compact('articulo'));

    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL EDITAR ARTICULO.', $ex);

            return redirect('area-espiritual/mis-articulos');
    	}
    }

    /*
    * Accion para actualizar articulo
    */
    public function actualizar(Request $request)
    {
    	try
    	{
    		$articulo_seleccionado = Session::get('articulo_seleccionado');

    		$data = $request->all();
    		if($articulo_seleccionado != $data['id'])
    		{
    			$errors = collect(['El art&iacute;culo no es el que ha seleccionado.']);
                return redirect()->back()
    						->withErrors($errors)
    						->withInput();
    		}

    		//reglas de validacion
    		$reglas = [
    			'titulo'	=>	'required|min:5|max:150|unique:articulo,titulo,'.$articulo_seleccionado,
    			'contenido'	=>	'required|min:10'
    		];

    		$validator = \Validator::make($data, $reglas);

    		if($validator->fails())
    		{
    			return redirect()->back()
    						->withErrors($validator->errors())
    						->withInput();
    		}

    		$articulo = Articulo::find($articulo_seleccionado);

    		//procesar contenido
    		$contenido = $this->procesar_contenido($data['contenido']);

    		$articulo->titulo = $data['titulo'];
    		$articulo->contenido = $contenido;
    		$articulo->slug = Str::slug($data['titulo']);

    		$articulo->save();

    		//Retornar en caso de exito;
	    	Session::flash('msg_success', 'Tu art&iacute;culo ha sido actualizado de forma correcta.');

	    	return redirect('area-espiritual/mis-articulos');

    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL EDITAR ARTICULO.', $ex);

            return redirect('area-espiritual/mis-articulos');
    	}
    }

    /*
    * METODOS PRIVADOS +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    */
    private function procesar_contenido($contenido)
    {
    	$contenido = str_replace('<div class="embed-responsive embed-responsive-16by9">', '', $contenido);
    	$contenido = str_replace('</iframe></div>', '</iframe>', $contenido);

    	$contenido = str_replace('class="img-responsive"', '', $contenido);

    	//para que sea un video responsive
		$cadena1 = '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" ';
		$cadena2 = '</iframe></div>';
		$contenido = str_replace('<iframe', $cadena1, $contenido);
		$contenido = str_replace('</iframe>', $cadena2, $contenido);

		//para tener una imagen responsive
		$cadena3 = '<img class="img-responsive" ';
		$contenido = str_replace('<img', $cadena3, $contenido);

		return $contenido;
    }
}
