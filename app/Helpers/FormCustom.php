<?php

namespace torrefuerte\Helpers;

/**
 *Esta clase es para crear botones de formularios con iconos
 */
class FormCustom
{
    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     * @return string
     */
    public static function attributes($attributes)
    {
        $html = array();

        foreach ((array) $attributes as $key => $value)
        {
            $element = self::attributeElement($key, $value);

            if ( ! is_null($element)) $html[] = $element;
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    public static function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        if (is_numeric($key)) $key = $value;

        if ( ! is_null($value)) return $key.'="'.e($value).'"';
    }

    /*
     * Imprimir un boton con imagen
     */
    public static function buttonImg($label, $img, $attributes = array())
    {
        echo '<button '.self::attributes($attributes).'><span><img src="'.asset($img).'"/></span>'.$label.'</button>';
    }

    /*
     * Imprimir el boton cancelar con imagen
     */
    public static function buttonCancelImg($route, $label, $img, $attributes = array())
    {
        echo '<a href="'.route($route).'"'.self::attributes($attributes).'><span><img src="'.asset($img).'"/></span>'.$label.'</a>';
    }

    public static function linkRouteImg($route, $label, $img, $attributes = array())
    {
        echo '<a href="'.route($route).'"'.self::attributes($attributes).'><span><img src="'.asset($img).'"/></span>'.$label.'</a>';
    }

    public static function linkImg($url, $label, $img, $attributes = array())
    {
        echo '<a href="'.url($url).'"'.self::attributes($attributes).'><span><img src="'.asset($img).'"/></span>'.$label.'</a>';
    }

}
