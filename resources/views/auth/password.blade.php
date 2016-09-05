@extends('master')

@section('topic')
    @include('topic')
@endsection

@section('maincontent')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="sign-form-pass">
                    <div class="sign-inner-pass">
                        @if ($errors->has())
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Se detectaron los siguientes errores:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(Session::has('status'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>{{ Session::get('status') }}</strong>
                            </div>
                        @endif
                        <h3 class="first-child letra-roja">Reestablecer Contraseña</h3>
                        <hr>
                        <p class="text-muted">
                            Ingrese su dirección de correo electrónico y le enviaremos un enlace para restablecer tu contraseña .
                        </p>
                        {!! Form::open(['url' => 'password/email', 'method' => 'POST']) !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Correo Electrónico']) !!}
                            </div>
                            <br>                            
                            <button type="submit" class="btn btn-primary btn-sm">Enviar Enlace</button>
                            &nbsp;
                            {!! link_to('/ingresar', $title = 'Volver', $attributes = ['class' => 'btn btn-theme-primary btn-sm'], $secure = null) !!}
                            <hr>
                        {!! Form::close() !!}

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