@extends('master')

@section('topic')
    @include('topic')
@endsection

@section('maincontent')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="">
                    <div class="sign-inner">
                        <h3 class="first-child letra-roja letra-centrada">Inicie Sesi&oacute;n en su Cuenta</h3>
                        <hr class="separador-login-top">
                        {!! Form::open(['route' => 'ingresar', 'method' => 'POST']) !!}
                            {!! Form::label('email', 'Ingresar Correo', ['class' => 'sr-only']) !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Correo Electrónico']) !!}
                            </div>
                            <br>
                            {!! Form::label('password', 'Ingresar Contrase&ntilde;a', ['class' => 'sr-only']) !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Contraseña']) !!}
                            </div>
                            <br>                            
                            <button type="submit" class="btn btn-sm btn-primary btn-block">
                                INGRESAR
                            </button>
                            <hr class="separador-login-bottom">
                        {!! Form::close() !!}

                        <!-- Sign up link -->
                        <p><a href="{{ route('registrarse') }}">Crear Cuenta.</a></p>

                        <!-- Lost password form -->
                        <p>
                            {!! link_to('password/email', $title = 'Olvidaste tu contraseña?', $attributes = null, $secure = null) !!}
                        </p>

                    </div>
                </div>
            </div>
        </div> <!-- / .row -->
    </div> <!-- / .container -->
@endsection

@section('script')

<script>
    $(document).ready(function(){
        @if(Session::has('mensaje-exito'))
            swal("Registrado Correctamente", "{{ Session::get('mensaje-exito') }}", "success");
        @endif

        @if(Session::has('mensaje-error'))
            swal("", "{{ Session::get('mensaje-error') }}", "error");
        @endif

    });
</script>

@endsection