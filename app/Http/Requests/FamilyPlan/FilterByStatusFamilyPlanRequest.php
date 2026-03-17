<?php

namespace App\Http\Requests\FamilyPlan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los parámetros de consulta para filtrar planes familiares por estado.
 */
class FilterByStatusFamilyPlanRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para el query parameter.
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'integer',
                'exists:status_plans,id' // Ajusta el nombre de la tabla según tu BD
            ]
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'El campo :attribute es obligatorio',
            'status.integer' => 'El campo :attribute debe ser un número entero',
            'status.exists' => 'El :attribute especificado no existe'
        ];
    }

    /**
     * Nombres personalizados de los atributos para los mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'status' => 'estado'
        ];
    }

    /**
     * Prepara los datos para la validación desde query params.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->query('status')
        ]);
    }
}