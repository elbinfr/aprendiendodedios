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
                        {!! Form::model(Request::all(), ['url' => 'biblia/busqueda-palabra/buscar', 'method' => 'GET']) !!}
                            <div class="form-group">
                                {!! Form::label('frase', 'Ingrese frase a buscar') !!}
                                {!! Form::text('frase', null, ['class' => 'form-control']) !!}
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
                    @foreach($lista as $objeto)
                        <div class="blog">
                            <div class="blog-desc">
                                <strong class="numero-cita">{{ $objeto->libro->nombre . ' ' . $objeto->numero_capitulo . ': ' . $objeto->numero_versiculo }}</strong>
                                <br>
                                <p class="justificado">
                                    {{ $objeto->texto }}
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
                    <div class="pull-right">
                        {!! $lista->appends(['frase' => $frase])->render() !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('biblia.partials.modal')
@endsection