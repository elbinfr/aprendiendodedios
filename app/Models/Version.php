<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'version';

    protected $fillable = ['abreviatura', 'nombre'];

    public function versiculoVersiones()
    {
        return $this->hasMany('torrefuerte\Models\VersiculoVersion');
    }
}
