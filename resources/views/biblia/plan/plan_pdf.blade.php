<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	{!! Html::style('assets/css/plan_lectura_pdf.css') !!}

</head>
<body>
	<header>
		<div class="borde-inferior">
			<div class="logo">
				<img src="{{ URL::asset('assets/img/logo-proyecto.png') }}" >
			</div>	
		</div>
	</header>

	<section>
		<table class="resumido">
			<tr>
				<td class="titulo-celda">Tipo de Plan</td>
				<td>{{ get_tipo_plan($plan) }}</td>
			</tr>
			<tr>
				<td class="titulo-celda">Nombre de Plan</td>
				<td>{{ $plan->nombre }}</td>
			</tr>
			<tr>
				<td class="titulo-celda">D&iacute;as de Lectura</td>
				<td>{{ get_dias_lectura($plan->dias_lectura) }}</td>
			</tr>
			<tr>
				<td class="titulo-celda">Duraci&oacute;n</td>
				<td>{{ 'de 	' . $plan->fecha_inicio->format('d/m/Y') . ' al ' . $plan->fecha_final->format('d/m/Y') }}</td>
			</tr>
			<tr>
				<td class="titulo-celda">Libros</td>
				<td>{{ $cadena_libros }}</td>
			</tr>
		</table>
	</section>

	<section>
		<table id="detalle">
			<thead>
				<tr class="cabecera">
					<th class="numero">#</th>
					<th>Fecha</th>
					<th>Lectura</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($plan->cronogramas as $cronograma)
					{{--*/ $fila = 1 /*--}}
										
					@foreach ($cronograma->lecturas as $lectura)
						@if ($fila == 1)
							<tr>
								<td>{{ $contador++ }}</td>
								<td rowspan="{{ $cronograma->lecturas->count() }}" class="centrado-vertical">
									<p>
										{{ $cronograma->fecha->format('d/m/Y') }}
									</p>											
								</td>
								<td>{{ get_title_lectura($lectura) }}</td>
								<td>{!! str_oracion($lectura->estado) !!}</td>
							</tr>
							{{--*/ $fila++ /*--}}
						@else
							<tr>
								<td>{{ $contador++ }}</td>										
								<td>{{ get_title_lectura($lectura) }}</td>
								<td>{!! str_oracion($lectura->estado) !!}</td>
							</tr>
						@endif
						
					@endforeach				
					
				@endforeach
			</tbody>
			
		</table>
	</section>

</body>
</html>