{!! Form::open(['url' => 'biblia/plan-lectura-sugerido/guardar', 'class' => 'form-horizontal']) !!}
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-8">
			{!! Form::text('nombre', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('tipo', 'Tipo', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-8">
			<select name="tipo" class="form-control">
				<option value="">Seleccione un tipo de lectura</option>
				<option value="cronologico">Cronol&oacute;gico</option>
				<option value="todo">De Inicio a Fin</option>
				<option value="variado">Variado</option>
			</select>
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
		{!! Form::label('inicio', 'Inicio', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-8">
			{!! Form::text('inicio', null, ['class' => 'form-control fecha', 
				'placeholder' => 'Fecha de Inicio']) !!}
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