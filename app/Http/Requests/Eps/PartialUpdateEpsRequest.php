<?php

namespace App\Http\Requests\Eps;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PartialUpdateEpsRequest extends FormRequest
{
    /**
     * Autorizar la solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para actualización parcial.
     */
    public function rules(): array
    {
        $epsId = $this->route('eps_id');

        return [
            'name'   => "sometimes|string|max:50|unique:eps,name,{$epsId}",
            'is_active' => 'sometimes|boolean',
        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'name.string'  => 'El :attribute debe ser una cadena de texto válida',
            'name.max'     => 'El :attribute no debe superar los :max caracteres',
            'name.unique'  => 'El :attribute ya se encuentra registrado',

            'is_active.boolean' => 'El :attribute debe ser verdadero o falso',
        ];
    }

    /**
     * Nombres amigables.
     */
    public function attributes(): array
    {
        return [
            'name'   => 'nombre de la EPS',
            'is_active' => 'estado de la EPS',
        ];
    }
}
