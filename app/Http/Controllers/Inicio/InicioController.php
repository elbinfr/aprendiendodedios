<?php

namespace torrefuerte\Http\Controllers\Inicio;

use Illuminate\Http\Request;

use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;
use torrefuerte\Models\Articulo;

class InicioController extends Controller
{
    public function index()
    {
        \Session::put('menu-item', 'Inicio');

        $articulos = Articulo::orderBy('created_at', 'DESC')->take(4)->get();

        return view('inicio.inicio', compact('articulos'));
    }
}
