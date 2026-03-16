<?php


namespace App\Services\Account;


use App\Models\User\User;
use Illuminate\Support\Facades\Hash;


/**
 * Servicio para la actualización de la contraseña del usuario.
 *
 * Responsabilidad única: validar la contraseña actual y actualizarla.
 *
 * La contraseña nueva llega ya validada desde UpdatePasswordRequest
 * (formato, confirmación y reglas de seguridad).
 */
class AccountPasswordService
{
    /**
     * Actualiza la contraseña del usuario autenticado.
     *
     * Verifica que la contraseña actual sea correcta antes de
     * realizar el cambio, previniendo actualizaciones no autorizadas.
     *
     * @param User   $user            Usuario autenticado
     * @param string $currentPassword Contraseña actual en texto plano
     * @param string $newPassword     Nueva contraseña en texto plano
     * @return array{error: bool, code: int, message: string}
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): array
    {
        /**
         * Verifica que la contraseña actual sea correcta.
         *
         * Hash::check compara el texto plano contra el hash almacenado
         * sin exponer la contraseña real en ningún momento.
         */
        if (!Hash::check($currentPassword, $user->password)) {
            return [
                'error'    => true,
                'code'     => 401,
                'message'  => 'La contraseña actual es incorrecta.',
                'errors'   => [],
                'errorKey' => 'current_password_incorrect',
            ];
        }

        /**
         * Actualiza la contraseña con Eloquent.
         *
         * El modelo User hashea automáticamente la contraseña
         * mediante el cast 'hashed' en $casts.
         */
        $user->update(['password' => $newPassword]);

        return [
            'error'   => false,
            'code'    => 200,
            'message' => 'Contraseña actualizada correctamente.',
        ];
    }
}