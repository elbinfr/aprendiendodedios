<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [
    'uses' => 'Inicio\InicioController@index',
    'as'   => 'inicio'
]);

/*
 * Acciones para el modulo de Biblia
 */
Route::group(['namespace' => 'Biblia', 'prefix' => 'biblia'], function()
{
    //Búsqueda por Pasaje
    Route::get('busqueda-pasaje', 'PasajeController@index');

    Route::post('busqueda-pasaje/buscar', 'PasajeController@buscarPasaje');
    Route::post('mostrarversiones', 'PasajeController@mostrarVersiones');
    Route::post('guardarcomentario', 'PasajeController@guardarComentario');
    Route::post('listarcomentarios', 'PasajeController@getComentarios');
    Route::post('editarcomentario', 'PasajeController@editarComentario');
    Route::post('actualizarcomentario', 'PasajeController@actualizarComentario');
    Route::post('eliminarcomentario', 'PasajeController@eliminarComentario');

    Route::post('referencia/buscar_pasaje', 'ReferenciaController@buscarPasaje');
    Route::post('referencia/agregar', 'ReferenciaController@agregar');
    Route::post('referencia/listar', 'ReferenciaController@listar');
    Route::post('referencia/eliminar', 'ReferenciaController@eliminar');

    //Búsqueda por Palabra o Frase
    Route::get('busqueda-palabra', 'PalabraController@index');
    Route::get('busqueda-palabra/buscar', 'PalabraController@buscarFrase');

    //mostrar comentarios
    Route::post('mostrar-comentarios', 'ComentarioController@listarPorVersiculo');

    //Planes de Lectura
    Route::get('plan-lectura', 'PlanController@index');

    Route::get('mis-planes-de-lectura', 'PlanController@misPlanes');
    Route::get('mis-planes-de-lectura/ver/{slug}', 'PlanController@ver');
    Route::get('mis-planes-de-lectura/detalle', 'PlanController@detalle');
    Route::get('mis-planes-de-lectura/leer/{slug}', 'PlanController@leer');
    Route::post('mis-planes-de-lectura/finalizar', 'PlanController@finalizar');
    Route::get('mis-planes-de-lectura/eliminar/{slug}', 'PlanController@eliminar');
    Route::get('mis-planes-de-lectura/descargar/{slug}', 'PlanController@descargar');

    Route::get('plan-lectura-propio/nuevo', 'PlanPropioController@nuevo');
    Route::post('plan-lectura-propio/guardar', 'PlanPropioController@guardar');  

    Route::get('plan-lectura-sugerido/nuevo', 'PlanSugeridoController@nuevo');
    Route::post('plan-lectura-sugerido/guardar', 'PlanSugeridoController@guardar');
});

/*
* Acciones del Modulo: Area Espiritual
*/
Route::group(['namespace' => 'AreaEspiritual', 'prefix' => 'area-espiritual'], function()
{
    Route::get('todos-los-articulos', 'ArticuloController@todos');
    Route::get('mis-articulos', 'ArticuloController@misArticulos');
    Route::get('crear-articulo', 'ArticuloController@crear');
    Route::post('crear-articulo', 'ArticuloController@guardar');
    Route::get('leer/{slug}', 'ArticuloController@leer');
    Route::get('eliminar/{slug}', 'ArticuloController@eliminar');
    Route::get('editar-articulo/{slug}', 'ArticuloController@editar');
    Route::post('actualizar-articulo', 'ArticuloController@actualizar');

    Route::post('comentar-articulo', 'ArticuloComentarioController@guardar');
    Route::post('eliminar-comentario', 'ArticuloComentarioController@eliminar');

    //Opcion para buscar versiculo en Agregar articulo
    Route::get('buscar-versiculo', 'VersiculoController@getBuscar');
    Route::post('buscar-versiculo', 'VersiculoController@postBuscar');

});

/*
 * Acciones del Modulo: Contacto
 */
Route::group( ['namespace' => 'Contacto'], function()
{
    Route::get('contacto', [
        'uses' => 'ContactoController@index',
        'as'   => 'contacto'
    ]);

    Route::post('enviarMensaje', 'ContactoController@enviarMensaje');
});

/*
* Acciones del Modulo: Cuenta
*/
Route::group(['namespace' => 'Cuenta', 'prefix' => 'cuenta'], function()
{
    Route::get('mi-perfil', 'PerfilController@index');
    Route::get('notificaciones', 'PerfilController@ver_notificaciones');
    Route::get('modificar-datos', 'PerfilController@modificar_datos');
    Route::post('modificar-datos', 'PerfilController@actualizar_datos');
    Route::get('cambiar-password', 'PerfilController@cambiar_password');
    Route::post('cambiar-password', 'PerfilController@actualizar_password');
});

// LOGIN DE USUARIOS
Route::get('ingresar', [
    'uses' => 'Auth\AuthController@getLogin',
    'as'   => 'ingresar'
]);

Route::post('ingresar', 'Auth\AuthController@postLogin');

Route::get('salir', [
    'uses' => 'Auth\AuthController@getLogout',
    'as'   => 'salir'
]);

// RESTABLECER CONTRASEÑA
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// MOSTRAR FORMULARIO DE REGISTRO DE USUARIOS...
Route::get('registrarse', [
    'uses' => 'Auth\AuthController@getRegister',
    'as'   => 'registrarse'
]);

Route::post('register', 'Auth\AuthController@postRegister');

Route::get('confirmation/{token}', [
    'uses' => 'Auth\AuthController@getConfirmation',
    'as'   => 'confirmation'
]);

//MODULO DE ADMINISTRACIÓN
Route::group( ['namespace' => 'Administracion', 'prefix' => 'administracion'], function()
{
    //Acciones para Perfiles de Usuario
    Route::get('perfil/buscar', 'PerfilController@getBuscar');
    Route::post('perfil/buscar', 'PerfilController@postBuscar');
    Route::resource('perfil', 'PerfilController');
});


Route::get('prueba', function(){

    $fecha_hoy = Carbon\Carbon::today();
    
    $planes = torrefuerte\Models\Plan::where('id',7)->first();
    $user = $planes->user;

    $cronogramas = torrefuerte\Models\Cronograma::whereEstado('pendiente')
                                        ->where('plan_id', $planes->id)
                                        ->where('fecha', $fecha_hoy)
                                        ->first();

    $url = url('ingresar');
    if(count($cronogramas) > 0)
    {
        $lecturas = $cronogramas->lecturas;

        return view('emails.lecturas_hoy', compact('user', 'lecturas', 'url'));
    }

});