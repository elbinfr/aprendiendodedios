<div class="coments">
	<div class="col-sm-8">
		@if(Auth::check())
			<div class="cmt well">
				{!! dibujar_foto() !!}
				<div class="cmt-block">					
					<strong>{{ Auth::user()->nombres . ' ' . Auth::user()->apellidos }}</strong>
					<div class="alert alert-info" role="alert">No olvides seleccionar un versículo.</div>
					{!! Form::open(['url' => '', 'id' => 'form-comentario', 'class' => 'cmt-body', 'role' => 'form']) !!}
						{!! Form::hidden('versiculo_id', null, null) !!}
						<div class="form-group">
							{!! Form::textarea('texto', null, ['class' => 'form-control texarea-comentario', 'rows' => '4']) !!}
						</div>
						{!! Form::button('Guardar Comentario', ['type' => 'submit', 'class' => 'btn btn-theme-primary']) !!}
					{!! Form::close() !!}
				</div>
			</div>
		@endif
	</div>	
</div>