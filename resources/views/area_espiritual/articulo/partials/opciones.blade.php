@if(is_login())
    <div class="panel panel-default">
        <div class="panel-heading">
            Opciones
        </div>
        <div class="fondo-rojo panel-body">
            {!! Form::open() !!}
                {!! link_to('area-espiritual/crear-articulo', 'Crear Art&iacute;culo', 
                    [
                        'class' => 'btn btn-primary btn-sm btn-block'
                    ]
                ) !!}
                {!! link_to('area-espiritual/mis-articulos', 'Mis Art&iacute;culos', 
                    [
                        'class' => 'btn btn-primary btn-sm btn-block'
                    ]
                ) !!}
                {!! link_to('area-espiritual/todos-los-articulos', 'Listar Todos', 
                    [
                        'class' => 'btn btn-primary btn-sm btn-block'
                    ]
                ) !!}                           
            {!! Form::close() !!}
        </div>
    </div>
@endif
<div class="panel panel-default">
    <div class="panel-heading">
        Lo M&aacute;s Leido
    </div>
    <div class="fondo-rojo panel-body">
        <div class="list-group">
            @foreach(mas_leidos() as $item)
                <a href="{{ url('area-espiritual/leer/'.$item->slug) }}" class="list-group-item">
                    <h6 class="list-group-item-heading ">
                        {{ $item->titulo }}
                    </h6>
                </a>
            @endforeach
        </div>
    </div>
</div>
