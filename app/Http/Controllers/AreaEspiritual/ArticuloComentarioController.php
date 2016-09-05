<?php

namespace torrefuerte\Http\Controllers\AreaEspiritual;

use Illuminate\Http\Request;

use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;
use torrefuerte\Models\ArticuloComentario;
use Session;

class ArticuloComentarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    * Accion para guardar comentario
    */
    public function guardar(Request $request)
    {
    	try
    	{
    		$data = $request->all();
    		//reglas de validacion
    		$reglas = [
    			'contenido'	=>	'required|min:4'
    		];

    		$validator = \Validator::make($data, $reglas);
    		if($validator->fails())
    		{
    			return response()->json([
    				'resultado'	=>	false,
    				'errors'	=>	$validator->errors()->all()
    			]);
    		}

    		$articulo_seleccionado = Session::get('articulo_seleccionado');

    		$comentario = ArticuloComentario::create([
    			'articulo_id'	=>	$articulo_seleccionado,
    			'user_id'		=>	current_user()->id,
    			'contenido'		=>	$data['contenido']
    		]);

    		$lista_comentarios = ArticuloComentario::where('articulo_id', $articulo_seleccionado)
    												->orderBy('created_at', 'DESC')
    												->get();
    		$comentarios = dibujar_articulo_comentarios($lista_comentarios);

    		return response()->json([
	            'resultado' => true,
	            'comentarios' => $comentarios
	        ]);

    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL COMENTAR ARTICULO.', $ex);

            return response()->json([
				'resultado'	=>	false,
				'errors'	=>	$errors
			]);
    	}
    }

    /*
    * Accion para eliminar comentario
    */
    public function eliminar(Request $request)
    {
    	try
    	{
    		$data = $request->all();

    		$articulo_seleccionado = Session::get('articulo_seleccionado');

    		$reglas = [
    			'comentario_id' => 'required|exists:articulo_comentario,id,articulo_id,'.$articulo_seleccionado
    		];

    		$validator = \Validator::make($data, $reglas);
    		if($validator->fails())
    		{
    			return response()->json([
    				'resultado'	=>	false,
    				'errors'	=>	$validator->errors()->all()
    			]);
    		}

    		$articulo_comentario = ArticuloComentario::find($data['comentario_id']);
    		$articulo_comentario->delete();

    		$lista_comentarios = ArticuloComentario::where('articulo_id', $articulo_seleccionado)
    												->orderBy('created_at', 'DESC')
    												->get();
    		$comentarios = dibujar_articulo_comentarios($lista_comentarios);

    		return response()->json([
	            'resultado' => true,
	            'comentarios' => $comentarios
	        ]);

    	}
    	catch(\Exception $ex)
    	{
    		$errors = procesar_excepcion('ERROR AL ELIMINAR COMENTARIO.', $ex);

            return response()->json([
				'resultado'	=>	false,
				'errors'	=>	$errors
			]);
    	}
    }
}
