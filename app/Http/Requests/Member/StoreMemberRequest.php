<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase StoreMemberRequest
 * * Centraliza la validación necesaria para registrar un nuevo miembro.
 * Evita lógica duplicada en el controlador y mantiene reglas consistentes.
 */
class StoreMemberRequest extends FormRequest
{
    /**
     * Define si el usuario puede ejecutar esta acción.
     * En este caso no se restringe por rol ni permisos.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas técnicas de validación.
     * Cada campo representa información básica y relacional del miembro.
     */
    public function rules(): array
    {
        return [
            // Nombres del miembro (solo texto y tamaño controlado)
            'names' => 'required|string|max:50',

            // Apellidos del miembro
            'last_names' => 'required|string|max:50',

            // Fecha de nacimiento para cálculos futuros y control de edad
            'birth_date' => 'required|date',

            // Relación con el catálogo de grupos sanguíneos
            'blood_group_id' => 'required|exists:blood_groups,id',

            // Tipo de documento asociado al miembro
            'document_type_id' => 'required|exists:document_types,id',

            // Número de documento (no se valida formato porque depende del tipo)
            'document_number' => 'required|numeric|max_digits:20',

            // Nacionalidad registrada en el catálogo correspondiente
            'nationality_id' => 'required|exists:nationalities,id',

            // Género del miembro
            'gender_id' => 'required|exists:genders,id',

            // Parentesco del miembro con el núcleo familiar
            'kinship_id' => 'required|exists:kinships,id',

            // Entidad prestadora de salud
            'eps_id' => 'required|exists:eps,id',

            // Teléfono de contacto (se valida longitud, no formato)
            'phone' => 'sometimes|string|max:10',
        ];
    }

    /**
     * Mensajes de error reutilizables.
     * Se usan comodines para reducir duplicación y mantener claridad.
     */
    public function messages(): array
    {
        return [
            // Campo obligatorio
            'required' => 'El :attribute es obligatorio',

            // Validación de tipo texto
            'string' => 'El :attribute debe ser una cadena de texto válida',

            // Validación de fecha
            'date' => 'El :attribute debe tener un formato de fecha válido',

            // Validación de relaciones foráneas
            'exists' => 'El :attribute seleccionado no existe en el sistema',

            // Límite máximo de caracteres
            'max' => 'El :attribute no puede superar los :max caracteres',

            'numeric' => 'El :attribute debe tener caracteres numericos'
        ];
    }

    /**
     * Traducción de nombres técnicos a nombres entendibles para el usuario.
     * Estos valores se inyectan automáticamente en :attribute.
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
