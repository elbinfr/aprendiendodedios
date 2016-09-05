<div class="panel-heading">
	Opciones
</div>
<div class="panel-body fondo-rojo">
    {!! Form::open() !!}
        {!! link_to('cuenta/notificaciones', 'Notificaciones', 
            [
                'class' => 'btn btn-primary btn-sm btn-block'
            ]
        ) !!}
        {!! link_to('cuenta/modificar-datos', 'Modificar Datos', 
            [
                'class' => 'btn btn-primary btn-sm btn-block'
            ]
        ) !!}
        {!! link_to('cuenta/cambiar-password', 'Cambiar Contrase&ntilde;a', 
            [
                'class' => 'btn btn-primary btn-sm btn-block'
            ]
        ) !!}
        {!! link_to('salir', 'Cerrar Sesi&oacute;n', 
            [
                'class' => 'btn btn-primary btn-sm btn-block'
            ]
        ) !!}                           
    {!! Form::close() !!}
</div>