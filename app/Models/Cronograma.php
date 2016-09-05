<?php

namespace torrefuerte\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    protected $table = 'cronograma';

    protected $fillable = [
        'plan_id', 
        'fecha', 
        'estado'
    ];

    protected $dates = [
        'fecha',
        'fecha_termino'
    ];

    public function plan()
    {
    	return $this->belongsTo('torrefuerte\Models\Plan');
    }

    public function lecturas()
    {
    	return $this->hasMany('torrefuerte\Models\Lectura');
    }
}
