<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class VersiculoVersion extends Model
{
    protected $table = 'versiculo_version';

    protected $fillable = ['versiculo_id', 'version_id', 'texto'];

    public function versiculo()
    {
        return $this->belongsTo('torrefuerte\Models\Versiculo');
    }

    public function version()
    {
        return $this->belongsTo('torrefuerte\Models\Version');
    }


}
