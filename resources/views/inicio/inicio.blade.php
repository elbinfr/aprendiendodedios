@extends('master')

@section('slider')
    @include('inicio.slider')
@endsection

@section('services')
    @include('inicio.services')
@endsection

@section('maincontent')
    <div class="container">
        <br />
        <div class="row">
            <div class="col-sm-12">
                <h3 class="headline first-child letra-roja"><span>Registrate</span></h3>
                <div class="row">
                    <div class="col-sm-6">                
                        <p class="justificado">
                            Te invitamos a registrarte para que tengas acceso a opciones como:
                            <ul>
                                <li>Comentar vers&iacute;culos.</li>
                                <li>Agregar referencias a los vers&iacute;culos.</li>
                                <li>Crear planes de lectura.</li>
                                <li>Publicar art&iacute;culos.</li>
                            </ul>
                        </p>
                        <br />
                    </div>
                    <div class="col-sm-6">                        
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/PQbsVQPIshk" width="420" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                        </div>
                        <br/>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h3 class="headline first-child letra-roja"><span>Busca Vers&iacute;culos</span></h3>
                <div class="row">
                    <div class="col-sm-6">                
                        <p class="justificado">
                            Puedes buscar vers&iacute;culos por pasaje o ingresando alguna frase. Cuando se muestra el resultado encontrado puedes leer los vers&iacute;culos en otras versiones como NVI (Nueva Versi&oacute;n Internacional), DHH (Dios Habla Hoy) y TLA (Traducci&oacute;n a Lenguaje Actual).
                        </p>
                        <p class="justificado">
                            Te invitamos a comentar los vers&iacute;culos, solo pide a Dios la sabidur&iacute;a y &eacute;l te la dar&aacute; abundantemente y sin reproche.
                        </p>
                        <p>
                            Tambi&eacute;n puedes referenciar los vers&iacute;culos para que otra persona pueda comprender mejor la Palabra de Dios.
                        </p>
                        <br />
                    </div>
                    <div class="col-sm-6">                        
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/ZQfV-Ow3xE0" width="420" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                        </div>
                        <br/>
                    </div>
                </div>
            </div>            
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h3 class="headline first-child letra-roja"><span>Crea tu plan de lectura</span></h3>
                <div class="row">
                    <div class="col-sm-6">                
                        <p class="justificado">
                            La lectura de la Palabra de Dios es muy importante para nuestra vida, nuestro Se&ntilde;or Jesucristo lo comparo con la comida; asi como nuestro cuerpo necesita alimento material, tambi&eacute;n nuestra alma necesita alimentarse para estar fortalecida y ese alimento es la Palabra de Dios.
                        </p>
                        <p class="justificado">
                            Aqui tienes la opci&oacute;n de gestionar tu lectura de la biblia, creando un plan de lectura propio (seleccionando los libros que deseas leer y los dias que tienes disponible) o puedes crear un plan sugerido (un plan ya establecido de todos los libros b&iacute;blicos).
                        </p>
                        <br />
                    </div>
                    <div class="col-sm-6">                        
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/RbIF6c3RkYI" width="420" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                        </div>
                        <br/>
                    </div>
                </div>
            </div>            
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h3 class="headline first-child letra-roja"><span>Publica tus art&iacute;culos</span></h3>
                <div class="row">
                    <div class="col-sm-6">                
                        <p>
                            Publica tus art&iacute;culos, tales como:
                            <ul>
                                <li>Sermones</li>
                                <li>Devocionales</li>
                                <li>Ense&ntilde;anzas</li>
                            </ul>
                            Que sirvan de ayuda para los dem&aacute;s, y as&iacute; todos podamos ser formados por la Palabra de Dios.
                        </p>
                        <br />
                    </div>
                    <div class="col-sm-6">
                        
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/dNAxLJg2_tI" width="420" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                        </div>
                        <br/>
                    </div>
                </div>
            </div>            
        </div>

    </div>
    <!-- / Mensaje de bienvenida-->

    <div class="container-fluid">
        <hr>
    </div>

    <!-- Recent Blog Posts -->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="headline-lg letra-roja">Publicaciones Recientes</h2>
            </div>
        </div> <!-- / .row -->
        <div class="row">            
            @foreach($articulos as $articulo)
                <div class="col-sm-12">
                    <div class="blog">
                        {!! dibujar_foto_comentario($articulo->user) !!}
                        <div class="blog-desc">
                            <h3>
                                <a href="javascript:void(0);">{{ $articulo->titulo }}</a>
                            </h3>
                            <hr>
                            <p class="text-muted">
                                por {{ $articulo->user->nombres.' '.$articulo->user->apellidos }}
                            </p>
                            {!! descripcion_corta($articulo) !!}
                            <a class="btn btn-sm btn-primary" href="{{ url('area-espiritual/leer/'.$articulo->slug) }}">
                                Leer Más...
                            </a>
                        </div>
                    </div>
                    <br>
                </div>
            @endforeach            
        </div> 
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        @if(Session::has('mensaje-exito'))
            swal("{{ Session::get('mensaje-exito') }}", "Ahora puedes iniciar sesion", "success");
        @endif
    });
</script>
@endsection
