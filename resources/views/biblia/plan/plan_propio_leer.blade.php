@extends('master')

@section('topic')
	@include('topic')
@endsection

@section('maincontent')
	<div class="container">
		<div class="row">
			<!-- Opciones -->
			<div class="col-sm-3">
				@include('biblia.plan.partials.opciones')
			</div>

			<div class="col-sm-9">
				@include('partials.rpta_operacion')

				<h3 class="headline first-child" id="typography">
					<span class="letra-roja">
						Mis planes de lectura ( {{
								$lectura->libro_nombre . ' ' 
                    			. $lectura->capitulo . ': ' 
                    			. $lectura->inicio . '-' . $lectura->final 
							}} )
					</span>
				</h3>
				
				@if($lectura->estado == 'pendiente')
					<div class="alert alert-info" role="alert">
						No olvides finalizar la lectura presionando el boton ubicado en la parte inferior de la p&aacute;gina.
					</div>
				@else
					<div class="alert alert-success" role="alert">
						Lectura Finalizada.
					</div>
				@endif
				
				
				@if(isset($lista))
                    @foreach($lista as $objeto)
                        <div class="blog">
                            <div class="blog-desc">
                                @if(!is_null($objeto->titulo))
                                    <p class="versiculo-titulo fondo-rojo">
                                        {{ $objeto->titulo }}
                                    </p>
                                @endif
                                <p class="justificado">
                                    <strong class="numero-cita">{{ 'V-' . $objeto->numero_versiculo . '. ' }}</strong>
                                    {{ $objeto->texto }}
                                    <br>
                                    <button class="btn btn-xs btn-success pull-right" data-toggle="modal"
                                            data-target="#accionesModal"
                                            data-versiculo-id="{{ $objeto->id }}"
                                            data-texto-seleccionado="{{ cita_biblica($objeto) }}" >
                                        Opciones
                                    </button>
                                </p>
                                <hr>
                            </div>
                        </div>
                    @endforeach

                @endif

                @if($lectura->estado == 'pendiente')
                	<div class="blog">
                		<div class="blog-desc">
                			{!! Form::open(['url' => 'biblia/mis-planes-de-lectura/finalizar', 'method'  => 'POST']) !!}
		                		{!! Form::hidden('lectura_slug', $lectura->slug) !!}
		                		{!! Form::button('Finalizar Lectura', [
		                			'class' =>	'btn btn-primary btn-sm',
		                			'type'	=>	'submit'
		                		]) !!}
		                		{!! link_to('biblia/mis-planes-de-lectura/ver/'.$lectura->cronograma->plan->slug, 'Volver', 
		                			['class' => 'btn btn-danger btn-sm']) 
		                		!!}
		                	{!! Form::close() !!}
                		</div>
                	</div>
                @else
                    <div class="blog">
                		<div class="blog-desc">
                			{!! Form::open(['url' => 'biblia/mis-planes-de-lectura/finalizar', 'method'  => 'POST']) !!}
		                		{!! Form::hidden('lectura_slug', $lectura->slug) !!}
		                		{!! link_to('biblia/mis-planes-de-lectura/ver/'.$lectura->cronograma->plan->slug, 'Volver', 
		                			['class' => 'btn btn-danger btn-sm']) 
		                		!!}
		                	{!! Form::close() !!}
                		</div>
                	</div>       	
                @endif

			</div>
		</div>
	</div>
	@include('biblia.partials.modal')
@endsection