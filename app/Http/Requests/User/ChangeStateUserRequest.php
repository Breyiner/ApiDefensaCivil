<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase ChangeStateGenderRequest
 * * Controla la activación o desactivación lógica de los registros de género.
 * Esto permite deshabilitar opciones sin comprometer la integridad referencial de los perfiles.
 */
class ChangeStateUserRequest extends FormRequest
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
     * Define las reglas de validación para el cambio de estado.
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_ids'      => 'required|array|min:1',
            'user_ids.*'    => 'exists:users,id',
            'state_user_id' => 'required|exists:state_users,id',
            'async'         => 'sometimes|boolean',
        ];
    }

    /**
     * Mensajes de error personalizados con :attribute y sin puntos finales.
     * @return array
     */
    public function messages(): array
    {
        return [
            'user_ids.required'      => 'Debe enviar al menos un usuario.',
            'user_ids.array'         => 'Los usuarios deben ser un arreglo.',
            'user_ids.*.exists'      => 'Uno de los usuarios no es válido.',
            'state_user_id.required' => 'El estado es obligatorio.',
            'state_user_id.exists'   => 'El estado seleccionado no es válido.',
            'async.boolean'          => 'El campo async debe ser verdadero o falso.',
        ];
    }

    /**
     * Define el nombre amigable del atributo.
     * @return array
     */
    public function attributes(): array
    {
        return [
            'user_ids'      => 'usuarios',
            'state_user_id' => 'estado de usuario',
            'async'         => 'modo asíncrono',
        ];
    }
}