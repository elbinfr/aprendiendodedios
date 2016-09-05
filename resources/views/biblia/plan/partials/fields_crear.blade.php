{!! Form::open(['url' => 'biblia/plan-lectura-propio/guardar', 'class' => 'form-horizontal']) !!}
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-10">
			{!! Form::text('nombre', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('libros', 'Libros', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-10">
			{!! Form::select('libros[]', $libros, null, 
				['class' => 'multi-select form-control',
					'multiple' ,
					'title' => 'Elije los libros a leer ...',
					'data-size' => '10']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('dias', 'Dias de Lectura', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-2">
			<div class="checkbox">
				<label>
					{!! Form::checkbox('dias[]', '1') !!} Lunes
				</label>
			</div>
			<div class="checkbox">
				<label>
					{!! Form::checkbox('dias[]', '2') !!} Martes
				</label>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<label>
					{!! Form::checkbox('dias[]', '3') !!} Mi&eacute;rcoles
				</label>
			</div>
			<div class="checkbox">
				<label>
					{!! Form::checkbox('dias[]', '4') !!} Jueves
				</label>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<label>
					{!! Form::checkbox('dias[]', '5') !!} Viernes
				</label>
			</div>
			<div class="checkbox">
				<label>
					{!! Form::checkbox('dias[]', '6') !!} S&aacute;bado
				</label>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<label>
					{!! Form::checkbox('dias[]', '0') !!} Domingo
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('duracion', 'Duraci&oacute;n', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-5">
			{!! Form::text('inicio', null, ['class' => 'form-control fecha', 
				'placeholder' => 'Inicio']) !!}
		</div>
		<div class="col-sm-5">
			{!! Form::text('fin', null, ['class' => 'form-control fecha',
				'placeholder' => 'Fin']) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			{!! Form::button('Guardar Plan', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'id' => 'btn-guardar']) !!}
			&nbsp;
			{!! link_to('biblia/mis-planes-de-lectura', $title = 'Cancelar', $attributes = ['class' => 'btn btn-theme-primary btn-sm'], $secure = null) !!}
		</div>
	</div>				
{!! Form::close() !!}