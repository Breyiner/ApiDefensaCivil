<?php

namespace App\Http\Requests\Eps;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangeStateEpsRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado.
     */
    public function authorize(): bool
    {
        return true; // Ajusta si necesitas permisos específicos
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'is_active' => 'required|boolean', // Obligatorio para changeState
        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'is_active.required' => 'El estado de la EPS es obligatorio.',
            'is_active.boolean'  => 'El estado debe ser verdadero o falso.',
        ];
    }

    /**
     * Nombres amigables de los atributos.
     */
    public function attributes(): array
    {
        return [
            'is_active' => 'estado de la EPS',
        ];
    }
}
