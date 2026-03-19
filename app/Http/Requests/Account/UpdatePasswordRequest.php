<?php


namespace App\Http\Requests\Account;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


/**
 * Valida los datos para actualizar la contraseña del usuario.
 *
 * Se usa en: PATCH /account/password
 *
 * Valida que la contraseña actual esté presente para verificar
 * la identidad del usuario, y que la nueva contraseña cumpla
 * las reglas de seguridad y sea confirmada correctamente.
 */
class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
            ],
            'new_password' => [
                'required',
                'string',
                'confirmed',                // valida new_password_confirmation
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'new_password_confirmation' => [
                'required',
                'string',
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'current_password.required'         => 'La contraseña actual es obligatoria.',
            'current_password.string'           => 'La contraseña actual debe ser un texto válido.',

            'new_password.required'             => 'La nueva contraseña es obligatoria.',
            'new_password.string'               => 'La nueva contraseña debe ser un texto válido.',
            'new_password.confirmed'            => 'La confirmación de la nueva contraseña no coincide.',
            'new_password.min'                  => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.mixed_case'           => 'La nueva contraseña debe contener mayúsculas y minúsculas.',
            'new_password.letters'              => 'La nueva contraseña debe contener al menos una letra.',
            'new_password.numbers'              => 'La nueva contraseña debe contener al menos un número.',
            'new_password.symbols'              => 'La nueva contraseña debe contener al menos un símbolo.',

            'new_password_confirmation.required' => 'La confirmación de la nueva contraseña es obligatoria.',
            'new_password_confirmation.string'   => 'La confirmación debe ser un texto válido.',
        ];
    }


    public function attributes(): array
    {
        return [
            'current_password'          => 'contraseña actual',
            'new_password'              => 'nueva contraseña',
            'new_password_confirmation' => 'confirmación de la nueva contraseña',
        ];
    }
}