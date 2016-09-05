/**
 * Created by Elbin on 03-10-2015.
 * Este script contiene las operaciones que se ejecutan al presionar en botones, enlaces, etc
 */

//---------------------------------------------------------------------------------------------

/*
 *Enviar mensaje desde la opcion de Contacto
 */
$('#btn-contacto').click(function(e){
    e.preventDefault();

    var frm = $(this).parents('form');
    var route = frm.attr('action');
    var datos = frm.serialize();

    $.ajax(
        {
            url:        route,
            type:       'POST',
            dataType:   'json',
            data:       datos,
            success:    function(result){
                alert(result.mensaje.captcha);
            }
        }
    );

});

/*
 * Script para realizar busqueda en operaciones CRUD
 */
$('#btn-buscar-crud').click(function(e){
    e.preventDefault();
    var tabla = $('#tabla-result');
    var filas = tabla.find('tbody');
    var form = $(this).parents('form');

    var criterio = $('#criterio').val();
    var dato = $('#dato').val();

    if(criterio == ''){
        swal("", "Debe seleccionar criterio de busqueda.", "error");
        return false;
    }
    if(dato == ''){
        swal("", "Debe ingresar el dato a buscar.", "error");
        return false;
    }

    var action = form.attr('acction');
    var data = form.serialize();

    $.post(action, data, function(result){
        tabla.show();
        filas.html(result);
    });
});

/*
 * Eliminar registros (en operaciones CRUD)
 */
$('.btn-delete').click(function(e){
    e.preventDefault();
    var mensaje = "Ha seleccionado eliminar el registro ";
    var row = $(this).parents('tr'); //obtengo en que fila hice click
    var id = row.data('id');
    var msg = mensaje.concat(id, '.');

    var form = $('#form-delete');
    var accion = form.attr('action').replace(':DATO_ID', id);
    var data = form.serialize();

    swal({
        title: "Esta Seguro?",
        text: msg,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    }, function(){
        $.post(accion, data, function(result){
            row.fadeOut();
            swal("", result, "success");
        }).fail(function(result){
            swal("", result, "error");
        });
    });

});

/*
* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* FUNCIONES PARA BUSQUEDA DE PASAJE
 */

/*
* Validacion de datos registrado
 */
function validarBuquedaPasaje(libro, capitulo, verso_ini, verso_fin){
    var msj = '';
    //validaciones
    if(libro == null || libro == ''){
        swal('', 'Debe ingresar libro a buscar.', 'error');
        return false;
    }
    if(capitulo == null || capitulo == ''){
        swal('', 'Debe ingresar el capitulo.', 'error');
        return false;
    }
    if(!esNumeroEntero(capitulo)){
        swal('', 'Capitulo debe ser un numero.', 'error');
        return false;
    }
    if(parseInt(capitulo) <= 0){
        swal('', 'Capitulo debe ser mayor que cero.', 'error');
        return false;
    }
    //si ha ingresado el versiculo inicial
    if( !(verso_ini == null || verso_ini == '') ){
        if( !esNumeroEntero(verso_ini)){
            swal('', 'Verso debe ser numero.', 'error');
            return false;
        }
        if(parseInt(verso_ini) <=0 ){
            swal('', 'Verso debe ser mayor que cero', 'error');
            return false;
        }
    }
    //si ha ingresado el versiculo final
    if( !(verso_fin == null || verso_fin == '') ){
        if(verso_ini == null || verso_ini == ''){
            swal('', 'Debe ingresar el versiculo inicial.','error');
            return false;
        }
    }
    if( (verso_ini != null && verso_ini != '') && (verso_fin != null && verso_fin != '') ){
        if( !(esNumeroEntero(verso_ini)) || !(esNumeroEntero(verso_fin)) ){
            swal('', 'Los versos deben ser numeros.', 'error');
            return false;
        }
        if(parseInt(verso_ini) <= 0 || parseInt(verso_fin) <= 0){
            swal('', 'Los versos deben ser numeros mayores que cero.', 'error');
            return false;
        }
        if( !(parseInt(verso_fin) >= parseInt(verso_ini)) ){
            swal('', 'El verso final debe ser mayor o igual que el verso inicial', 'error');
            return false;
        }
    }

    return true;
};

/*
 * Funcion cuando presiono click en el numero de un versiculo
 * para cargar el texto en otras versiones,
 * cargar referencias del versiculo
 */
$('#accionesModal').on("click", "#btn-show-versiones", function(){
    var boton = $(this);
    var form = boton.parents('form');
    var action = boton.data('action');
    var data = form.serialize();
    $('#accionesModal').modal('hide');

    $.post(action, data, function(result){
        $('#versionesModal').find('.modal-body').html(result.contenido);
        $('#versionesModal').find('#versionesModalLabel').html( versiculo_seleccionado + ' (Otras Versiones) ' );
        $('#versionesModal').modal('show');
    });
});

