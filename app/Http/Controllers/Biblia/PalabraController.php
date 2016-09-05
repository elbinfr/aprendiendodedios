<?php

namespace torrefuerte\Http\Controllers\Biblia;

use Illuminate\Http\Request;
use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;

use torrefuerte\Models\Libro;
use torrefuerte\Models\Versiculo;

use Session;

class PalabraController extends Controller
{
    public function index()
    {
        Session::put('menu-item', 'Biblia');
        Session::put('menu-subitem', 'B&uacute;squeda por palabra');

        $libros_referencia = Libro::lists('nombre', 'id');

        return view('biblia.palabra.index', compact('libros_referencia'));
    }

    /*
     * Buscar Frase
     */
    public function buscarFrase(Request $request)
    {
        $data = $request->all();
        //reglas de validacion
        $rules = [
            'frase' => 'required|min:3'
        ];

        $validator = \Validator::make($data, $rules);
        if($validator->fails()){
            return redirect('biblia/busqueda-palabra')
                ->withErrors($validator->errors())
                ->withInput();
        }

        $libros_referencia = Libro::lists('nombre', 'id');
        $frase = $data['frase'];
        $lista = Versiculo::texto($frase)->paginate(15);

        //return $lista;
        return view('biblia.palabra.index', compact('libros_referencia', 'lista', 'frase'));

    }
}
