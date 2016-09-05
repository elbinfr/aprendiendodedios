<?php

namespace torrefuerte\Http\Controllers\Biblia;

use Illuminate\Http\Request;
use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;

use torrefuerte\Models\Comentario;
use torrefuerte\Models\Libro;
use torrefuerte\Models\Versiculo;
use torrefuerte\Models\VersiculoVersion;

use Session;

class PasajeController extends Controller
{
    public function index()
    {
        Session::put('menu-item', 'Biblia');
        Session::put('menu-subitem', 'B&uacute;squeda de Pasaje');

        $libros = Libro::lists('nombre', 'id');
        $libros_referencia = Libro::lists('nombre', 'id');

        return view('biblia.pasaje.index', compact('libros', 'libros_referencia'));
    }

    /*
     * Accion para buscar Pasaje biblico
     */
    public function buscarPasaje(Request $request)
    {
        $data = $request->all();
        //reglas de validacion
        //para vesiculos puede ser : numero o numero-numero
        $rules = [
            'libro'      => ['required', 'numeric', 'exists:libro,id'],
            'capitulo'      => ['required', 'numeric', 'integer', 'min:1'],
            'versiculos'    => ['regex:/^([1-9][0-9]*)$|^([1-9][0-9]*-[1-9][0-9]*)$/']
        ];

        $validator = \Validator::make($data, $rules);
        if($validator->fails()){
            return redirect('biblia/busqueda-pasaje')
                ->withErrors($validator->errors())
                ->withInput();
        }

        $verso_ini = '';
        $verso_fin = '';

        //si tiene un rango de versiculos
        $verso_ini = $data['versiculos'];
        if(str_contains($data['versiculos'], '-')){
            $vector = explode('-', $data['versiculos']);
            $verso_ini = $vector[0];
            $verso_fin = $vector[1];
        }

        $parametros = [
            'criterio'  => 'id',
            'libro'     => $data['libro'],
            'capitulo'  => $data['capitulo'],
            'verso_ini' => $verso_ini,
            'verso_fin' => $verso_fin
        ];

        $lib = Libro::find($parametros['libro']);
        $cadena_versiculo = '';
        if( !is_null($data['versiculos']) && ($data['versiculos'] != '') ){
            $cadena_versiculo = ': ' . $data['versiculos'];
        }
        $titulo_buscado = $lib->nombre . ' ' . $parametros['capitulo'] . $cadena_versiculo;
        $lista = Versiculo::pasaje($parametros)->orderBy('id', 'ASC')->get();

        $libros = Libro::lists('nombre', 'id');
        $libros_referencia = Libro::lists('nombre', 'id');

        return view('biblia.pasaje.index', compact('lista', 'titulo_buscado', 'libros', 'libros_referencia'));
    }

    /*
     * Acción para mostrar el versículo seleccionado en otras versiones
     */
    public function mostrarVersiones(Request $request)
    {
        $data = $request->all();

        $versiones = VersiculoVersion::where('versiculo_id', $data['versiculo_id'])->get();
        $contenido = dibujar_versiones($versiones);

        if($request->ajax()){
            return response()->json([
                'contenido'        => $contenido
            ]);
        }
    }

    /*
     * Acción para guardar comentarios
     */
    public function guardarComentario(Request $request)
    {
        //Reglas de validación
        $rules = [
            'user_id' => 'required|numeric|exists:users,id,id,'.current_user()->id,
            'versiculo_id' => 'required|numeric|exists:versiculo,id',
            'texto' => 'required'
        ];

        //Se ejecuta el validar, si hay errores los enviamos al usuario
        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'resultado' => false,
                'errors'    => $validator->errors()->all()
            ]);
        }

        //Si todo esta correcto guardamos
        Comentario::create($request->all());

        if($request->ajax()){
            return response()->json([
                'resultado' => true
            ]);
        }
    }

    /*
     * Acción para listar comentarios
     */
    public function getComentarios(Request $request)
    {
        $data = $request->all();

        $comentarios = $this->obtenerComentariosHtml($data['verso_inicial'], $data['verso_final']);

        if($request->ajax()){
            return response()->json([
               'comentarios' => $comentarios
            ]);
        }
    }

    /*
     * Acción para mostrar los datos del comentario a editar
     */
    public function editarComentario(Request $request)
    {
        //reglas de validación
        $rules = [
            'comentario_id' => 'required|numeric|exists:comentario,id,user_id,'.current_user()->id
        ];

        //ejecutamos la validación
        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'resultado' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

        $comentario = Comentario::find($request->input('comentario_id'));
        return response()->json([
            'resultado'     => true,
            'comentario'    => $comentario
        ]);
    }

    /*
     * Acción para actualizar comentario
     */
    public function actualizarComentario(Request $request)
    {
        $data = $request->all();

        //reglas de validación
        $rules = [
            'id'            => 'required|numeric|exists:comentario,id,user_id,'.current_user()->id,
            'user_id'       => 'required|numeric|exists:comentario,user_id,user_id,'.current_user()->id,
            'versiculo_id'  => 'required|numeric|exists:comentario,versiculo_id',
            'texto'         => 'required'
        ];

        //ejecutamos la validación
        $validator = \Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json([
                'resultado' => false,
                'errors'    => $validator->errors()->all()
            ]);
        }

        $comentario = Comentario::find($data['id']);
        $comentario->texto = $data['texto'];
        $comentario->save();
        return response()->json([
            'resultado' => true
        ]);
    }

    /*
     * Acción para eliminar comentario
     */
    public function eliminarComentario(Request $request)
    {
        //reglas de validación
        $rules = [
            'comentario_id' => 'required|numeric|exists:comentario,id,user_id,'.current_user()->id
        ];

        //ejecutamos el validador
        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
               'resultado'  => false,
                'errors'    => $validator->errors()->all()
            ]);
        }

        // todo esta OK entonces se elimina el comentario
        $comentario = Comentario::findOrFail($request->input('comentario_id'));
        $comentario->delete();

        return response()->json([
           'resultado'  => true
        ]);

    }

    /*
     * FUNCIONES
     */

    /*
     * Función para mostrar los comentarios en un rango de versículos
     * Esta función se llama desde las acciones de este controlador
     */
    private function obtenerComentariosHtml($inicio, $final)
    {
        $comentarios = '';

        $lista_comentarios = Comentario::whereBetween('versiculo_id', [$inicio, $final])
            ->orderBy('versiculo_id', 'asc')
            ->get();

        if(count($lista_comentarios) > 0){
            $comentarios = dibujar_comentarios($lista_comentarios);
        }

        return $comentarios;
    }
}
