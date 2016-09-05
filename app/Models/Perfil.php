<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    //
    protected $table = 'perfil';

    protected $fillable = ['nombre', 'descripcion'];

}
