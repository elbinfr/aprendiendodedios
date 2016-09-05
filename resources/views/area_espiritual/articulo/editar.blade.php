@extends('master')

@section('topic')
	@include('topic')
@endsection

@section('maincontent')
	<div class="container">
		<div class="row">
			<!-- OPCIONES (Solo cuando esta logueado) -->
			<div class="col-sm-3">
				@include('area_espiritual.articulo.partials.opciones')
			</div>
			<div class="col-sm-9">
				<h3 class="headline first-child letra-roja"><span>Editar Art&iacute;culo</span></h3>
				@include('partials.rpta_operacion')
				{!! Form::open(['url' => 'area-espiritual/actualizar-articulo', 'method' => 'POST']) !!}
					{!! Form::hidden('id', $articulo->id) !!}
					<div class="form-group">
						{!! Form::label('titulo', 'T&iacute;tulo') !!}
						{!! Form::text('titulo', $articulo->titulo, 
							[
								'class' 		=> 'form-control',
								'placeholder'	=>	'Ingrese el titulo ...'
							]
						) !!}
					</div>
					<div class="form-group">
						{!! Form::label('contenido', 'Contenido') !!}
						{!! Form::textarea('contenido', $articulo->contenido, 
							[
								'class'	=> 'form-control textarea-contenido'
							]
						) !!}
					</div>
					<div class="form-group">
						{!! Form::button('Actualizar Art&iacute;culo', 
							[
								'type'	=>	'submit',
								'class'	=> 'btn btn-primary btn-sm'
							]
						) !!}
						{!! link_to('area-espiritual/mis-articulos', 'Cancelar', 
		                    [
		                        'class' => 'btn btn-danger btn-sm'
		                    ]
		                ) !!}
					</div>
					
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection

@section('script')
	<script>
		tinymce.init({
		    selector: '.textarea-contenido',
		    language: 'es',
		    plugins: [
			    'advlist autolink lists link image charmap print preview anchor',
			    'searchreplace visualblocks code fullscreen',
			    'insertdatetime media contextmenu paste code'
			],
			toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
			
		});
	</script>

@endsection