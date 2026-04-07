<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida operaciones masivas que solo requieren IDs de usuarios.
 *
 * 🔹 Reutilizable para: approveRequests y rejectAndDeleteRequests
 * 🔹 async opcional para controlar si se ejecuta en Job o de forma síncrona
 */
class BulkUserIdsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'async'      => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_ids.required'  => 'Debe enviar al menos un usuario.',
            'user_ids.array'     => 'Los usuarios deben ser un arreglo.',
            'user_ids.*.exists'  => 'Uno de los usuarios no es válido.',
            'async.boolean'      => 'El campo async debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_ids' => 'usuarios',
            'async'    => 'modo asíncrono',
        ];
    }
}