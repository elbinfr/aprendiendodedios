<?php

namespace torrefuerte\Http\Requests;

use torrefuerte\Http\Requests\Request;

class StorePerfilRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre'      => 'required|min:4|alpha|unique:perfil,nombre',
            'descripcion' => 'required|min:5'
        ];
    }
}
