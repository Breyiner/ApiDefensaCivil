<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Clase UpdateDepartmentRequest
 * Se encarga de validar la edición de departamentos existentes de forma segura.
 */
class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación para la actualización de un departamento.
     * @return array
     */
    public function rules(): array
    {
        /**
         * En los apiResource de Laravel, el parámetro de la ruta se llama igual que el recurso en singular ('department').
         * Evaluamos ambas opciones por seguridad de arquitectura.
         */
        $departmentParam = $this->route('department') ?? $this->route('department_id');

        /**
         * Si Laravel inyectó el objeto del modelo completo en la ruta, extraemos solo su ID.
         * De lo contrario, conservamos el valor tal como viene.
         */
        $departmentId = is_object($departmentParam) ? $departmentParam->id : $departmentParam;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                // Expresión regular nativa para permitir letras, tildes (áéíóú), eñes (ñÑ) y espacios en blanco
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
                // Uso fluido de Rule::unique para ignorar el ID actual de forma segura y evitar fallos de sintaxis SQL
                Rule::unique('departments', 'name')->ignore($departmentId),
            ],
        ];
    }

    /**
     * Mensajes de error personalizados.
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El :attribute es obligatorio',
            'name.regex'    => 'El :attribute debe tener solo letras y espacios',
            'name.string'   => 'El :attribute debe ser de tipo texto',
            'name.unique'   => 'El :attribute ya existe',
            'name.max'      => 'El :attribute tiene un máximo de 50 caracteres'
        ];
    }

    /**
     * Define el nombre amigable del campo para los mensajes.
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre del departamento',
        ];
    }
}