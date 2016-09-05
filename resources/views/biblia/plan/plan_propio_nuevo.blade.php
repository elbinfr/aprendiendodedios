@extends('master')

@section('topic')
	@include('topic')
@endsection

@section('maincontent')

<div class="container">
	<div class="row">
		<div class="col-sm-3">
			<!-- OPCIONES -->
			@include('biblia.plan.partials.opciones')
		</div>
		<div class="col-sm-9">
			<h3 class="headline first-child letra-roja"><span>Nuevo Plan de Lectura (Propio)</span></h3>
			@include('partials.rpta_operacion')			

			@if($tiene_planes_activos)
				<div class="alert alert-info" role="alert">
					<h4>Tienes un plan pendiente. Para crear uno nuevo debes finalizar todos tus planes.</h4>
				</div>
			@else
				<div>
					<button class="btn btn-block" id="loader" style="display:none;">
						<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> 
						Procesando Plan de Lectura ...
					</button>
					<br>
				</div>
				<!-- FORMULARIO -->
				@include('biblia.plan.partials.fields_crear')

			@endif
		</div>		
	</div>
</div>

@endsection

@section('script')
<script>
	$(function(){
		$('.multi-select').multiSelect({
			selectableHeader: "<div class='multiselect-header'>Libros Disponibles</div>",
			selectionHeader: "<div class='multiselect-header'>Libros Seleccionados</div>"
		});

		$('#btn-guardar').click(function(){
			$('#loader').show();
		});
	});
</script>
@endsection