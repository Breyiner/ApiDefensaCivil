<?php


namespace App\Http\Controllers\API\Account;


use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Services\Account\AccountPasswordService;
use Illuminate\Http\JsonResponse;


/**
 * Controlador para la actualización de la contraseña del usuario.
 *
 * Responsabilidad única: gestionar el cambio de contraseña
 * verificando la contraseña actual antes de actualizarla.
 *
 * Endpoints:
 * - PATCH /account/password → updatePassword()
 *
 * Middlewares requeridos: auth:sanctum
 */
class AccountPasswordController extends Controller
{
    public function __construct(
        private readonly AccountPasswordService $accountPasswordService,
    ) {}


    /**
     * Actualiza la contraseña del usuario autenticado.
     *
     * PATCH /account/password
     *
     * Body:
     * {
     *   "current_password": "contraseña_actual",
     *   "new_password": "nueva_contraseña",
     *   "new_password_confirmation": "nueva_contraseña"
     * }
     *
     * Response 200:
     * {
     *   "message": "Contraseña actualizada correctamente."
     * }
     *
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $response = $this->accountPasswordService->updatePassword(
            user:            $request->user(),
            currentPassword: $request->input('current_password'),
            newPassword:     $request->input('new_password'),
        );

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code'],
                $response['errors'],
                $response['errorKey']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
        );
    }
}
