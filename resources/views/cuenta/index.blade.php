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
	        	
	        </div>
		</div>
	</div>
@endsection