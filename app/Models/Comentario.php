<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentario';

    protected $fillable = ['user_id', 'versiculo_id', 'texto'];

    public function user()
    {
        return $this->belongsTo('torrefuerte\Models\User');
    }

    public function versiculo()
    {
        return $this->belongsTo('torrefuerte\Models\Versiculo');
    }

}
