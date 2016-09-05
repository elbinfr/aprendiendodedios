@extends('master')

@section('topic')
    @include('topic')
@endsection

@section('maincontent')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="headline first-child"><span>B&uacute;queda de Perfil de Usuario</span></h3>
                <div class="col-sm-7">
                    @include('partials.rpta_operacion')
                    {!! Form::open( ['url' => 'administracion/perfil/buscar', 'method' => 'POST']) !!}
                        <div class="form-group">
                            {!! Form::label('criterio', 'Criterio de B&uacute;squeda') !!}
                            {!! Form::select('criterio', $columnas, null, [
                                'class' => 'form-control',
                                'placeholder' => 'Seleccione ...'
                                ]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('dato', 'Dato') !!}
                            {!! Form::text('dato', null, ['class' => 'form-control']) !!}
                        </div>
                        {!! Formcustom::buttonImg('Buscar', 'assets/img/buscar-negro.png', [
                            'class' => 'btn btn-primary',
                            'type'  => 'submit',
                            'id'    => 'btn-buscar-crud'
                            ]) !!}
                        {{ Formcustom::buttonCancelImg('administracion.perfil.index', 'Cancelar', 'assets/img/cancelar-negro.png', [
                            'class' => 'btn btn-danger'
                            ]) }}
                    {!! Form::close() !!}
                </div>
                <div class="col-sm-10" >
                    <div class="table-responsive">
                        <br>
                        <table id="tabla-result" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed table-hover" >
                            <thead>
                                <tr class="tabla-cabecera">
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Descripci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection