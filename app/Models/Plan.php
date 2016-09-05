<?php

namespace torrefuerte\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Plan extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $table = 'plan';

    protected $fillable = [
        'user_id', 
        'nombre', 
        'fecha_inicio', 
        'fecha_final', 
        'dias_lectura', 
        'estado',
        'tipo'
    ];

    protected $dates = [
        'fecha_inicio', 
        'fecha_final',
        'fecha_termino'
    ];

    //de campo se guardara el campo slug (usado para url amigable)
    protected $sluggable = [
        'build_from'    => ['nombre']
    ];

    public function cronogramas()
    {
    	return $this->hasMany('torrefuerte\Models\Cronograma');
    }

    public function user()
    {
    	return $this->belongsTo('torrefuerte\Models\User');
    }
}
