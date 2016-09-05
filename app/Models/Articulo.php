<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulo';

    protected $fillable = ['user_id', 'titulo', 'contenido', 'slug'];

    /*
    *	RELACIONES
    */
    
    public function user()
    {
    	return $this->belongsTo('torrefuerte\Models\User');
    }

    public function articulo_comentarios()
    {
    	return $this->hasMany('torrefuerte\Models\ArticuloComentario');
    }

    /*
    * METODOS
    */
    public static function listar_anios()
    {
    	$query = "select distinct date_part('year', created_at) as anio from articulo order by anio";

    	$anios = \DB::select($query);

    	return $anios;
    }
}
