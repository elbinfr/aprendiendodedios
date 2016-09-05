<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloComentario extends Model
{
    protected $table = 'articulo_comentario';

    protected $fillable = ['articulo_id', 'user_id', 'contenido'];

    /*
    * RELACIONES
    */

    public function articulo()
    {
    	return $this->belongTo('torrefuerte\Models\Articulo');
    }

    public function user()
    {
    	return $this->belongsTo('torrefuerte\Models\User');
    }
}
