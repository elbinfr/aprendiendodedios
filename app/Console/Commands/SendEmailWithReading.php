<?php

namespace torrefuerte\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Mail;
use torrefuerte\Models\Cronograma;
use torrefuerte\Models\Lectura;
use torrefuerte\Models\Plan;
use torrefuerte\Models\User;

class SendEmailWithReading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendreadings';//enviar lecturas

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email with reading plan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->comment(PHP_EOL.$this->sendReadingPlan().PHP_EOL);
    }

    /**
     *  envia correo con las lecturas del dia
     *
     * @return string
     */
    public function sendReadingPlan()
    {
        $fecha_hoy = Carbon::today();

        $planes = Plan::whereEstado('pendiente')->get();

        $contador = 0;
        $url = url('ingresar');

        foreach($planes as $plan)
        {
            $user = $plan->user;
            //cronogramas pendientes hoy
            $cronogramas = Cronograma::whereEstado('pendiente')
                                        ->where('plan_id', $plan->id)
                                        ->where('fecha', $fecha_hoy)
                                        ->first();

            if(count($cronogramas) > 0)
            {
                $lecturas = $cronogramas->lecturas;
                //Envio de email.
                Mail::send('emails.lecturas_hoy', compact('user', 'lecturas', 'url'), function ($m) use ($user) 
                {
                    $m->from(env('CONTACT_MAIL'), env('CONTACT_NAME'));
                    $m->to($user->email, $user->nombres)->subject('Lectura biblica para hoy');
                });

                $contador++;
            }
            
        }

        return $contador.' mails were sent.';
    }
}
