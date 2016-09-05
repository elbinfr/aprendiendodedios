<?php

namespace torrefuerte\Http\Controllers\Biblia;

use Illuminate\Http\Request;
use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;

use torrefuerte\Models\Versiculo;
use torrefuerte\Models\Referencia;

class ReferenciaController extends Controller
{
    //Buscar Pasaje
    public function buscarPasaje(Request $request)
    {
        $datos = $request->all();
        //reglas de validacion
        //para vesiculos puede ser : numero o numero-numero
        $rules = [
            'libro_id'      => ['required', 'numeric', 'exists:libro,id'],
            'capitulo'      => ['required', 'numeric'],
            'versiculos'    => ['required', 'regex:/^([1-9][0-9]*)$|^([1-9][0-9]*-[1-9][0-9]*)$/']
        ];

        //ejecutamos la validaión
        $validator = \Validator::make($datos, $rules);
        if($validator->fails()){
            return response()->json([
               'resultado'  => false,
                'errors'    => $validator->errors()->all()
            ]);
        }

        $verso_ini = '';
        $verso_fin = '';

        //si tiene un rango de versiculos
        $verso_ini = $datos['versiculos'];
        if(str_contains($datos['versiculos'], '-')){
            $vector = explode('-', $datos['versiculos']);
            $verso_ini = $vector[0];
            $verso_fin = $vector[1];
        }

        $parametros = [
            'criterio'  => 'id',
            'libro'     => $datos['libro_id'],
            'capitulo'  => $datos['capitulo'],
            'verso_ini' => $verso_ini,
            'verso_fin' => $verso_fin
        ];

        $versiculo = new Versiculo();
        $lista_versos = Versiculo::pasaje($parametros)->orderBy('id', 'ASC')->get();

        $cadena_versos = 'No se encontraron resultados.';
        if(count($lista_versos)>0){
            $cadena_versos = dibujar_versiculos_add_referencia($lista_versos, $datos['versiculo_id']);
        }

        return response()->json([
           'resultado' => true,
            'versiculos' => $cadena_versos
        ]);
    }

    //Agregar Referencia
    public function agregar(Request $request)
    {
        $datos = $request->all();
        //reglas de validación
        $rules = [
            'versiculo_id' => ['required', 'numeric', 'exists:versiculo,id']
        ];

        $validator = \Validator::make($datos, $rules);
        if($validator->fails()){
            return response()->json([
                'resultado' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

        $versiculo_id = $datos['versiculo_id'];
        $lista_versos = $datos['lista_versos'];
        $codigos_versiculos = explode(';', $lista_versos);

        //solo los codigo correctos se agregarán
        for($i=0 ; $i<count($codigos_versiculos); $i++){
            $codigo = $codigos_versiculos[$i];
            if(is_numeric($codigo)){
                //que exista en la table versiculo
                $existe_en_versiculo = Versiculo::find($codigo);
                if( !is_null($existe_en_versiculo) ){
                    //que no haya sido agregado como referencia al versiculo_id
                    $existe_referencia = Referencia::where('versiculo_id', $versiculo_id)
                                                        ->where('cita', $codigo)->first();
                    if(is_null($existe_referencia)){
                        //Guardamos la referencia
                        $referencia = new Referencia();
                        $referencia->user_id = current_user()->id;
                        $referencia->versiculo_id = $versiculo_id;
                        $referencia->cita = $codigo;
                        $referencia->save();
                        //Guardamos referencia en viceversa:
                        $referencia_cruzada = new Referencia();
                        $referencia_cruzada->user_id = current_user()->id;
                        $referencia_cruzada->versiculo_id = $codigo;
                        $referencia_cruzada->cita = $versiculo_id;
                        $referencia_cruzada->save();
                    }
                }
            }
        }

        //asumimos que todo estuvo OK
        return response()->json([
           'resultado' => true
        ]);
    }

    /*
     * Listar Referencias
     */
    public function listar(Request $request)
    {
        $datos = $request->all();

        //reglas de validación
        $rules = [
            'versiculo_id' => 'required|numeric|exists:versiculo,id'
        ];

        //ejecutar la validacion
        $validator = \Validator::make($datos, $rules);
        if($validator->fails()){
            return response()->json([
                'resultado' => false,
                'errors'    => $validator->errors()->all()
            ]);
        }

        $versiculo_base = Versiculo::find($datos['versiculo_id']);
        $lista_referencia = Referencia::where('versiculo_id', $datos['versiculo_id'])
                                            ->orderBy('cita', 'asc')->get();

        $cadena_referencias = dibujar_referencias_cruzadas($lista_referencia);

        return response()->json([
            'resultado'     => true,
            'versiculos'    => $cadena_referencias
        ]);
    }

    /*
     * Eliminar Referencia
     */
    public function eliminar(Request $request)
    {
        $datos = $request->all();

        //reglas de validación
        $rules = [
            'id' => 'required|numeric|exists:referencia,id,user_id,'.current_user()->id,
            'user_id'  => 'required|numeric|exists:referencia,user_id,user_id,'.current_user()->id
        ];

        $validator = \Validator::make($datos, $rules);
        if($validator->fails()){
            return response()->json([
               'resultado'  => 'error',
                'errors'    => $validator->errors()->all()
            ]);
        }

        $referencia = Referencia::find($datos['id']);
        if( !is_null($referencia)){
            $referencia_cruzada = Referencia::where('versiculo_id', $referencia->cita)
                                                ->where('cita', $referencia->versiculo_id)->first();
            //eliminamos ambas referencias
            $referencia->delete();
            $referencia_cruzada->delete();

            return response()->json([
                'resultado' => 'success',
                'msg_resultado' => 'Referencia eliminada.'
            ]);
        }

        return response()->json([
            'resultado' => 'info',
            'msg_resultado' => 'No se eliminó la referencia.'
        ]);

    }

}
