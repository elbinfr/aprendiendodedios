<?php

/*
 * Verificar si el usuario esta logueado
 */
function is_login()
{
    return Auth::check();
}

/*
 * Usuario logueado
 */
function current_user()
{
    return Auth::user();
}

/*
* Verificar si es superusuario: id = 4
*/
function es_superadmin()
{
    if(Auth::user()->perfil_id == 4)
    {
        return true;
    }else{
        return false;
    }
}

/*
* Generar el vector de excepciones para mostrar en el archivo log
*/
function datos_excepcion($ex)
{
    $vector = [
        'mensaje'   => $ex->getMessage(),
        'codigo'    => $ex->getCode(),
        'archivo'   => $ex->getFile(),
        'linea'     => $ex->getLine()
    ];

    return $vector;
}

/*
* Escribir excepcion en archivo log y devolver un mensaje para el usuario
*/
function procesar_excepcion($mensaje_excepcion, $exeption)
{
    \Log::info($mensaje_excepcion . '---' . $exeption, datos_excepcion($exeption));

    $errors = collect(['Se detecto un error, porfavor vuelva a intentarlo en unos minutos.']);

    return $errors;
}

/*
 * Convertir un string en tipo oracion
 */
function str_oracion($cadena)
{
    $palabras = explode('_', $cadena);
    $oracion = '';
    for($i=0; $i < count($palabras) ; $i++){
        if($i == 0){
            $palabras[$i] = ucfirst($palabras[$i]);
        }
        $oracion = $oracion.' '.$palabras[$i];
    }
    return $oracion;
}

/*
 * Obtener las columnas de una tabla de la base de datos postgres
 */
function getColumnasFomTable($tabla)
{
    $query = "SELECT cols.ordinal_position as posicion,cols.column_name as nombre,cols.data_type"
        ." FROM information_schema.columns cols"
        ." WHERE"
        ." cols.table_name=?"
        ." and cols.column_name not in ('created_at', 'updated_at')"
        ." order by posicion";

    $lista = \DB::select($query, [$tabla]);
    $columnas = array();
    foreach($lista as $item){
        $columnas[$item->nombre] = str_oracion($item->nombre);
    }

    return $columnas;
}

/*
 *Obtener los valores de una tabla, buscado por columna
 */
function buscarPorColumna($tabla, $campo, $dato)
{
    $dato = strtoupper($dato);
    $query = "select * from ".$tabla." where upper(".$campo."::character varying) like '%' || ? || '%'";

    $lista = \DB::select($query, [$dato]);

    return $lista;
}

/*
 * Dado un array dibujar las filas y columnas
 */
function dibujar_filas($lista)
{
    $cadena = '';
    for($i = 0; $i < count($lista); $i++){
        $objeto = $lista[$i];
        $cadena = $cadena.'<tr>';
        foreach($objeto as $nombre => $valor){
            if(strcmp($nombre, 'created_at') != 0 && strcmp($nombre, 'updated_at') != 0){
                $cadena = $cadena.'<td>'.$valor.'</td>';
            }
        }
        $cadena = $cadena.'</tr>';
    }

    echo $cadena;
}

/*
 * Obtener cita biblica: libro numero_capitulo: numero_versiculo
 */
function cita_biblica($objeto)
{
    return $objeto->libro->nombre . ' ' . $objeto->numero_capitulo . ': ' . $objeto->numero_versiculo;
}

/*
 * Dado un array, dibujar los versiculos
 */
function dibujar_versiculos($lista)
{
    $cadena ='';

    if(count($lista) > 0){
        foreach($lista as $objeto){
            if($objeto->titulo !=null && $objeto->titulo != ''){
                $cadena = $cadena.'<p align="center" style="color:#2babcf"><strong>'.$objeto->titulo.'</strong></p>';
            }
            $cadena = $cadena.'<p id="'.$objeto->id.'" class="txt-verso" >'            
            . '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#accionesModal" data-versiculo-id="' . $objeto->id . '">'
            . $objeto->numero_versiculo 
            . '</button>'
            . '&nbsp;' . $objeto->texto . '</p>';
        }
    }

    return $cadena;
}

/*
 * Dibujar los versiculos en otras versiones
 */
function dibujar_versiones($lista)
{
    $cadena = '';

    if(count($lista) > 0){
        foreach($lista as $objeto){
            $cadena = $cadena.'<p><strong style="color:#2babcf">'.$objeto->version->abreviatura.'</strong><br>'.$objeto->texto.'</p>';
        }
    }

    return $cadena;
}

