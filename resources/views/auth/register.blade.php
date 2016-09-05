@extends('master')

@section('topic')
    @include('topic')
@endsection

@section('maincontent')
    <div class="container">
      <div class="row">
        <div class="col-sm-7">
          <h3 class="headline first-child letra-roja"><span>Registro De Nuevo Usuario</span></h3>
          @include('partials.rpta_operacion')
          {!! Form::open(['url' => 'register', 'method' => 'POST', 'files' => true]) !!}
            <div class="form-group">
              {!! Form::label('nombres', 'Nombre') !!}
              {!! Form::text('nombres', null, ['class' => 'form-control'])!!}
            </div>
            <div class="form-group">
              {!! Form::label('apellidos', 'Apellidos') !!}
              {!! Form::text('apellidos', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('sexo', 'Sexo') !!}
              {!! Form::select('sexo', [
                  'Hombre' => 'Hombre',
                  'Mujer' => 'Mujer'
                  ], null, ['class' => 'custom-select form-control', 'placeholder' => 'Seleccione ...']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('pais', 'Pa&iacute;s') !!}
              {!! Form::select('pais', $paises, null, [
                'class' => 'custom-select form-control',
                'placeholder' => 'Elija un país ...',
                'data-size' => '10',
                'data-live-search' => 'true']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('celular', 'N&uacute;mero de Celular') !!}
              {!! Form::text('celular', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('email', 'Correo Electr&oacute;nico') !!}
              {!! Form::email('email', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('password', 'Contraseña') !!}
              {!! Form::password('password', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('password_confirmation', 'Confirma Contraseña') !!}
              {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('foto', 'Foto de Perfil') !!}
              {!! Form::file('foto', ['class' => 'file custom-image']) !!}
            </div>
            {!! Form::button('Guardar Datos', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm']) !!}
            &nbsp;
            {!! link_to('/ingresar', $title = 'Volver', $attributes = ['class' => 'btn btn-theme-primary btn-sm'], $secure = null) !!}
            
          {!! Form::close() !!}
        </div>

        <div class="col-sm-5">
          <h3 class="headline second-child letra-roja"><span>¿Porqu&eacute; registrarme?</span></h3>
          <div class="info-board info-board-theme-primary">
            <p>
              Al registrate es creada tu cuenta de usuario, la cual te brinda acceso a opciones como:
              <br>
              <ul>
                <li>Comentar vers&iacute;culos.</li>
                <li>Referenciar vers&iacute;culos</li>
                <li>Seleccionar vers&iacute;culos favoritos</li>
                <li>Hacer el seguimiento de tu plan de lectura</li>
                <li>Publicar tus art&iacute;culos</li>
              </ul>
            </p>
          </div>
        </div>

      </div>
    </div>
@endsection
