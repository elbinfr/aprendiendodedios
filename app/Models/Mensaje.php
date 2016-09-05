<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensaje';

    protected $fillable = ['email', 'nombre', 'asunto', 'contenido'];
}