/*
* Establecer la imagen a mostrar para el usuario logueado
*/
function dibujar_foto()
{

    $cadena = '';
    $ruta = 'assets/fotos/';
    $foto = '';
    if(is_null(Auth::user()->foto)){
        $sexo = Auth::user()->sexo;
        if($sexo == 'Hombre'){
            $foto = 'avatar-hombre.png';
        }else{
            $foto = 'avatar-mujer.png';
        }

    }else{
        $foto = Auth::user()->foto;        
    }

    $ruta = $ruta . $foto;
    $cadena = '<img src="' . asset($ruta) . '" class="img-logueado"/>';

    return $cadena;
}

/*
 * Funciones para dibujar los comentarios
 */
function dibujar_foto_comentario($user)
{
    $cadena = '';
    $ruta = 'assets/fotos/';
    $foto = '';
    if(is_null($user->foto)){
        $sexo = $user->sexo;
        if($sexo == 'Hombre'){
            $foto = 'avatar-hombre.png';
        }else{
            $foto = 'avatar-mujer.png';
        }

    }else{
        $foto = $user->foto;
    }

    $ruta = $ruta . $foto;
    $cadena = '<img src="' . asset($ruta) . '" />';

    return $cadena;
}

function dibujar_eliminar_comentario($comentario)
{
    $boton = '';

    if(is_login()){
        if($comentario->user_id == current_user()->id){
            $boton = '<a class="btn-eliminar-cmt btn btn-danger btn-xs pull-right" '
                        . ' data-action="' . url('biblia/eliminarcomentario') . '" '
                        . ' data-token="' . csrf_token() . '" '
                        . ' data-comentario-id="' . $comentario->id .'" >Eliminar</a>';
        }
    }

    return $boton;
}

function dibujar_editar_comentario($comentario)
{
    $boton = '';

    if(is_login()){
        if($comentario->user_id == current_user()->id){
            $boton = '<a class="btn-editar-cmt btn btn-info btn-xs pull-right" '
                . ' data-action="' . url('biblia/editarcomentario') . '" '
                . ' data-token="' . csrf_token() . '" '
                . ' data-comentario-id="' . $comentario->id .'" >Editar</a>&nbsp;';
        }
    }

    return $boton;
}

function obtener_div_comentario($comentario, $es_par)
{
    $foto = dibujar_foto_comentario($comentario->user);
    $clase = 'par';
    if(!$es_par){
        $clase = 'impar';
    }

    $cadena =   '<div class="row cmt cmt-caja '.$clase.'" data-id="'.$comentario->id.'">'
                .$foto
                .'<div class="cmt-block">'
                .'<span class="cmt-versiculo">Versículo: '.$comentario->versiculo->numero_versiculo.'</span>'
                .'<p class="cmt-body cmt-contenido">'
                .$comentario->texto
                .'</p>'
                .'<span class="cmt-usuario">Por '.$comentario->user->nombres.' '.$comentario->user->apellidos.'</span>'
                .dibujar_eliminar_comentario($comentario)
                .dibujar_editar_comentario($comentario)
                .'</div>'
                .'</div>';

    return $cadena;
}

function dibujar_comentarios($lista)
{
    $cadena ='';

    if(count($lista) > 0){
        $contador = 1;
        foreach($lista as $comentario){
            $es_par = true;
            if(($contador % 2) > 0){
                $es_par = false;
            }
            $cadena = $cadena.obtener_div_comentario($comentario, $es_par);
            $contador++;
        }
    }else{
        $cadena = '<h3>No se encontraron comentarios</h3>';
    }

    return $cadena;
}

//dibuja los versiculos con check exepto al verso que se esta asignado las referencias.
function no_existe_versiculo_en_referencia($versiculo_id, $cita)
{
    $dato = torrefuerte\Models\Referencia::where('versiculo_id', $versiculo_id)
                                            ->where('cita', $cita)->first();

    if( is_null($dato)){
        return true;
    }else{
        return false;
    }
}

