<?php

namespace App\Http\Requests\HousingInfo;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase StoreHousingInfoRequest
 * * Valida la creación de información de vivienda, incluyendo la carga de archivos.
 * Garantiza que el archivo sea una imagen válida y que no exceda los límites de tamaño.
 */
class StoreHousingInfoRequest extends FormRequest
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
     * Define las reglas de validación para la información de vivienda.
     * @return array
     */
    public function rules(): array
    {
        return [
            /**
             * Se asegura que el plan familiar exista y que no tenga ya una info registrada.
             */
            // 'family_plan_id' => 'required|exists:family_plans,id|unique:housing_infos,family_plan_id',
            'family_plan_id' => 'required|exists:family_plans,id',
            
            /**
             * Validación de archivos: tipo imagen, formatos específicos y tamaño (2MB).
             */
            'path' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'housing_info_type_id' => 'required|exists:housing_info_types,id',
        ];
    }

    /**
     * Mensajes de error personalizados con :attribute y sin punto final.
     * @return array
     */
    public function messages(): array
    {
        return [
            'family_plan_id.required' => 'El :attribute es obligatorio',
            'family_plan_id.exists'   => 'El :attribute seleccionado no es válido',
            'family_plan_id.unique'   => 'El :attribute ya cuenta con una imagen registrada',
            
            'path.required' => 'La :attribute es obligatoria',
            'path.image'    => 'El archivo :attribute debe ser una imagen',
            'path.mimes'    => 'La :attribute debe ser en formato JPG, JPEG, PNG o WEBP',
            'path.max'      => 'La :attribute no debe pesar más de 2MB'
        ];
    }

    /**
     * Define los nombres amigables de los campos.
     * @return array
     */
    public function attributes(): array
    {
        return [
            'family_plan_id' => 'plan familiar',
            'path'           => 'imagen de la vivienda',
        ];
    }
}