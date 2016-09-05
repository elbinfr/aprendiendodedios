@extends('master')
@section('topic')
  @include('topic')
@endsection
@section('maincontent')
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
        <h3 class="headline first-child"><span>Nuevo Perfil de Usuario</span></h3>
        @include('partials.rpta_operacion')
        {!! Form::open(['route' => 'administracion.perfil.store', 'method' => 'POST']) !!}
          @include('administracion.perfil.partials.fields')
          {{ Formcustom::buttonImg(' Guardar Perfil', 'assets/img/guardar-negro.png', ['type' => 'submit', 'class' => 'btn btn-primary']) }}
          &nbsp;&nbsp;&nbsp;
          {{ Formcustom::buttonCancelImg('administracion.perfil.index', 'Cancelar', 'assets/img/cancelar-negro.png', ['class' => 'btn btn-danger']) }}
        {!! Form::close() !!}
      </div>
      <div class="col-sm-4">
        <div class="info-board info-board-theme-primary">
          <p>
            Los perfiles sirven para controlar los accesos que tienen los usuarios a las opciones del sistema.
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection
