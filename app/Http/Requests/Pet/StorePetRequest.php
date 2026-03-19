<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de validación para crear una nueva mascota
 */
class StorePetRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Permitir a todos los usuarios autenticados
    }

    /**
     * Reglas de validación para la creación de mascotas
     * 
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Nombre de la mascota: obligatorio, texto, máximo 50 caracteres
            'name' => 'required|string|max:50',
            
            // Raza: obligatoria, texto, máximo 50 caracteres
            'breed' => 'required|string|max:50',
            
            // Fecha de nacimiento: obligatoria, debe ser una fecha válida, no puede ser futura
            'birth_date' => 'required|date|before_or_equal:today',
            
            // ID del género: obligatorio, debe existir en la tabla animal_genders
            'animal_gender_id' => 'required|exists:animal_genders,id',
            
            // ID de la especie: obligatorio, debe existir en la tabla species
            'species_id' => 'required|exists:species,id',
            
            // ID del plan familiar: obligatorio, debe existir en la tabla family_plans
            'family_plan_id' => 'required|exists:family_plans,id',
        ];
    }

    /**
     * Mensajes de error personalizados para las validaciones
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Mensajes para el campo 'name'
            'name.required' => 'El nombre de la mascota es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede superar los 50 caracteres.',

            // Mensajes para el campo 'breed'
            'breed.required' => 'La raza es obligatoria.',
            'breed.string' => 'La raza debe ser un texto válido.',
            'breed.max' => 'La raza no puede superar los 50 caracteres.',

            // Mensajes para el campo 'birth_date'
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'birth_date.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',

            // Mensajes para el campo 'animal_gender_id'
            'animal_gender_id.required' => 'El género del animal es obligatorio.',
            'animal_gender_id.exists' => 'El género seleccionado no existe.',

            // Mensajes para el campo 'species_id'
            'species_id.required' => 'La especie es obligatoria.',
            'species_id.exists' => 'La especie seleccionada no existe.',

            // Mensajes para el campo 'family_plan_id'
            'family_plan_id.required' => 'El plan familiar es obligatorio.',
            'family_plan_id.exists' => 'El plan familiar seleccionado no existe.',
        ];
    }
}