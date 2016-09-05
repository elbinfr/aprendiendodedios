@extends('master')

@section('topic')
	@include('topic')
@endsection

@section('maincontent')
	<div class="container">
		<div class="row">
			<!-- OPCIONES (Solo cuando esta loguado) -->
			<div class="col-sm-3">
				@include('area_espiritual.articulo.partials.opciones')
			</div>
			<div class="col-sm-9">
				@include('partials.rpta_operacion')
				<br>
				@include('area_espiritual.articulo.partials.lista')							
			</div>						
		</div>
	</div>
@endsection