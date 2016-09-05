<!-- MODAL : muestra las opciones para el usuario-->
<div class="modal fade" id="accionesModal" tabindex="-1" role="dialog" aria-labelledby="accionesModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="accionesModalLabel">Elija una opción</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => '', 'id' => 'opciones_form']) !!}
                    <input type="hidden" name="versiculo_id">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-show-versiones"
                            data-action="{{ url('biblia/mostrarversiones') }}" >
                        Ver Otras Versiones
                    </button>
                    @if(is_login())
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-add-comentario" >
                            Comentar Verículo
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-add-referencia" >
                            Agregar Referencia
                        </button>
                    @endif
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-list-referencia"
                            data-action="{{ url('biblia/referencia/listar') }}" >
                        Ver Referencias
                    </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- MODAL : Ventana con el versiculo en otras versiones -->
<div class="modal fade" id="versionesModal" tabindex="-1" role="dialog" aria-labelledby="versionesModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="versionesModalLabel">Texto en otras versiones</h4>
            </div>
            <div class="modal-body capa-scroll">

            </div>
            <div class="modal-footer">
                {!! Form::button('Cerrar', ['type' => 'button', 'class' => 'btn btn-danger btn-sm', 'data-dismiss' => 'modal']) !!}
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Ventana con formulario para comentar versiculo-->
@if(is_login())
    <div class="modal fade" id="comentarioModal" tabindex="-1" role="dialog" aria-labelledby="comentarioModalLabel">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="comentarioModalLabel">Comentar Versiculo</h4>
                </div>
                <div class="modal-body">
                    <div class="cmt well">
                        {!! dibujar_foto() !!}
                        <div class="cmt-block">
                            <strong>{{ current_user()->nombres . ' ' . current_user()->apellidos }}</strong>
                            {!! Form::open(['url' => 'biblia/guardarcomentario', 'id' => 'form-comentario', 'class' => 'cmt-body', 'role' => 'form']) !!}
                                {!! Form::hidden('user_id', current_user()->id, null) !!}
                                {!! Form::hidden('versiculo_id', null, null) !!}
                                <div class="form-group">
                                    {!! Form::textarea('texto', null, ['class' => 'form-control texarea-comentario', 'rows' => '4']) !!}
                                </div>
                                {!! Form::button('Guardar Comentario', ['type' => 'button', 'class' => 'btn btn-primary btn-sm', 'id' => 'btn_GuardarComentario']) !!}
                                {!! Form::button('Cerrar', ['type' => 'button', 'class' => 'btn btn-danger btn-sm', 'data-dismiss' => 'modal']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA EDITAR COMENTARIO-->
    <div class="modal fade" id="editarComentarioModal" tabindex="-1" role="dialog" aria-labelledby="editarcomentarioModalLabel">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editarcomentarioModalLabel">Editar Comentario</h4>
                </div>
                <div class="modal-body">
                    <div class="cmt well">
                        {!! dibujar_foto() !!}
                        <div class="cmt-block">
                            <strong>{{ current_user()->nombres . ' ' . current_user()->apellidos }}</strong>
                            {!! Form::open(['url' => 'biblia/actualizarcomentario', 'id' => 'form-editar-comentario', 'class' => 'cmt-body', 'role' => 'form']) !!}
                                {!! Form::hidden('id', null, null) !!}
                                {!! Form::hidden('user_id', current_user()->id, null) !!}
                                {!! Form::hidden('versiculo_id', null, null) !!}
                                <div class="form-group">
                                    {!! Form::textarea('texto', null, ['class' => 'form-control texarea-comentario', 'rows' => '4']) !!}
                                </div>
                                {!! Form::button('Actualizar Comentario', ['type' => 'button', 'class' => 'btn btn-primary btn-sm', 'id' => 'btn_EditarComentario']) !!}
                                {!! Form::button('Cerrar', ['type' => 'button', 'class' => 'btn btn-danger btn-sm', 'data-dismiss' => 'modal']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MPDAL PARA AGREGAR REFERENCIA -->
    <div class="modal fade" id="addReferenciaModal" tabindex="-1" role="dialog" aria-labelledby="addReferenciaModalLabel">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addReferenciaModalLabel">Agregar Referencia</h4>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-sm-6">
                            {!! Form::open(['url' => 'biblia/referencia/buscar_pasaje', 'id' => 'frm-search-referencia']) !!}
                            {!! Form::hidden('versiculo_id', null, null) !!}
                                <div class="form-group">
                                    {!! Form::label('libro_id', 'Libro') !!}
                                    {!! Form::select('libro_id', $libros_referencia, null, [
                                        'class' => 'custom-select form-control',
                                        'placeholder' => 'Elija un libro ...',
                                        'data-size' => '10',
                                        'data-live-search' => 'true']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('capitulo', 'Cap&iacute;tulo') !!}
                                    {!! Form::text('capitulo', null, ['class' => 'form-control' ,
                                        'maxlength' => '3',
                                        'size'      => '3']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('versiculos', 'Vers&iacute;culos') !!}
                                    {!! Form::text('versiculos', null, ['class' => 'form-control' ,
                                        'placeholder' => 'Por ejemplo 1-10']) !!}
                                </div>
                                {!! Form::button('Buscar', ['type' => 'submit',
                                    'class' => 'btn btn-primary btn-sm',
                                    'id' => 'btn-search-texto']) !!}
                            {!! Form::close() !!}
                        </div>
                        <div class="col-sm-6 capa-scroll">
                            {!! Form::open(['url' => 'biblia/referencia/agregar', 'method' => 'POST', 'id' => 'frm-add-referencia']) !!}
                                {!! Form::hidden('versiculo_id', null, null) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('Cerrar', ['type' => 'button', 'class' => 'btn btn-danger btn-sm', 'data-dismiss' => 'modal']) !!}
                </div>
            </div>
        </div>
    </div>
@endif

<!-- MODAL : Ventana que muestra las referencias bíblicas -->
    <div class="modal fade" id="listReferenciaModal" tabindex="-1" role="dialog" aria-labelledby="listReferenciaModalLabel">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="listReferenciaModalLabel">Referencias</h4>
                </div>
                <div class="modal-body capa-scroll">

                </div>
                <div class="modal-footer">
                    {!! Form::button('Cerrar', ['type' => 'button', 'class' => 'btn btn-danger btn-sm', 'data-dismiss' => 'modal']) !!}
                </div>
            </div>
        </div>
    </div>