<?php

namespace torrefuerte\Http\Controllers\Auth;

use torrefuerte\Models\User;
use Validator;
use torrefuerte\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use torrefuerte\Models\Pais;
use Mail;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombres' => 'required|min:3|max:100',
            'apellidos' => 'required|min:3|max:100',
            'sexo' => 'required|in:Hombre,Mujer',
            'pais' => 'required|integer|exists:pais,id',
            'celular' => 'numeric|min:9',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'foto' => 'image'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = new User([
            'nombres'   => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'sexo'      => $data['sexo'],
            'pais_id'   => $data['pais'],
            'celular'   => $data['celular'],            
            'email'     => $data['email'],
            'password'  => bcrypt($data['password'])
        ]);
        if(isset($data['foto'])){
            $user->foto = $data['foto'];
        }

        $user->perfil_id = 1;// Perfil: Usuario
        $user->registration_token = str_random(40);
        $user->save();

        $url = route('confirmation', ['token' => $user->registration_token]);

        //Envio de email para confirmar cuenta.
        Mail::send('emails.registro', compact('user', 'url'), function ($m) use ($user) {
            $m->from(env('CONTACT_MAIL'), env('CONTACT_NAME'));
            $m->to($user->email, $user->nombres)->subject('Activa tu cuenta!');
        });

        return $user;

    }

    /*
     * Mostrar formulario de Logueo
     */
    public function getLogin()
    {
        \Session::put('menu-item', 'Ingresar');
        \Session::put('menu-subitem', 'Iniciar Sesión');

        return view('auth.login');
    }

    /*
    * Login de Usuario
    */
    public function postLogin(Request $request)
    {
        if(\Auth::attempt(['email' => $request['email'], 'password' => $request['password'] ])){
            return redirect('cuenta/notificaciones');
        }
        \Session::flash('mensaje-error', 'Los datos son incorrectos');
        return redirect()->route('ingresar');
    }

    /*
    * Logout de Usuario
    */
    public function getLogout()
    {
        \Auth::logout();
        return redirect()->route('inicio');
    }

    /*
     * Formulario de registro
     */
    public function getRegister()
    {
        \Session::put('menu-subitem', 'Registrarse');
        /*
        *El metodo lists genera un array asociativo: pasises[ id => nombre ]
        */
        $paises = Pais::lists('nombre', 'id');

        return view('auth.register', compact('paises'));
    }

    public function postRegister(Request $request)
    {
        //validamos los datos del formulario
        $datos = $request->all();
        $validator = $this->validator($datos);
        if($validator->fails()){
            $this->throwValidationException($request, $validator);
        }

        $email = $datos['email'];
        //archivos
        if($request->hasFile('foto')){
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $size = $file->getClientSize();
            $fecha = Carbon::now()->toDateString();
            $segundo = Carbon::now()->second;
            $nombre = $email.'_'.$fecha.'_'.$segundo.'.'.$extension;
            $datos['foto'] = $nombre;
            
        }

        //Se crea el usuario
        $user = $this->create($datos);

        //Subimos el archivo de la foto.
        if($request->hasFile('foto')){
            $foto = $request->file('foto');
            \Storage::disk('local')->put($nombre, \File::get($foto));
        }
        
        return redirect()->route('ingresar')
            ->with('mensaje-exito', 'Por favor revisa tu email: ' . $user->email . ' para activar tu cuenta.');
    }

    protected function getConfirmation($token)
    {
        $user = User::where('registration_token', $token)->firstOrFail();
        $user->registration_token = null;
        $user->estado = 'Activo';
        $user->save();
        return redirect()->route('inicio')
            ->with('mensaje-exito', 'Tu email fue confirmado');
    }

}