/*
 * Función para mostrar el Modal que contiene la lista de comentarios
 */
$('#accionesModal').on("click", "#btn-show-comentarios", function(){
    var boton = $(this);
    var form = boton.parents('form');
    var action = boton.data('action');
    var data = form.serialize();
    $('#accionesModal').modal('hide');
    var modal = $('#listComentariosModal');

    $.post(action, data, function(result){
        if(result.resultado == 'success'){
            $('#listComentariosModal').find('.modal-body').html(result.comentarios);
            $('#listComentariosModal').find('#listComentariosModalLabel').html( versiculo_seleccionado + ' (Comentarios) ' );
            $('#listComentariosModal').modal('show');
        }else{
            var error = dibujarDivErrores(result.errors);
            swal({
                title: "",
                text: error,
                type: "error",
                html: true
            });
        }
    });
});

/*
* Función para mostrar el Modal que contiene el formulario para agregar comentario
 */
$('#accionesModal').on("click", "#btn-add-comentario", function(){
    var boton = $(this);
    var form = boton.parents('form');
    var versiculo_id = form.find('input[name=versiculo_id]').val();
    $('#accionesModal').modal('hide');
    var modal = $('#comentarioModal');

    modal.on('show.bs.modal', function (event) {
        var modal = $(this);
        modal.find('#form-comentario input[name=versiculo_id]').val(versiculo_id);
    });

    $('#accionesModal').modal('hide');
    $('#comentarioModal').find('#comentarioModalLabel').html( versiculo_seleccionado + '(Comentar)');
    $('#comentarioModal').modal('show');
});

/*
* Funcion para guardar comentario
 */
$('#btn_GuardarComentario').click(function(e){
    var form = $(this).parents('form');
    var action = form.attr('action');
    var data = form.serialize();

    $.post(action, data, function(result){
        if(result.resultado == true){            
            $('#comentarioModal').modal('hide');
            swal('', 'Operación exitosa', 'success');
        }else{
            var error = dibujarDivErrores(result.errors);
            swal({
                title: "",
                text: error,
                type: "error",
                html: true
            });
        }
    });
});

/*
* Mostrar comentario para editar
 */
$('#listComentariosModal').on("click", ".btn-editar-cmt", function(e){
    var boton = $(this);
    var parametros = { _token : boton.data('token'), comentario_id : boton.data('comentario-id') };
    var action = boton.data('action');
    var data = $.param(parametros);
    $('#listComentariosModal').modal('hide');
    var modal = $('#editarComentarioModal');

    $.post(action, data, function(result){
        if(result.resultado == true){

            modal.on('show.bs.modal', function (event) {
                var modal = $(this);
                modal.find('#form-editar-comentario input[name=id]').val(result.comentario.id);
                modal.find('#form-editar-comentario input[name=versiculo_id]').val(result.comentario.versiculo_id);
                modal.find('#form-editar-comentario textarea[name=texto]').val(result.comentario.texto);
            });
            $('#editarComentarioModal').find('#editarcomentarioModalLabel').html(versiculo_seleccionado +'(Editar Comentario)');
            $('#editarComentarioModal').modal('show');
        }else{
            var error = dibujarDivErrores(result.errors);
            swal({
                title: "",
                text: error,
                type: "error",
                html: true
            });
        }
    });
});

/*
 * Funcion para editar comentario
 */
$('#btn_EditarComentario').click(function(e){
    var form = $(this).parents('form');
    var action = form.attr('action');
    var data = form.serialize();

    $.post(action, data, function(result){
        if(result.resultado == true){
            $('#editarComentarioModal').modal('hide');
            swal('', 'Operación exitosa', 'success');
        }else{
            var error = dibujarDivErrores(result.errors);
            swal({
                title: "",
                text: error,
                type: "error",
                html: true
            });
        }
    });
});

/*
* Eliminar comentario
 */
$('#listComentariosModal').on("click", ".btn-eliminar-cmt",function(e){
    var boton = $(this);
    var parametros = { _token : boton.data('token'), comentario_id : boton.data('comentario-id')};
    var action = boton.data('action');
    var data = $.param(parametros);

    swal({
        title: "Esta Seguro?",
        text: "Ha seleccionado eliminar el comentario.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    }, function(){
        $.post(action, data, function(result){
            if(result.resultado == true){
                $('#listComentariosModal').modal('hide');
                swal('', 'Comentario eliminado correctamente', 'success');
            }else{
                var error = dibujarDivErrores(result.errors);
                swal({
                    title: "",
                    text: error,
                    type: "error",
                    html: true
                });
            }
        });
    });
});

/*
* Mostrar en formulario para agregar referencia
 */
