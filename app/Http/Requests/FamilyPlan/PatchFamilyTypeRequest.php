<?php

namespace App\Http\Requests\familyPlan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PatchFamilyTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'family_type_id' => 'required|exists:family_types,id',


            'comentary' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'family_type_id.required' => 'El :attribute es obligatorio',
            'family_type_id.exists'   => 'El :attribute seleccionado no es válido',

            'comentary.string' => 'El comentario debe ser texto válido',
            'comentary.max'    => 'El comentario no puede superar los :max caracteres'
        ];
    }

    public function attributes(): array
    {
        return [
            'family_type_id' => 'tipo de familia',
            'comentary'      => 'comentario'
        ];
    }
}
