@extends('master')

@section('topic')
	@include('topic')
@endsection

@section('maincontent')
	<div class="container">
		<div class="row">
			<!-- Opciones -->
			<div class="col-sm-3">
				@include('biblia.plan.partials.opciones')
			</div>
			<div class="col-sm-9">
				<h3 class="headline first-child" id="typography"><span class="letra-roja">Mis planes de lectura</span></h3>
				
				@include('partials.rpta_operacion')

				@if( count($planes) > 0 )
					@include('biblia.plan.partials.mis_planes')
				@else
					<div class="alert alert-info" role="alert">
						<h4>Aun no tienes un plan de lectura. Empieza por crearte uno.</h4>
					</div>
				@endif

			</div>
		</div>
	</div>
@endsection