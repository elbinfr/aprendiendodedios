<?php

namespace torrefuerte\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    protected $table = 'lectura';

    protected $fillable = [
    	'cronograma_id', 
    	'libro_id', 
    	'libro_nombre', 
    	'capitulo', 
    	'inicio', 
    	'final', 
    	'estado'
    ];

    protected $dates = [
        'fecha_leida'
    ];

    public function cronograma()
    {
    	return $this->belongsTo('torrefuerte\Models\Cronograma');
    }
}
