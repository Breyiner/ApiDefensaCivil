<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase UpdateMemberRequest
 * * Valida la actualización de un miembro existente.
 * Permite modificar solo los campos enviados sin afectar el resto del registro.
 */
class UpdateMemberRequest extends FormRequest
{
    /**
     * Autoriza la ejecución de la solicitud.
     * No se aplican restricciones adicionales por ahora.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para actualización.
     * Se usa "sometimes" para permitir cambios parciales.
     */
    public function rules(): array
    {
        return [
            // Nombres del miembro
            'names' => 'sometimes|required|string|max:50',

            // Apellidos del miembro
            'last_names' => 'sometimes|required|string|max:50',

            // Fecha de nacimiento
            'birth_date' => 'sometimes|required|date',

            // Grupo sanguíneo asociado
            'blood_group_id' => 'sometimes|required|exists:blood_groups,id',

            // Tipo de documento
            'document_type_id' => 'sometimes|required|exists:document_types,id',

            // Número de documento
            'document_number' => 'sometimes|required|string|max:20',

            // Nacionalidad
            'nationality_id' => 'sometimes|required|exists:nationalities,id',

            // Género
            'gender_id' => 'sometimes|required|exists:genders,id',

            // Parentesco
            'kinship_id' => 'sometimes|required|exists:kinships,id',

            // EPS
            'eps_id' => 'sometimes|required|exists:eps,id',

            // Teléfono de contacto
            'phone' => 'sometimes|required|string|max:10',
        ];
    }

    /**
     * Mensajes de error reutilizables.
     * Se mantienen genéricos para evitar duplicación innecesaria.
     */
    public function messages(): array
    {
        return [
            'required' => 'El :attribute es obligatorio',
            'string'   => 'El :attribute debe ser una cadena de texto válida',
            'date'     => 'El :attribute debe tener un formato de fecha válido',
            'exists'   => 'El :attribute seleccionado no existe en el sistema',
            'max'      => 'El :attribute no puede superar los :max caracteres',
        ];
    }

    /**
     * Nombres amigables para los atributos.
     * Mejora la claridad de los mensajes hacia el usuario final.
     */
    public function attributes(): array
    {
        return [
            'names' => 'nombres',
            'last_names' => 'apellidos',
            'birth_date' => 'fecha de nacimiento',
            'blood_group_id' => 'grupo sanguíneo',
            'document_type_id' => 'tipo de documento',
            'document_number' => 'número de documento',
            'nationality_id' => 'nacionalidad',
            'gender_id' => 'género',
            'kinship_id' => 'parentesco',
            'eps' => 'EPS',
            'phone' => 'teléfono',
        ];
    }
}
