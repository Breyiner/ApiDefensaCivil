<?php

namespace App\Http\Requests\FamilyPlan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase StoreFamilyPlanRequest
 * * Valida el registro inicial de un nuevo Plan Familiar.
 * Asegura que se vinculen correctamente la zona, ciudad y seccional
 * para mantener la integridad territorial del programa.
 */
class StoreFamilyPlanRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     * * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación para la creación del plan.
     * * @return array
     */
    public function rules(): array
    {
        return [
            'last_names'     => 'required|alpha_spaces|string|max:255',
            'zone_id'        => 'required|exists:zones,id',
            'city_id'        => 'required|exists:cities,id',
            'sectional_id'   => 'required|exists:sectionals,id',
            'user_id'        => 'required|exists:users,id',
            'family_type_id' => 'nullable|exists:family_types,id',
        ];
    }

    /**
     * Mensajes de error personalizados con :attribute y sin puntos finales.
     * * @return array
     */
    public function messages(): array
    {
        return [
            'last_names.required'     => 'Los :attribute son obligatorios',
            'last_names.alpha_spaces' => 'Los :attribute solo pueden contener letras y espacios',
            'last_names.string'       => 'Los :attribute deben ser un texto válido',
            'last_names.max'          => 'Los :attribute no deben superar los :max caracteres',

            'zone_id.required'        => 'Debe seleccionar una :attribute',
            'zone_id.exists'          => 'La :attribute seleccionada no es válida',

            'city_id.required'        => 'Debe seleccionar una :attribute',
            'city_id.exists'          => 'La :attribute seleccionada no es válida',

            'sectional_id.required'   => 'Debe seleccionar una :attribute',
            'sectional_id.exists'     => 'La :attribute seleccionada no es válida',

            'user_id.required'        => 'Debe asignar un :attribute responsable',
            'user_id.exists'          => 'El :attribute seleccionado no es válido',
        ];
    }

    /**
     * Define los nombres amigables de los atributos.
     * * @return array
     */
    public function attributes(): array
    {
        return [
            'last_names'     => 'apellidos de la familia',
            'zone_id'        => 'zona',
            'city_id'        => 'ciudad',
            'sectional_id'   => 'seccional',
            'user_id'        => 'usuario responsable',
            'family_type_id' => 'tipo de familia',
        ];
    }
}