function dibujar_versiculos_add_referencia($lista, $verisculo_id)
{
    $cadena ='';
    $contador = 0;
    if(count($lista) > 0){
        $cadena = '<input name="_token" type="hidden" value="'.csrf_token().'"> ';
        foreach($lista as $objeto){
            if($objeto->id != $verisculo_id){
                if( no_existe_versiculo_en_referencia($verisculo_id, $objeto->id) ){
                    $contador++;
                    $cadena = $cadena.'<p>'
                        . '<input type="checkbox" name="versoBox[]" value="'.$objeto->id.'"/>'
                        . '<strong>'
                        . $objeto->numero_versiculo
                        . '</strong>'
                        . '&nbsp;' . $objeto->texto
                        . '</p>';
                }
            }
        }
        if($contador > 0){
            $cadena = $cadena . '<button id="btn-save-referencia" class="btn btn-primary btn-sm pull-right">Guardar Referencia</button>';
        }
    }

    return $cadena;
}

function dibujar_eliminar_referencia($objeto){
    $boton = '';

    if(is_login()){
        if($objeto->user_id == current_user()->id){
            $boton = '<br><a class="btn-eliminar-ref btn btn-danger btn-xs pull-right" '
                . ' data-action="' . url('biblia/referencia/eliminar') . '" '
                . ' data-token="' . csrf_token() . '" '
                . ' data-id="' . $objeto->id .'" '
                . ' data-user-id="' . $objeto->user_id . '">Eliminar</a>';
        }
    }

    return $boton;
}

function dibujar_referencias_cruzadas($lista)
{
    $cadena = '';

    if(count($lista)>0){
        foreach($lista as $objeto){
            $cadena = $cadena . '<p class="verso-referencia">'
                        . '<strong class="numero-verso-referencia">' . $objeto->referenciacruzada->libro->nombre
                        . ' '
                        . $objeto->referenciacruzada->numero_capitulo
                        . ': '
                        . $objeto->referenciacruzada->numero_versiculo
                        . '</strong><br>'
                        . $objeto->referenciacruzada->texto
                        . dibujar_eliminar_referencia($objeto)
                        . '</p>';
        }
    }else{
        $cadena = '<h3>No se encontraron referencias.</h3>';
    }

    return $cadena;
}

/*
* Imprimir estado de plan de lectura
*/
function dibujar_estado($estado)
{
    $cadena = '';

    if ($estado == 'pendiente') {
        $cadena = '<span class="label label-danger">' . strtoupper($estado) . '</span>';
    }elseif ($estado == 'finalizado') {
        $cadena = '<span class="label label-success">' . strtoupper($estado) . '</span>';
    }

    return $cadena;

}

/*
* Convertir texto a mayusculas
*/
function a_mayusculas($cadena)
{
    $cadena = strtr(strtoupper($cadena), 'àèìòùáéíóúçñäëïöü', 'ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ');

    return $cadena;
}

/*
* Obtener nombre del dia dado un numero
*/
function get_nombre_dia($dia)
{
    $nombre = '';

    switch ($dia) {
        case '0':
            $nombre = 'Domingo';
            break;
        case '1':
            $nombre = 'Lunes';
            break;
        case '2':
            $nombre = 'Martes';
            break;
        case '3':
            $nombre = 'Miércoles';
            break;
        case '4':
            $nombre = 'Jueves';
            break;
        case '5':
            $nombre = 'Viernes';
            break;
        case '6':
            $nombre = 'Sábado';
            break;        
        default:
            $nombre = 'Día no existe';
            break;
    }

    return $nombre;
}

/*
* Obtener nombres de dias
*/
function get_dias_lectura($dias)
{
    $vector = explode(';', $dias);

    for ($i=0; $i < count($vector); $i++) { 
        $vector[$i] = get_nombre_dia($vector[$i]);
    }

    return implode(', ', $vector);
    
}

/*
* Devolver la clase para un evento en calentar
*/

function get_class_event()
{
    $colect = collect([
        'event-warning', 
        'event-info', 
        'event-special', 
        'event-success', 
        'event-inverse', 
        'event-important'
    ]);

    return $colect->random();
}

/*
* Escribir el texto de lectura, por ejemplo: Rut 1:1-10
*/
function get_title_lectura($lectura)
{
    $cadena = $lectura->libro_nombre . ' ' . $lectura->capitulo . ': ' . $lectura->inicio
               . '-' . $lectura->final;

    return $cadena;
}

