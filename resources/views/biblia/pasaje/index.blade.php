@extends('master')

@section('topic')
    @include('topic')
@endsection

@section('maincontent')
    <div class="container">
        <div class="row">
            <!-- Formulario de Búsqueda -->
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Búsqueda
                    </div>
                    <div class="fondo-rojo panel-body">
                        {!! Form::model(Request::all(), ['url' => 'biblia/busqueda-pasaje/buscar', 'method' => 'POST']) !!}
                            <div class="form-group">
                                {!! Form::label('libro', 'Libro') !!}
                                {!! Form::select('libro', $libros, null, [
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
                            {{ Formcustom::buttonImg(' Buscar', 'assets/img/buscar-negro.png', [
                            'type'  => 'submit',
                            'class' => 'btn btn-primary btn-sm',
                            'id'    => 'btn-buscar-pasaje'
                            ]) }}
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
            <!-- Resultado de la búsqueda -->
            <div class="col-sm-9" id="resultado-busqueda">
                @include('partials.rpta_operacion')
                @if(isset($lista))
                    <h2 class="headline first-child" id="typography"><span class='letra-roja'>{{ $titulo_buscado }}</span></h2>
                    @foreach($lista as $objeto)
                        <div class="blog">
                            <div class="blog-desc">
                                @if(!is_null($objeto->titulo))
                                    <p class="versiculo-titulo fondo-rojo">
                                        {{ $objeto->titulo }}
                                    </p>
                                @endif
                                <p class="justificado">
                                    <strong class="numero-cita">{{ 'V-' . $objeto->numero_versiculo . '. ' }}</strong>
                                    {!! $objeto->texto !!}
                                    <br>
                                    <button class="btn btn-xs btn-success pull-right" data-toggle="modal"
                                            data-target="#accionesModal"
                                            data-versiculo-id="{{ $objeto->id }}"
                                            data-texto-seleccionado="{{ cita_biblica($objeto) }}" >
                                        Opciones
                                    </button>
                                </p>
                                <hr>
                            </div>
                        </div>
                    @endforeach

                @endif
            </div>
        </div>
    </div>
    @include('biblia.partials.modal')
@endsection