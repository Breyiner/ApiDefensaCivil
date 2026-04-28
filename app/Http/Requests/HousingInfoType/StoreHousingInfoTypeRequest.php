<?php

namespace App\Http\Requests\housingInfoType;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class storeHousingInfoType extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:housing_info_types,name,',
        ];
    }

    public function messages(): array
    {
        return [
            'name.alpha' => 'El nombre debe contener solo letras',
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'name.unique' => 'Ya existe un tipo de acción con ese nombre.',
        ];
    }
}
