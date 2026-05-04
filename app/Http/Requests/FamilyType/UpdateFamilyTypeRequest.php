<?php

namespace App\Http\Requests\FamilyType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|alpha|max:255|unique:family_types,name,' . $this->route('id'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.alpha' => 'El nombre debe contener solo letras',
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'name.unique' => 'Ya existe un tipo de familia con ese nombre.',
        ];
    }
}
