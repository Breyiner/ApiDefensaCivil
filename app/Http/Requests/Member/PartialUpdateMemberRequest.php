<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase PartialUpdateMemberRequest
 * * Valida la actualización parcial de un miembro.
 * Permite modificar uno o varios campos sin afectar el resto del registro.
 */
class PartialUpdateMemberRequest extends FormRequest
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
     * Reglas de validación para actualización parcial (PATCH).
     * Ningún campo es obligatorio, solo se valida si viene en la petición.
     */
    public function rules(): array
    {
        return [
            // Nombres del miembro
            'names' => 'sometimes|string|max:50',

            // Apellidos del miembro
            'last_names' => 'sometimes|string|max:50',

            // Fecha de nacimiento
            'birth_date' => 'sometimes|date',

            // Grupo sanguíneo asociado
            'blood_group_id' => 'sometimes|exists:blood_groups,id',

            // Tipo de documento
            'document_type_id' => 'sometimes|exists:document_types,id',

            // Número de documento
            'document_number' => 'sometimes|string|max:20',

            // Nacionalidad
            'nationality_id' => 'sometimes|exists:nationalities,id',

            // Género
            'gender_id' => 'sometimes|exists:genders,id',

            // Parentesco
            'kinship_id' => 'sometimes|exists:kinships,id',

            // EPS
            'eps' => 'sometimes|exists:eps,id',

            // Teléfono de contacto
            'phone' => 'sometimes|string|max:10',
        ];
    }

    /**
     * Mensajes de error personalizados.
     * Se reutilizan reglas genéricas para mantener consistencia.
     */
    public function messages(): array
    {
        return [
            'string' => 'El :attribute debe ser una cadena de texto válida',
            'date'   => 'El :attribute debe tener un formato de fecha válido',
            'exists' => 'El :attribute seleccionado no existe en el sistema',
            'max'    => 'El :attribute no puede superar los :max caracteres',
        ];
    }

    /**
     * Nombres amigables de los atributos.
     * Se usan en los mensajes de validación para mayor claridad.
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
