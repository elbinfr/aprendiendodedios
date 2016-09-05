<?php

namespace torrefuerte\Http\Controllers\Administracion;

use Illuminate\Http\Request;

use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;
use torrefuerte\Http\Requests\StorePerfilRequest;
use torrefuerte\Http\Requests\UpdatePerfilRequest;

use torrefuerte\Models\Perfil;
use Session;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Session::put('menu-item', 'Administración');
        Session::put('menu-subitem', 'Perfiles de Usuario');

        $perfiles = Perfil::orderBy('id', 'ASC')->paginate(10);

        return view('administracion.perfil.index', compact('perfiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('administracion.perfil.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(StorePerfilRequest $request)
    {
        Perfil::create($request->all());

        Session::flash('msg_success', 'Los datos se guardaron correctamente.');

        return redirect()->route('administracion.perfil.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $perfil = Perfil::findOrFail($id);
        return view('administracion.perfil.edit', compact('perfil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdatePerfilRequest $request, $id)
    {
        $perfil = Perfil::findOrFail($id);

        $perfil->fill($request->all());
        $perfil->save();

        Session::flash('msg_success', 'El perfil se actualizó correctamente.');
        return redirect()->route('administracion.perfil.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $perfil = Perfil::findOrFail($id);
        $result = $perfil->delete();

        if($result == 1){
            $mensaje = "El perfil ".$perfil->nombre." fue eliminado correctamente.";
        }else{
            $mensaje = "Ocurrio un error al eliminar el perfil ".$perfil->nombre.".";
        }

        if($request->ajax()){
            return $mensaje;
        }
    }

    /*
     * Mostrar el formulario de busqueda
     */
    public function getBuscar()
    {
        $perfil = new Perfil();
        $columnas = getColumnasFomTable('perfil');
        return view('administracion.perfil.buscar', compact('columnas'));
    }

    /*
     * Realizar la busqueda
     */
    public function postBuscar(Request $request)
    {
        $campo = $request->input('criterio');
        $dato = $request->input('dato');

        $lista = buscarPorColumna('perfil',$campo, $dato);

        $str_filas = dibujar_filas($lista);

        if($request->ajax()){
            return $str_filas;
        }
    }
}
