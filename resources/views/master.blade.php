
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ URL::asset('assets/img/logo-ico.ico') }}">

    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="cache-control" content="no-store" />
    <meta http-equiv="cache-control" content="must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="Keywords" content="aprendiendodedios.website, aprendiendo de dios, Aprendiendo de Dios, aprendiendodedios, Dios, biblia,Jesus, palabra de Dios, palabra de dios, Cristo" />

    <title>Aprendiendo de Dios</title>

    <!-- CSS Global -->
    {!! Html::style('assets/css/style.css') !!}

    <!-- CSS Plugins -->
    {!! Html::style('assets/css/font-awesome.min.css') !!}
    {!! Html::style('assets/css/animate.css') !!}

    <!-- plugin para inputfile -->
    {!! Html::style('assets/css/fileinput.css') !!}

    <!-- plugin para datepicker -->
    {!! Html::style('assets/css/bootstrap-datepicker3.css') !!}

    <!-- plugin para combobox -->
    {!! Html::style('assets/css/bootstrap-select.css') !!}

    <!-- plugin para cuadros de dialogo -->
    {!! Html::style('assets/css/alertify.css') !!}
    {!! Html::style('assets/css/alertify-theme/bootstrap.css') !!}

    <!-- plugin para alerts -->
    {!! Html::style('assets/css/sweetalert.css') !!}

    <!--plugin para multiselect -->
    {!! Html::style('assets/css/multi-select.css') !!}

    <!-- plugin bootstrap-calendar -->
    {!! Html::style('assets/css/bootstrap-calendar/calendar.css') !!}

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'>
    
    <!-- Metas para compartir en facebook -->
    @yield('meta')

</head>

<body>

<!-- Navigation -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ URL::asset('assets/img/logo-proyecto.png') }}" >
            </a>
        </div>
        <!-- MENU ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
        <div class="collapse navbar-collapse">
            @include('menu')
        </div><!--/.nav-collapse -->
        <!-- /MENU +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
    </div>
</div> <!-- / .navigation -->

<!-- Wrapper -->
<div class="wrapper">
    <!-- SLIDE +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
    <!-- Home Slider -->
    @yield('slider')
    <!-- /SLIDE +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->

    <!-- Services -->
    @yield('services')

    <!-- Topic Header -->
    @yield('topic')

    <!-- CONTENIDO PRINCIPAL +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
    @yield('maincontent')

</div> <!-- / .wrapper -->

<!-- Footer -->
@include('footer')

<!-- JavaScript
================================================== -->

<!-- JS Global -->
{!! Html::script('assets/js/jquery-1.11.3.js') !!}
{!! Html::script('assets/js/bootstrap.min.js') !!}

<!-- JS Plugins -->
{!! Html::script('assets/js/scrolltopcontrol.js') !!}

<!-- JS Custom -->
{!! Html::script('assets/js/custom.js') !!}
{!! Html::script('assets/js/index.js') !!}

<!-- script para fileinput -->
{!! Html::script('assets/js/fileinput.js') !!}

<!-- script para datepicker -->
{!! Html::script('assets/js/bootstrap-datepicker.js') !!}
{!! Html::script('assets/js/bootstrap-datepicker.es.min.js') !!}

<!-- script para combobox -->
{!! Html::script('assets/js/bootstrap-select.js') !!}

<!-- script para cuadros de dialogo -->
{!! Html::script('assets/js/alertify.js') !!}

<!-- script para alerts -->
{!! Html::script('assets/js/sweetalert.js') !!}

<!-- script para autocomplete (input) -->
{!! Html::script('assets/js/bootstrap3-typeahead.js') !!}

<!-- script para multiselect -->
{!! Html::script('assets/js/jquery.multi-select.js') !!}

<!-- script para tinymce -->
{!! Html::script('assets/plugins/tinymce/tinymce.min.js') !!}

<!-- Mis Scripts -->
{!! Html::script('assets/js/funciones.js') !!}
{!! Html::script('assets/js/scripts.js') !!}
{!! Html::script('assets/js/operaciones.js') !!}

@yield('script')

</body>
</html>
