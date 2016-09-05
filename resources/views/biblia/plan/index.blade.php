@extends('master')

@section('topic')
	@include('topic')
@endsection

@section('maincontent')
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="headline first-child letra-roja"><span>Planes de Lectura B&iacute;blica</span></h3>
				<div class="row">
					<div class="col-sm-8 justificado">
						<p>
							La Biblia esta conformada por diferentes libros, por lo que podr&iacute;amos decir que es como una biblioteca. En una biblioteca el lector va al primer estante, toma el primer libro y empieza a leerlo, al finalizar toma el siguiente libro y asi hasta terminar con el &uacute;ltimo libro. 
						</p>
						<p>
							Estimado lector, para leer la biblia puede hacerlo de la misma manera, pero es recomendable establecer un plan de lecturas diarias para que cada vez podamos conocer m&aacute;s a Dios y la forma como El quiere que vivamos.
						</p>
						<p>
							Te mostramos los planes que tenemos y adem&aacute;s tienes la opci&oacute;n de crear tu propio plan de lectura.
						</p>				
					</div>
					<div class="col-sm-4 well justificado fondo-rojo">
						<p>
							Toda la Escritura es inspirada por Dios, y útil para enseñar, para redargüir, para corregir, para instruir en justicia, a fin de que el hombre de Dios sea perfecto, enteramente preparado para toda buena obra. <strong>(2 Timoteo 3:16-17)</strong>
						</p>
					</div>
				</div>
			</div>			
		</div>
		<div class="row">
			<div class="col-sm-8">
				<div class="list-group">
					<a href="#" class="list-group-item">
						<h4 class="list-group-item-heading letra-azul">
							Plan Cronol&oacute;gico
						</h4>
						<p class="list-group-item-text">
							Lea la Biblia en el orden cronológico en el que se produjeron sus historias y eventos.
						</p>
					</a>
					<a href="#" class="list-group-item">
						<h4 class="list-group-item-heading letra-azul">
							Plan de Inicio a Fin
						</h4>
						<p class="list-group-item-text">
							Lea la Biblia de principio a fin siguiendo el orden de los libros, desde el Génesis hasta el Apocalipsis.
						</p>
					</a>
					<a href="#" class="list-group-item">
						<h4 class="list-group-item-heading letra-azul">
							Plan Variado
						</h4>
						<p class="list-group-item-text">
							Cada día lea un tipo de literatura diferente: Ley, Historia, Poesía, Profetas, Evangelio, Ep&iacute;stola, Profecía.
						</p>
					</a>
					<a href="#" class="list-group-item">
						<h4 class="list-group-item-heading letra-azul">
							Plan Propio
						</h4>
						<p class="list-group-item-text">
							Elijes los libros que deseas leer, la duraci&oacute;n y los d&iacute;as de lectura.
						</p>
					</a>
				</div>
				@if (is_login())
	              	<a href="{{ url('biblia/mis-planes-de-lectura') }}" class="btn btn-primary btn-sm">
	              		Ver Planes de Lectura
	              	</a>
	            @else
	              	<div class="alert alert-info" role="alert">
	              		<b>Para ver los planes debes iniciar sesi&oacute;n !!!</b>
	              	</div>
	            @endif
			</div>
		</div>
		
	</div>
@endsection