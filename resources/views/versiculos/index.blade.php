<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	<!-- CSS Plugins -->
    {!! Html::style('assets/css/font-awesome.min.css') !!}

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Búsqueda
                    </div>
                    <div class="fondo-rojo panel-body">
                        {!! Form::model(Request::all(), ['url' => 'area-espiritual/buscar-versiculo', 'method' => 'POST']) !!}
                            <div class="form-group">
                                {!! Form::label('libro', 'Libro') !!}
                                {!! Form::select('libro', $libros, null, [
                                    'class' => 'custom-select form-control',
                                    'data-size' => '10',
                                    'data-live-search' => 'true']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('capitulo', 'Cap&iacute;tulo') !!}
                                {!! Form::text('capitulo', null, ['class' => 'form-control' ,
                                    'maxlength' => '3',
                                    'size'      => '3']) !!}
                            </div>
                            {{ Formcustom::buttonImg(' Buscar', 'assets/img/buscar-negro.png', [
                            'type'  => 'submit',
                            'class' => 'btn btn-primary btn-block btn-sm',
                            'id'    => 'btn-buscar-pasaje'
                            ]) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <!-- Resultado de la búsqueda -->
            <div class="col-sm-9" id="resultado-busqueda">
                @include('partials.rpta_operacion')
                @if(isset($lista))
                    <h2 class="headline first-child" id="typography">
                    	<span class='letra-roja'>{{ $titulo_buscado }}</span>
                    </h2>
                    @foreach($lista as $objeto)
                        <div class="blog">
                            <div class="blog-desc">
                                @if(!is_null($objeto->titulo))
                                    <p class="versiculo-titulo fondo-rojo">
                                        {{ $objeto->titulo }}
                                    </p>
                                @endif
                                <p class="justificado">
                                    <strong class="numero-cita">                                    	
                                    	{{ $objeto->numero_versiculo . '. ' }}
                                    </strong>
                                    {!! $objeto->texto !!}
                                </p>
                                <hr>
                            </div>
                        </div>
                    @endforeach
                    <div id="prueba">
                    	Hola es una prueba.
                    </div>

                @endif
            </div>
		</div>		
	</div>
	
</body>
</html>