/**
 * Created by Elbin on 03-10-2015.
 * Este script contiene mis funciones
 */
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

/*
* Variables
 */

var versiculo_seleccionado='xxx';

/*
* Funciones de modal
 */
$(function(){
    $('#accionesModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('versiculo-id'); // Extract info from data-* attributes
        versiculo_seleccionado = button.data('texto-seleccionado');

        var modal = $(this);
        modal.find('#opciones_form input[name=versiculo_id]').val(id);
    });

    //+++++ cuando se abren los pop-up: eliminar el espacio que genera +++++++++++++++++++++++++++++++++++++++++++++
    $('#versionesModal').on('show.bs.modal', function (event) {
        $('body').addClass('modal-open');
        $('body').removeAttr('style');
    });

    $('#listComentariosModal').on('show.bs.modal', function (event) {
        $('body').addClass('modal-open');
        $('body').removeAttr('style');
    });

    $('#comentarioModal').on('show.bs.modal', function (event) {
        $('body').addClass('modal-open');
        $('body').removeAttr('style');
    });

    $('#editarComentarioModal').on('show.bs.modal', function (event) {
        $('body').addClass('modal-open');
        $('body').removeAttr('style');
    });

    $('#addReferenciaModal').on('show.bs.modal', function (event) {
        $('body').addClass('modal-open');
        $('body').removeAttr('style');
    });

    $('#listReferenciaModal').on('show.bs.modal', function(event){
        $('body').addClass('modal-open');
        $('body').removeAttr('style');
    });

    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    //cuando se cierra el formulario de comentario, dejar sin valor el input del id de versiculo
    $('#comentarioModal').on('hide.bs.modal', function (event) {
        var modal = $(this);
        modal.find('#form-comentario input[name=versiculo_id]').val(null);
        modal.find('#form-comentario textarea[name=texto]').val(null);
    });

    //cuando se cierra el formulario de editar comentario, dejar sin valor el input del id de versiculo
    $('#editarComentarioModal').on('hide.bs.modal', function (event) {
        var modal = $(this);
        modal.find('#form-editar-comentario input[name=id]').val(null);
        modal.find('#form-editar-comentario input[name=versiculo_id]').val(null);
        modal.find('#form-editar-comentario textarea[name=texto]').val(null);
    });

    //cuando se cierra el formulario de agregar referencia
    $('#addReferenciaModal').on('hide.bs.modal', function (event) {
        var modal = $(this);
        modal.find('#frm-search-referencia input[name=versiculo_id]').val(null);
        var $miSelect = modal.find('#frm-search-referencia select[name=libro_id]');
        $miSelect.selectpicker('val', '');
        var form = modal.find('#frm-search-referencia');
        form.resetearForm();
        modal.find('#frm-add-referencia').html('');
    });
});

/*
* Validar si un numero es entero
 */
function esNumeroEntero(numero){
    if(isNaN(numero)){
        return false;
    }else{
        if(numero - Math.floor(numero) == 0){
            return true;
        }else{
            return false;
        }
    }
};

/*
* Dibujar div con lista de errores
 */
function dibujarDivErrores(errors){
    var div = '<div class="alert alert-danger" role="alert"><ul>';

    for(var i = 0; i < errors.length; i++ ){
        div = div + '<li class="error-item">' + errors[i] + '</li>';
    }

    div = div + '</ul></div>';

    return div;
};

/*
* Resetear o limpiar los campos de un formulario
 */
jQuery.fn.resetearForm = function () {
    $(this).each (function() { this.reset(); });
};

