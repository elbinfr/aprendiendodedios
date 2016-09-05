@extends('master')

@section('topic')
    @include('topic')
@endsection

@section('maincontent')
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
	            <div class="user-avatar shadow-effect text-center">
	            	{!! dibujar_foto_mi_cuenta() !!}
	            	{{ $user->nombres . ' ' . $user->apellidos }}
	            </div>
	            <div class="panel panel-default">
	            	@include('cuenta.partials.opciones')
	            </div>
	        </div>
	        <div class="col-sm-9">
	        	<div class="row">
	        		<h3 class="headline first-child letra-roja"><span>Modificar Datos</span></h3>
	        		@include('partials.rpta_operacion')
					{!! Form::open(['url' => 'cuenta/modificar-datos', 'method' => 'POST', 'files' => true]) !!}
						<div class="form-group">
							{!! Form::label('nombres', 'Nombre') !!}
							{!! Form::text('nombres', $user->nombres, ['class' => 'form-control'])!!}
						</div>
						<div class="form-group">
							{!! Form::label('apellidos', 'Apellidos') !!}
							{!! Form::text('apellidos', $user->apellidos, ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::label('sexo', 'Sexo') !!}
							{!! Form::select('sexo', [
								'Hombre' => 'Hombre',
								'Mujer' => 'Mujer'
								], $user->sexo, 
								['class' => 'custom-select form-control', 'placeholder' => 'Seleccione ...']) 
							!!}
						</div>
						<div class="form-group">
							{!! Form::label('pais', 'Pa&iacute;s') !!}
							{!! Form::select('pais', $paises, $user->pais_id, [
								'class' => 'custom-select form-control',
								'placeholder' => 'Elija un país ...',
								'data-size' => '10',
								'data-live-search' => 'true']) 
							!!}
						</div>
						<div class="form-group">
							{!! Form::label('celular', 'N&uacute;mero de Celular') !!}
							{!! Form::text('celular', $user->celular, ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::label('email', 'Correo Electr&oacute;nico') !!}
							{!! Form::email('email', $user->email, ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::label('foto', 'Cambiar Foto de Perfil') !!}
							{!! Form::file('foto', ['class' => 'file custom-image']) !!}
						</div>
						{!! Form::button('Guardar Datos', 
							[
								'type' => 'submit', 
								'class' => 'btn btn-primary btn-sm'
							]) 
						!!}

					{!! Form::close() !!}
	        	</div>
	        </div>
		</div>
	</div>
@endsection