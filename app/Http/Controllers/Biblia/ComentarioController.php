<?php

namespace torrefuerte\Http\Controllers\Biblia;

use Illuminate\Http\Request;
use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;

use torrefuerte\Models\Comentario;

class ComentarioController extends Controller
{

    public function listarPorVersiculo(Request $request)
    {
        $data = $request->all();
        //reglas de validación
        $rules = [
            'versiculo_id' => 'required|numeric|exists:versiculo,id'
        ];

        $validator = \Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json([
                'resultado' => 'error',
                'errors'    => $validator->errors()->all()
            ]);
        }

        $lista = Comentario::where('versiculo_id',$data['versiculo_id'])->orderBy('updated_at')->get();

        $comentarios = dibujar_comentarios($lista);

        return response()->json([
            'resultado' => 'success',
            'comentarios' => $comentarios
        ]);
    }

}