$('#accionesModal').on("click", "#btn-add-referencia", function(){
    var boton = $(this);
    var form = boton.parents('form');
    var versiculo_id = form.find('input[name=versiculo_id]').val();

    $('#addReferenciaModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        modal.find('#frm-search-referencia input[name=versiculo_id]').val(versiculo_id);
    });
    $('#addReferenciaModal').find('#addReferenciaModalLabel').html(versiculo_seleccionado + '(Agregar Referencia)');
    $('#addReferenciaModal').modal('show');
});

/*
* Buscar pasaje para agregar referencia
 */
$('#frm-search-referencia').on("click", "#btn-search-texto", function(e){
    e.preventDefault();
    var boton = $(this);
    var form = boton.parents('form');
    var action = form.attr('action');
    var data = form.serialize();
    var versiculo_id = form.find('input[name=versiculo_id]').val();

    $.post(action, data, function(result){
        if(result.resultado == true){
            var html = '<input type="hidden" name="versiculo_id" value="'+versiculo_id+'"/>';
            $('#frm-add-referencia').html(html + result.versiculos);
        }else{
            var error = dibujarDivErrores(result.errors);
            swal({
                title: "",
                text: error,
                type: "error",
                html: true
            });
        }
    });
});

/*
* Click en boton guardar referencia
 */
$('#frm-add-referencia').on("click", "#btn-save-referencia", function(e){
    e.preventDefault();
    var boton = $(this);
    var form = boton.parents('form');
    var action = form.attr('action');

    var lista_versos = '';
    var contador = 0;
    form.find('input[name="versoBox[]"]:checked').each(function(){
        lista_versos += $(this).val() + ';';
        contador++;
    });

    if(contador==0){
        swal("", "Debe seleccionar por lo menos un verso.", "error");
    }else{
        lista_versos = lista_versos.substring(0, lista_versos.length-1);
        var parametros = { _token: form.find('input[name=_token]').val(), versiculo_id: form.find('input[name=versiculo_id]').val(), lista_versos: lista_versos };
        var data = $.param(parametros);

        $.post(action, data, function(result){
            if(result.resultado == true){
                $('#addReferenciaModal').modal('hide');
                swal('', 'Referencia agregada con éxito', 'success');
            }else{
                var error = dibujarDivErrores(result.errors);
                swal({
                    title: "",
                    text: error,
                    type: "error",
                    html: true
                });
            }
        });
    }
});

/*
* Ver referencias
 */
$('#accionesModal').on("click", "#btn-list-referencia", function(){
    var boton = $(this);
    var form = boton.parents('form');
    var action = boton.data('action');
    var data = form.serialize();
    $('#accionesModal').modal('hide');

    $.post(action, data, function(result){
        if(result.resultado == true){
            $('#listReferenciaModal').find('.modal-title').html(versiculo_seleccionado + '(Referencias)');
            $('#listReferenciaModal').find('.modal-body').html(result.versiculos);
            $('#listReferenciaModal').modal('show');
        }else{
            var error = dibujarDivErrores(result.errors);
            swal({
                title: "",
                text: error,
                type: "error",
                html: true
            });
        }
    });
});

/*
* Eliminar Referencia
 */
$('#listReferenciaModal').on("click", ".btn-eliminar-ref", function(e){
    var boton = $(this);
    var parametros = { _token : boton.data('token'), id : boton.data('id'), user_id: boton.data('user-id')};
    var action = boton.data('action');
    var data = $.param(parametros);

    swal({
        title: "Esta Seguro?",
        text: "Ha seleccionado eliminar referencia.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    }, function(){
        $.post(action, data, function(result){
            if(result.resultado == 'success'){
                $('#listReferenciaModal').modal('hide');
                swal('', result.msg_resultado, 'success');
            }else if(result.resultado == 'info') {
                $('#listReferenciaModal').modal('hide');
                swal('', result.msg_resultado, 'info');
            }else{
                var error = dibujarDivErrores(result.errors);
                swal({
                    title: "",
                    text: error,
                    type: "error",
                    html: true
                });
            }
        });
    });
});

/*
* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

/*
* ELIMINAR PLAN DE LECTURA
*/
$('.btn-elimimar-plan').click(function(e){
    e.preventDefault();
    var boton = $(this);
    var nombre = boton.data('nombre');
    var action = boton.attr('href');
    
    swal({
        title: "Esta Seguro?",
        text: "Ha seleccionado eliminar el plan: " + nombre,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    }, function(){
        window.location = action;
    });
});

/*
* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

/*
* ELIMINAR ARTICULO
*/
$('.btn-elimimar-articulo').click(function(e){
    e.preventDefault();
    var boton = $(this);
    var nombre = boton.data('nombre');
    var action = boton.attr('href');
    
    swal({
        title: "Esta Seguro?",
        text: "Ha seleccionado eliminar el articulo: " + nombre,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    }, function(){
        window.location = action;
    });
});