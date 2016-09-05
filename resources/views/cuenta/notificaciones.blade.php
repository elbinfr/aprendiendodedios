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
	        		<h3 class="headline first-child letra-roja"><span>Notificaciones</span></h3>
	        		@include('partials.rpta_operacion')
	        		<div class=" well justificado fondo-verde">
	        			<strong>Recuerda!!! </strong>
						<p>
							{{ $versiculo->texto }}
							<strong>
								{{ ' ('.$versiculo->libro->nombre.' '.$versiculo->numero_capitulo.': '.$versiculo->numero_versiculo.')' }}
							</strong>
						</p>
					</div>       		
	        		@if($atrasadas > 0)
						<div class="alert alert-danger" role="alert">
							<strong>Atenci&oacute;n!!!</strong> Tienes lecturas atrasadas.
							<a href="{{ url('biblia/mis-planes-de-lectura/ver/'.$plan->slug) }}" class="btn btn-sm btn-danger pull-right">
								Ver
							</a>
						</div>
	        		@endif
	        		@if($pendiente_hoy > 0)
						<div class="alert alert-success">
							Para este d&iacute;a tienes lecturas pendientes.
							<a href="{{ url('biblia/mis-planes-de-lectura/ver/'.$plan->slug) }}" class="btn btn-sm btn-success pull-right">
								Ver
							</a>
						</div>
	        		@endif
	        		@if( count($mis_articulos_top) > 0 )
						<div class="alert alert-info">
							<strong>Felicidades !!!</strong> Tus siguientes art&iacute;culos estan dentro de los m&aacute;s leidos.
							<ul>
								@foreach( $mis_articulos_top as $item )
									<li>{{ $item->titulo }}</li>
								@endforeach
							</ul>
						</div>
	        		@endif      		
	        	</div>
	        </div>
		</div>
	</div>
@endsection