<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    //
    protected $table = 'referencia';

    protected $fillable = ['user_id', 'versiculo_id', 'cita'];

    public function user()
    {
        return $this->belongsTo('torrefuerte\Models\User');
    }

    public function versiculo()
    {
        return $this->belongsTo('torrefuerte\Models\Versiculo');
    }

    public function referenciacruzada()
    {
        return $this->belongsTo('torrefuerte\Models\Versiculo', 'cita');
    }
}
