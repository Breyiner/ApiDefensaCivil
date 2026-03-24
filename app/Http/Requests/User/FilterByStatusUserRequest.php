<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de validación para filtrar usuarios por estado.
 * 
 * Valida que el parámetro 'status' sea un ID válido de la tabla state_users.
 */
class FilterByStatusUserRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación que se aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|integer|exists:state_users,id',
        ];
    }

    /**
     * Mensajes personalizados de error para las validaciones.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'El parámetro status es obligatorio',
            'status.integer' => 'El parámetro status debe ser un número entero',
            'status.exists' => 'El estado especificado no existe en el sistema',
        ];
    }

    /**
     * Obtiene los datos validados de la query string.
     * Laravel automáticamente valida los query parameters.
     */
    protected function prepareForValidation(): void
    {
        // Si necesitas obtener el status de la query string
        $this->merge([
            'status' => $this->query('status'),
        ]);
    }
}