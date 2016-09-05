<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libro';

    protected $fillable = ['nombre', 'abreviatura', 'capitulos'];

    public function versiculos()
    {
        return $this->hasMany('torrefuerte\Models\Versiculo');
    }
}
