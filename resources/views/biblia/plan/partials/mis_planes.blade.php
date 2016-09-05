<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
			<tr class="tabla-cabecera">
				<th>NOMBRE</th>
				<th>INICIO</th>
				<th>FIN</th>
				<th>ESTADO</th>
				<th colspan="3">ACCIONES</th>
			</tr>
		</thead>
		<tbody>
			@foreach($planes as $plan)
				<tr>
					<td>{{ $plan->nombre }}</td>
					<td>{{ $plan->fecha_inicio->format('d/m/Y') }}</td>
					<td>{{ $plan->fecha_final->format('d/m/Y') }}</td>
					<td>{!! dibujar_estado($plan->estado) !!}</td>
					<td colspan="3" class="columna-acciones">
						<a href="{{ url('biblia/mis-planes-de-lectura/ver/'.$plan->slug) }}" class="btn btn-primary btn-xs"> 
							Ver Plan
						</a>
						<a href="{{ url('biblia/mis-planes-de-lectura/eliminar/'.$plan->slug) }}" 
							class="btn btn-danger btn-xs btn-elimimar-plan"
							data-nombre="{{ $plan->nombre }}">
							Eliminar
						</a>
						<!--
						<a href="{{ url('biblia/mis-planes-de-lectura/descargar/'.$plan->slug) }}" class="btn btn-success btn-xs" target="_blank">
							Descargar
						</a>
						-->
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>