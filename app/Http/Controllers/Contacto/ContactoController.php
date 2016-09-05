<?php

namespace torrefuerte\Http\Controllers\Contacto;

use Request;
use Mail;
use Session;

use torrefuerte\Http\Requests;
use torrefuerte\Http\Controllers\Controller;

use torrefuerte\Models\Mensaje;
use torrefuerte\Models\User;

class ContactoController extends Controller
{
    public function index()
    {
        Session::put('menu-item', 'Contacto');
        Session::forget('menu-subitem');

        $user = new User();
        if(is_login())
        {
            $user = current_user();
        }

        return view('contacto.contacto', compact('user'));
    }

    public function enviarMensaje(Request $request)
    {
        //Se captura los datos enviados
        $datos = Request::all();

        //validar los datos ingresados
        $rules =  array(
            'email'     => ['required', 'email'],
            'nombre'    => ['required', 'alpha'],
            'asunto'    => ['required', 'min:5'],
            'contenido' => ['required', 'min:5'],
            'captcha'   => ['required', 'captcha']
        );
        $validator = \Validator::make($datos,$rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withInput(Request::except('captcha'));
        }

        //Enviar correo
        Mail::send('emails.contacto', $datos, function($msj)
        {
            //remitente
            $msj->from(Request::input('email'), Request::input('nombre'));

            //asunto
            $msj->subject(Request::input('asunto'));

            //receptor
            $msj->to(env('CONTACT_MAIL'), env('CONTACT_NAME'));
        });

        //Guardar el mensaje en DB
        $obj_mensaje = new Mensaje(Request::all());
        $obj_mensaje->save();

        Session::flash('msg_success', 'El mensaje fue enviado correctamente');

        return redirect()->route('contacto');

    }
}
