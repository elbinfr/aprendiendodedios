@foreach($articulos as $articulo)
	<div class="blog">
		{!! dibujar_foto_comentario($articulo->user) !!}
		<div class="blog-desc">
			<h3>
			  <a href="{{ url('area-espiritual/leer/'.$articulo->slug) }}">
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
		</div>
	</div>
	<br>
@endforeach
<div class="pull-right">
    {!! $articulos->render() !!}
</div>