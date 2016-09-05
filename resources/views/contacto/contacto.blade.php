@extends('master')

@section('topic')
    @include('topic')
@endsection

@section('maincontent')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="headline first-child letra-roja"><span>Cont&aacute;ctanos</span></h3>
                <p>
                    Estamos siempre a tu disposici&oacute;n para aclarar cualquier duda y facilitar la informaci&oacute;n que necesites, solo completa el formulario.
                </p>
                @include('partials.rpta_operacion')
                {!! Form::open(['url' => 'enviarMensaje', 'method' => 'POST']) !!}
                    <div class="form-group">
                        {!! Form::label('email', 'Tu email') !!}
                        {!! Form::email('email', $user->email, ['class' => 'form-control', 'placeholder'=>'Email']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('nombre', 'Tu nombre') !!}
                        {!! Form::text('nombre', $user->nombres, ['class' => 'form-control', 'placeholder' => 'Nombre']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('asunto', 'Asunto') !!}
                        {!! Form::text('asunto', null, ['class' => 'form-control', 'placeholder' => 'Asunto']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('contenido', 'Tu mensaje') !!}
                        {!! Form::textarea('contenido', null, ['class' => 'form-control texarea-comentario', 'rows' => '7', 'placeholder' => 'Mensaje']) !!}
                    </div>
                    <div class="form-group">
                        {!! Captcha::img(); !!}
                        <br/>
                        {!! Form::label('captcha', 'Ingrese el código de la imagen') !!}
                        {!! Form::text('captcha', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::button('Enviar Mensaje', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
