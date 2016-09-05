<div class="clearfix"></div>
<div class="comments">
	<!-- formulario para comentar -->
	@if(is_login())
		<div class="cmt well">
            {!! dibujar_foto_comentario(current_user()) !!}
            <div class="cmt-block">
	            <strong>{{ current_user()->nombres . ' ' . current_user()->apellidos }}</strong>
	            {!! Form::open(['url' => 'area-espiritual/comentar-articulo', 'method' => 'POST', 'class' => 'cmt-body', 'role' => 'form', 'id' => 'frm-comentario']) !!}
	            	<div class="form-group">
	            		{!! Form::textarea('contenido', null, 
	            			[
	            				'class'			=> 'form-control articulo-comentario',
	            				'rows'			=>	'3',
	            				'placeholder'	=>	'Ingrese su comentario'
	            			]
	            		) !!}
	            	</div>
	            	{!! Form::button('Comentar', 
	            		[
	            			'type'	=>	'button',
	            			'class' =>	'btn btn-primary btn-sm btn-comentar'
	            		]) 
	            	!!}
	            {!! Form::close() !!}
            </div>
        </div>
	@endif
	<!-- Lista de Comentarios -->
	<div id="lista-comentarios">
		{!! dibujar_articulo_comentarios($lista_comentarios) !!}
	</div>
</div>