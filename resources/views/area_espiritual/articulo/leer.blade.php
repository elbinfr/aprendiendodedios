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
							{!! $articulo->contenido !!}
						</div>
						<a class="btn btn-sm btn-primary btn-volver" href="#">
							Volver
						</a>
						<a target="_blank" href="https://twitter.com/share"
							class="twitter btn btn-sm btn-default"
							data-url="{{ url('area-espiritual/leer/'.$articulo->slug) }}"
							data-text = "{{ $articulo->titulo }}"  
							data-hashtags="Biblia,Cristo"
							data-share="twitter">
							<i class="fa fa-twitter text-theme-twitter"></i>
							Twitter
						</a>
						<a target="_blank" href="https://www.facebook.com/sharer.php"
							class="facebook btn btn-sm btn-default"
							data-href="{{ url('area-espiritual/leer/'.$articulo->slug) }}"
							data-title="{{ $articulo->titulo }}" 
							data-share="facebook">
							<i class="fa fa-facebook-square text-theme-facebook"></i>
							Facebook
						</a>
					</div>
					<!-- Comentarios -->
					@include('area_espiritual.articulo.partials.comentarios')
				</div>
				<br>			
			</div>
		</div>
	</div>
@endsection

@section('script')
<script>
	$('.btn-volver').click(function(e) {
		e.preventDefault();
		history.go(-1);
	});

	$('.twitter').click(function(e) {
		e.preventDefault();
		var boton = $(this);
		var href = $(this).attr('href');
		var parametros = {url: boton.data('url'),text : boton.data('text'), hashtags : boton.data('hashtags')};
    	var data = $.param(parametros);
    	var link = href + '?' + data;
    	
		//window.location = link;
		window.open(link,'_blank');
	});

	$('.facebook').click(function(e) {
		e.preventDefault();
		var boton = $(this);
		var href = $(this).attr('href');
		var parametros = {u: boton.data('href'), t: boton.data('title')};
    	var data = $.param(parametros);
    	var link = href + '?' + data;
    	
		window.open(link,'_blank');
	});

	$('.btn-comentar').click(function(e) {
		e.preventDefault();
		var form = $(this).parents('form');
		var action = form.attr('action');
		var data = form.serialize();

		$.post(action, data, function(result) {
			if(result.resultado == true){				
				$('#frm-comentario textarea[name=contenido]').val(null);
	            $('#lista-comentarios').html(result.comentarios);
	            swal('', 'Operación exitosa', 'success');
	        }else{
	            var error = dibujarDivErrores(result.errors);
	            swal({
	                title: "",
	                text: error,
	                type: "error",
	                html: true
	            });
	        }
		});
	});

	$('#lista-comentarios').on("click", ".btn-eliminar-comentario",function(e){
    var boton = $(this);
    var parametros = { _token : boton.data('token'), comentario_id : boton.data('comentario-id')};
    var action = boton.data('action');
    var data = $.param(parametros);

    swal({
        title: "Esta Seguro?",
        text: "Ha seleccionado eliminar el comentario.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    }, function(){
        $.post(action, data, function(result){
            if(result.resultado == true){
                $('#lista-comentarios').html(result.comentarios);
                swal('', 'Comentario eliminado correctamente', 'success');
            }else{
                var error = dibujarDivErrores(result.errors);
                swal({
                    title: "",
                    text: error,
                    type: "error",
                    html: true
                });
            }
        });
    });
});

</script>
@endsection