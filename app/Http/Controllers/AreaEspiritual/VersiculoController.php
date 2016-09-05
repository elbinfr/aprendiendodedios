<?php

namespace torrefuerte\Http\Controllers\AreaEspiritual;

use Illuminate\Http\Request;

use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;

use torrefuerte\Models\Libro;
use torrefuerte\Models\Versiculo;

class VersiculoController extends Controller
{
    public function getBuscar()
    {
    	$libros = Libro::lists('nombre', 'id');

    	return view('versiculos.index', compact('libros'));
    }

    public function postBuscar(Request $request)
    {
    	$data = $request->all();

    	$capitulo = $data['capitulo'];

    	$lib = Libro::find($data['libro']);
    	$libros = Libro::lists('nombre', 'id');

    	if(is_null($capitulo) || $capitulo == '' || !is_numeric($capitulo))
    	{

    		return view('versiculos.index', compact('libros'));
    	}
    	else
    	{
    		$titulo_buscado = $lib->nombre . ' ' . $capitulo;

	    	$lista = Versiculo::where('libro_id', $data['libro'])
	    							->where('numero_capitulo', $capitulo)
	    							->orderBy('id', 'ASC')->get();	        

	    	return view('versiculos.index', compact('libros', 'lista', 'titulo_buscado'));
    	}
    }
}
