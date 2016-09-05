<div class="panel panel-default">
	<div class="panel-heading">
		Opciones
	</div>
	<div class="fondo-rojo panel-body">
		{!! Form::open() !!}
            {!! link_to('biblia/plan-lectura-propio/nuevo', 'Crear Plan Propio', 
                [
                    'class' => 'btn btn-primary btn-sm btn-block'
                ]
            ) !!}
            {!! link_to('biblia/plan-lectura-sugerido/nuevo', 'Crear Plan Sugerido', 
                [
                    'class' => 'btn btn-primary btn-sm btn-block'
                ]
            ) !!}
            {!! link_to('biblia/mis-planes-de-lectura', 'Ver Planes', 
                [
                    'class' => 'btn btn-primary btn-sm btn-block'
                ]
            ) !!}                           
        {!! Form::close() !!}					
		
	</div>					
</div>