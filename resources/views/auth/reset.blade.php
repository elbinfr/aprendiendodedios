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
                        <h3 class="first-child letra-roja">Reestablecer Contraseña</h3>
                        <hr>

                        {!! Form::open(['url' => 'password/reset', 'method' => 'POST']) !!}
                            {!! Form::hidden('token', $token, null) !!}
                            <div class="form-group">
                              {!! Form::label('email', 'Correo Electr&oacute;nico') !!}
                              {!! Form::email('email', null, ['class' => 'form-control', 'value' => "{{ old('email') }"]) !!}
                            </div>
                            <div class="form-group">
                              {!! Form::label('password', 'Contraseña') !!}
                              {!! Form::password('password', ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                              {!! Form::label('password_confirmation', 'Confirma Contraseña') !!}
                              {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                            </div>
                            <br>                            
                            <button type="submit" class="btn btn-primary btn-sm">Restablecer Contraseña</button>
                            
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