{!! Form::open(['url' => '', 'class' => 'form-horizontal well'])!!}
	{!! Form::hidden('plan_slug', $plan->slug, ['id' => 'plan_slug']) !!}
	{!! Form::hidden('fecha_calendar', $fecha_calendar, ['id' => 'fecha_calendar']) !!}
	<div class="form-group">
		{!! Form::label('tipo', 'Tipo', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-10">
			{!! Form::text('tipo', get_tipo_plan($plan), [
				'class' => 'form-control', 
				'disabled'
			]) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('estado', 'Estado', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-10">
			{!! Form::text('estado', str_oracion($plan->estado), [
				'class' => 'form-control', 
				'disabled'
			]) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-10">
			{!! Form::text('nombre', $plan->nombre, [
				'class' => 'form-control', 
				'disabled'
			]) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('dias', 'Dias de Lectura', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-10">
			{!! Form::text('dias', get_dias_lectura($plan->dias_lectura), 
				['class' => 'form-control',
				'disabled'
			]) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('duracion', 'Duraci&oacute;n', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-5">
			{!! Form::text('inicio', $plan->fecha_inicio->format('d/m/Y'), [
				'class' => 'form-control', 
				'placeholder' => 'Inicio',
				'disabled'
			]) !!}
		</div>
		<div class="col-sm-5">
			{!! Form::text('fin', $plan->fecha_final->format('d/m/Y'), [
				'class' => 'form-control',
				'placeholder' => 'Fin',
				'disabled'
			]) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('libros', 'Libros', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-10">
			{!! Form::textarea('libros', $cadena_libros, [
				'class' => 'form-control texarea-comentario',
				'rows'	=>	'3',
				'disabled'
			]) !!}
		</div>
	</div>
	<hr>	
	<div class="form-group">
		{!! Form::label('', 'Colores', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-10">
			@if($plan->estado == 'pendiente')
				{!! Form::button('Lecturas de Hoy', ['class' => 'btn btn-success btn-sm']) !!}
				{!! Form::button('Lecturas Pendientes', ['class' => 'btn btn-warning btn-sm']) !!}
				{!! Form::button('Lecturas Atrasadas', ['class' => 'btn btn-danger btn-sm']) !!}
				{!! Form::button('Lecturas Finalizadas', ['class' => 'btn btn-info btn-sm']) !!}
			@else
				{!! Form::button('Finalizadas correctas', ['class' => 'btn btn-success btn-sm']) !!}
				{!! Form::button('Finalizadas con atraso', ['class' => 'btn btn-danger btn-sm']) !!}
				{!! Form::button('Finalizadas con adelanto', ['class' => 'btn btn-info btn-sm']) !!}
			@endif
		</div>
	</div>
{!! Form::close() !!}