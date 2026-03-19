<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de validación para actualización parcial de una mascota (PATCH)
 */
class PartialUpdatePetRequest extends FormRequest
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
     * Reglas de validación para la actualización parcial de mascotas
     * Los campos son opcionales (sometimes) pero se validan si están presentes
     * 
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Nombre: opcional, si se envía debe ser texto, máximo 50 caracteres
            'name' => 'sometimes|string|max:50',
            
            // Raza: opcional, si se envía debe ser texto, máximo 50 caracteres
            'breed' => 'sometimes|string|max:50',
            
            // Fecha de nacimiento: opcional, si se envía debe ser fecha válida y no futura
            'birth_date' => 'sometimes|date|before_or_equal:today',
            
            // ID del género: opcional, si se envía debe existir en la tabla animal_genders
            'animal_gender_id' => 'sometimes|exists:animal_genders,id',
            
            // ID de la especie: opcional, si se envía debe existir en la tabla species
            'species_id' => 'sometimes|exists:species,id',
            
            // ID del plan familiar: opcional, si se envía debe existir en la tabla family_plans
            'family_plan_id' => 'sometimes|exists:family_plans,id',
        ];
    }

    /**
     * Mensajes de error personalizados para las validaciones
     * Solo se incluyen mensajes para validaciones que no sean 'required'
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Mensajes para el campo 'name'
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede superar los 50 caracteres.',

            // Mensajes para el campo 'breed'
            'breed.string' => 'La raza debe ser un texto válido.',
            'breed.max' => 'La raza no puede superar los 50 caracteres.',

            // Mensajes para el campo 'birth_date'
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'birth_date.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',

            // Mensajes para el campo 'animal_gender_id'
            'animal_gender_id.exists' => 'El género seleccionado no existe.',
            
            // Mensajes para el campo 'species_id'
            'species_id.exists' => 'La especie seleccionada no existe.',
            
            // Mensajes para el campo 'family_plan_id'
            'family_plan_id.exists' => 'El plan familiar seleccionado no existe.',
        ];
    }
}