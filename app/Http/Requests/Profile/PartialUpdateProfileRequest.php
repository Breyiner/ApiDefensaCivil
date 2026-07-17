<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase PartialUpdateProfileRequest
 * * Valida actualizaciones parciales del perfil de usuario.
 * Maneja lógica compleja como la mayoría de edad y unicidad de documentos.
 */
class PartialUpdateProfileRequest extends FormRequest
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
     * Define las reglas de validación para la actualización del perfil.
     * * @return array
     */
    public function rules(): array
    {
        /**
         * Obtenemos el ID del perfil (ajusta el nombre del parámetro según tu ruta, 
         * ej: 'profile' o 'id') para las excepciones de unicidad.
         */
        // $profileId = $this->route('profile_id');
        $profileId = $this->route('profile_id') ?? $this->route('profile') ?? $this->route('id') ?? head($this->route()->parameters());

        return [
            'names'            => 'sometimes|alpha_spaces|string|max:50',
            'last_names'       => 'sometimes|alpha_spaces|string|max:50',
            'birth_date'       => 'sometimes|date_format:Y-m-d|before:18 years ago',
            'document_type_id' => 'sometimes|exists:document_types,id',
            /**
             * document_number: Se asume que 'unique_document_by_type' es una regla personalizada 
             * que ya maneja la excepción del ID internamente.
             */
            // 'document_number'  => 'required|numeric|max_digits:20|unique_document_by_type',
            'document_number'  => "required|numeric|max_digits:20|unique_document_by_type:{$profileId}",
            'phone'            => "sometimes|numeric|max_digits:15|unique:profiles,phone,{$profileId}",
            'gender_id'        => 'sometimes|exists:genders,id',
            'organization_id'  => 'sometimes|exists:organizations,id' 
        ];
    }

    /**
     * Mensajes de error personalizados con :attribute y sin puntos finales.
     * * @return array
     */
    public function messages(): array
    {
        return [
            'names.alpha_spaces'      => 'Los :attribute deben contener solo letras y espacios',
            'names.string'            => 'Los :attribute deben ser una cadena de texto válida',
            'names.max'               => 'Los :attribute no deben superar los :max caracteres',
            
            'last_names.alpha_spaces' => 'Los :attribute deben contener solo letras y espacios',
            'last_names.string'       => 'Los :attribute deben ser una cadena de texto válida',
            'last_names.max'          => 'Los :attribute no deben superar los :max caracteres',
            
            'birth_date.date_format'  => 'La :attribute debe tener el formato AAAA-MM-DD',
            'birth_date.before'       => 'Para registrarse debe ser mayor de 18 años',
            
            'document_type_id.exists' => 'El :attribute seleccionado no es válido',
            
            'document_number.numeric'                => 'El :attribute debe contener solo números',
            'document_number.max_digits'             => 'El :attribute no debe superar los :max dígitos',
            'document_number.unique_document_by_type' => 'Este :attribute ya existe para el tipo de documento seleccionado',
            
            'phone.numeric'    => 'El :attribute solo debe contener números',
            'phone.max_digits' => 'El :attribute no debe superar los :max dígitos',
            'phone.unique'     => 'Este :attribute ya está registrado',
            
            'gender_id.exists'       => 'El :attribute seleccionado no es válido',
            'organization_id.exists' => 'La :attribute seleccionada no es válida'
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