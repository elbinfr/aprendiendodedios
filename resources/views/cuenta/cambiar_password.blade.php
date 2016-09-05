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
	        		<h3 class="headline first-child letra-roja"><span>Modificar Contrase&ntilde;a</span></h3>
	        		@include('partials.rpta_operacion')
					{!! Form::open(['url' => 'cuenta/cambiar-password', 'method' => 'POST', 'files' => true]) !!}
						<div class="form-group">
			            	{!! Form::label('password', 'Contraseña Nueva') !!}
			            	{!! Form::password('password', ['class' => 'form-control']) !!}
			            </div>
			            <div class="form-group">
			            	{!! Form::label('password_confirmation', 'Repite Nueva Contraseña') !!}
			            	{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
			            </div>
						{!! Form::button('Actualizar Contrase&ntilde;a', 
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