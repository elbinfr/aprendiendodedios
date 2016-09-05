<?php

namespace torrefuerte\Http\Requests;

use torrefuerte\Http\Requests\Request;
use Illuminate\Routing\Route;

class UpdatePerfilRequest extends Request
{
    private $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }
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
            'nombre'      => 'required|min:4|alpha|unique:perfil,nombre,'.$this->route->getParameter('perfil'),
            'descripcion' => 'required|min:5'
        ];
    }
}