/*
* Obtener el tipo de lectura
*/
function get_tipo_plan($plan)
{
    $cadena = '';
    $tipo = $plan->tipo;
    switch ($tipo) {
        case 'propio':
            $cadena = 'Plan de Lectura Propio';
            break;
        case 'cronologico':
            $cadena = 'Plan de Lectura Cronológico';
            break;
        case 'variado':
            $cadena = 'Plan de Lectura Variado';
            break;
        case 'todo':
            $cadena = 'Plan de Lectura de Inicio a Fin';
            break;
    }

    return $cadena;
}

/*
* Retornar Anios
*/
function listar_anios()
{
    $anios = torrefuerte\Models\Articulo::listar_anios();

    $lista_anios = array();
    foreach ($anios as $anio) {
        $indice = $anio->anio;

        $lista_anios[$indice] = $anio->anio;
    }

    return $lista_anios;
}


/*
* Retornar lista de meses
*/
function listar_meses()
{
    $meses = [
        '1' =>  'Enero',
        '2' =>  'Febrero',
        '3' =>  'Marzo',
        '4' =>  'Abril',
        '5' =>  'Mayo',
        '6' =>  'Junio',
        '7' =>  'Julio',
        '8' =>  'Agosto',
        '9' =>  'Septiembre',
        '10' =>  'Octubre',
        '11' =>  'Noviembre',
        '12' =>  'Diciembre',
    ];

    return $meses;
}

/*
* Obtener una descripcion corta del articulo
*/
function descripcion_corta($articulo)
{
    $contenido = $articulo->contenido;
    $encontrar_p = '<p>';
    $encontrar_fin_p = '</p>';

    $pos_p = strpos($contenido, $encontrar_p);
    $pos_fin_p = strpos($contenido, $encontrar_fin_p, $pos_p);
    $cantidad_caracteres = $pos_fin_p + 4 - $pos_p;

    return substr($contenido, $pos_p, $cantidad_caracteres);

}

/*
* RETORNAR LOS MAS LEIDOS
*/
function mas_leidos()
{
    $mas_leidos = torrefuerte\Models\Articulo::where('numero_visitas' , '>', 0)
                                ->orderBy('numero_visitas', 'DESC')
                                ->take(10)->get();

    return $mas_leidos;
}

/*
* METODOS PARA LOS COMENTARIOS DE ARTICULOS
*/
function dibujar_eliminar_articulo_comentario($comentario)
{
    $boton = '';

    if(is_login()){
        if($comentario->user_id == current_user()->id){
            $boton = '<a class="btn-eliminar-comentario btn btn-danger btn-xs pull-right" '
                        . ' data-action="' . url('area-espiritual/eliminar-comentario') . '" '
                        . ' data-token="' . csrf_token() . '" '
                        . ' data-comentario-id="' . $comentario->id .'" >Eliminar</a>';
        }
    }

    return $boton;
}

function obtener_div_articulo_comentario($comentario)
{
    $foto = dibujar_foto_comentario($comentario->user);

    $cadena =   '<div class="row cmt cmt-caja" data-id="'.$comentario->id.'">'
                .$foto
                .'<div class="cmt-block">'
                .'<span class="cmt-usuario">Por '.$comentario->user->nombres.' '.$comentario->user->apellidos.'</span>'
                .'<p class="cmt-body cmt-contenido">'
                .$comentario->contenido
                .'<br>'
                .dibujar_eliminar_articulo_comentario($comentario)
                .'</p>'               
                .'<hr>'
                .'</div>'
                .'</div>';

    return $cadena;
}

function dibujar_articulo_comentarios($lista)
{
    $cadena ='';

    if(count($lista) > 0){
        foreach($lista as $comentario){            
            $cadena = $cadena.obtener_div_articulo_comentario($comentario);
        }
        $cadena = '<hr class="separador">'.$cadena;
    }else{
        $cadena = '';
    }

    return $cadena;
}

/*
* Establecer la imagen a mostrar en el modulo: cuenta->perfil
*/
function dibujar_foto_mi_cuenta()
{

    $cadena = '';
    $ruta = 'assets/fotos/';
    $foto = '';
    if(is_null(Auth::user()->foto)){
        $sexo = Auth::user()->sexo;
        if($sexo == 'Hombre'){
            $foto = 'avatar-hombre.png';
        }else{
            $foto = 'avatar-mujer.png';
        }

    }else{
        $foto = Auth::user()->foto;        
    }

    $ruta = $ruta . $foto;
    $cadena = '<img src="' . asset($ruta) . '" class="img-responsive center-block"/>';

    return $cadena;
}