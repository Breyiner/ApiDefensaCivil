<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase UpdateProfileRequest
 * * Valida la actualización completa de la información de perfil.
 * Aplica excepciones de unicidad basadas en el ID del perfil actual.
 */
class UpdateProfileRequest extends FormRequest
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
     * Define las reglas de validación para la edición del perfil.
     * * @return array
     */
    public function rules(): array
    {
        /**
         * Capturamos el ID del perfil desde la ruta para permitir que 
         * los datos únicos (como el teléfono) no choquen con el registro actual.
         */
        // $profileId = $this->route('profile_id');
        // $profileId = $this->route('profile_id') ?? $this->route('profile');
        $profileId = $this->route('profile_id') ?? $this->route('profile') ?? $this->route('id') ?? head($this->route()->parameters());

        return [
            'names'            => 'required|alpha_spaces|string|max:50',
            'last_names'       => 'required|alpha_spaces|string|max:50',
            'birth_date'       => 'required|date_format:Y-m-d|before:18 years ago',
            'document_type_id' => 'required|exists:document_types,id',
            /** * Se asume que unique_document_by_type maneja la excepción internamente 
             * o mediante parámetros adicionales si es necesario.
             */
            // 'document_number'  => 'required|numeric|max_digits:20|unique_document_by_type',
            'document_number'  => "required|numeric|max_digits:20|unique_document_by_type:{$profileId}",
            'phone'            => "required|numeric|max_digits:15|unique:profiles,phone,{$profileId}",
            'gender_id'        => 'required|exists:genders,id',
            'organization_id'  => 'required|exists:organizations,id' 
        ];
    }

    /**
     * Mensajes de error personalizados con :attribute y sin puntos finales.
     * * @return array
     */
    public function messages(): array
    {
        return [
            'names.required'           => 'Los :attribute son obligatorios',
            'names.alpha_spaces'       => 'Los :attribute deben contener solo letras y espacios',
            'names.string'             => 'Los :attribute deben ser una cadena de texto válida',
            'names.max'                => 'Los :attribute no deben superar los :max caracteres',

            'last_names.required'      => 'Los :attribute son obligatorios',
            'last_names.alpha_spaces'  => 'Los :attribute deben contener solo letras y espacios',
            'last_names.string'        => 'Los :attribute deben ser una cadena de texto válida',
            'last_names.max'           => 'Los :attribute no deben superar los :max caracteres',

            'birth_date.required'      => 'La :attribute es obligatoria',
            'birth_date.date_format'   => 'La :attribute debe tener el formato AAAA-MM-DD',
            'birth_date.before'        => 'Debe ser mayor de 18 años para actualizar el perfil',

            'document_type_id.required' => 'El :attribute es obligatorio',
            'document_type_id.exists'   => 'El :attribute seleccionado no es válido',

            'document_number.required'               => 'El :attribute es obligatorio',
            'document_number.numeric'                => 'El :attribute debe contener solo números',
            'document_number.max_digits'             => 'El :attribute no debe superar los :max dígitos',
            'document_number.unique_document_by_type' => 'Este :attribute ya existe para el tipo de documento seleccionado',

            'phone.required'   => 'El :attribute es obligatorio',
            'phone.numeric'    => 'El :attribute solo debe contener números',
            'phone.max_digits' => 'El :attribute no debe superar los :max dígitos',
            'phone.unique'     => 'Este :attribute ya está registrado por otro usuario',

            'gender_id.required' => 'El :attribute es obligatorio',
            'gender_id.exists'   => 'El :attribute seleccionado no es válido',

            'organization_id.required' => 'La :attribute es obligatoria',
            'organization_id.exists'   => 'La :attribute seleccionada no es válida',
        ];
    }

    /**
     * Define los nombres amigables de los atributos.
     * * @return array
     */
    public function attributes(): array
    {
        return [
            'names'            => 'nombres',
            'last_names'       => 'apellidos',
            'birth_date'       => 'fecha de nacimiento',
            'document_type_id' => 'tipo de documento',
            'document_number'  => 'número de documento',
            'phone'            => 'número de teléfono',
            'gender_id'        => 'género',
            'organization_id'  => 'organización',
        ];
    }
}