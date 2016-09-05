<?php

namespace torrefuerte\Models;

use Illuminate\Database\Eloquent\Model;

class Versiculo extends Model
{
    protected $table = 'versiculo';

    protected $fillable = ['numero_capitulo', 'numero_versiculo', 'titulo', 'texto'];

    public function libro()
    {
        return $this->belongsTo('torrefuerte\Models\Libro');
    }

    public function versiculoVersiones()
    {
        return $this->hasMany('torrefuerte\Models\VersiculoVersion');
    }

    public function comentarios()
    {
        return $this->hasMany('torrefuerte\Models\Comentario');
    }

    public function referencias()
    {
        return $this->hasMany('torrefuerte\Models\Referencia');
    }

    public function scopeTexto($query, $texto)
    {
        if( trim($texto) != '' )
        {
            $query->whereRaw(' upper(texto) like ? ', [ '%' . a_mayusculas($texto) . '%' ]);
        }
    }

    public function scopePasaje($query, $parametro = array())
    {
        $verso_ini = $parametro['verso_ini'];
        $verso_fin = $parametro['verso_fin'];

        if( ($verso_ini == null || $verso_ini == '') && ($verso_fin == null || $verso_fin == '')){
            return $query->where('libro_id', $parametro['libro'])
                            ->where('numero_capitulo', $parametro['capitulo']);
        }else{
            //No se ingresa el versiculo final O el versiculo final es igual al versiculo inicial
            if( $verso_fin == null || $verso_fin == '' || $verso_fin == $verso_ini ){
                return $query->where('libro_id', $parametro['libro'])
                                ->where('numero_capitulo', $parametro['capitulo'])
                                ->where('numero_versiculo', $verso_ini);
            }else{
                return $query->where('libro_id', $parametro['libro'])
                                ->where('numero_capitulo', $parametro['capitulo'])
                                ->whereBetween('numero_versiculo', [$verso_ini, $verso_fin]);
            }
        }
    }

}
