<?php

namespace App\Http\Requests\Eps;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEpsRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        $epsId = $this->route('eps_id');

        return [
            'name'   => "required|string|max:50|unique:eps,name,{$epsId}",
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El :attribute es obligatorio',
            'name.string'   => 'El :attribute debe ser una cadena de texto válida',
            'name.max'      => 'El :attribute no debe superar los :max caracteres',
            'name.unique'   => 'El :attribute ya se encuentra registrado',

            'is_active.required' => 'El :attribute es obligatorio',
            'is_active.boolean'  => 'El :attribute debe ser verdadero o falso',
        ];
    }

    /**
     * Nombres amigables de atributos.
     */
    public function attributes(): array
    {
        return [
            'name'   => 'nombre de la EPS',
            'is_active' => 'estado de la EPS',
        ];
    }
}
