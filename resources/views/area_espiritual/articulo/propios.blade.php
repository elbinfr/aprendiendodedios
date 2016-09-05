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
				@foreach($articulos as $articulo)
					<div class="blog">
						{!! dibujar_foto_comentario($articulo->user) !!}
						<div class="blog-desc">
							<h3>
							  <a href="blog-post_sidebar-left.html">
							  	{{ $articulo->titulo }}
							  </a>
							</h3>
							<hr>
							<p class="text-muted">
								{{ 'Por ' .
									$articulo->user->nombres . 
									' ' . 
									$articulo->user->apellidos .
									', el ' .
									(new Carbon\Carbon($articulo->created_at))->format('d/m/Y')
								}}
							</p>
							<div class="contenido-articulo">
								{!! descripcion_corta($articulo) !!}
							</div>							
							<a class="btn btn-sm btn-theme-primary" href="{{ url('area-espiritual/leer/'.$articulo->slug) }}">
								Leer M&aacute;s...
							</a>
							<a class="btn btn-sm btn-success" 
								href="{{ url('area-espiritual/editar-articulo/'.$articulo->slug) }}"
								data-nombre="{{ $articulo->titulo }}" >
								Editar
							</a>
							<a class="btn btn-sm btn-warning btn-elimimar-articulo" 
								href="{{ url('area-espiritual/eliminar/'.$articulo->slug) }}"
								data-nombre="{{ $articulo->titulo }}" >
								Eliminar
							</a>
						</div>
					</div>
					<br>
				@endforeach	
				<div class="pull-right">
				    {!! $articulos->render() !!}
				</div>	
			</div>
		</div>
	</div>
@endsection