<?php

use App\Http\Controllers\API\Account\AccountController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE CUENTA Y SEGURIDAD (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de la cuenta del usuario autenticado: verificación de contraseña,
 * actualización de email y cambio de contraseña.
 *
 * Prefijo base: /api/account
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 *
 * Permisos requeridos (Spatie):
 * - account.verify-password
 * - account.update-email
 * - account.update-password
 */

Route::prefix('account')->group(function () {

    // Verificar la contraseña actual antes de ejecutar acciones sensibles
    Route::post('/verify-password', [AccountController::class, 'verifyPassword'])
        ->middleware('permission:account.verify-password');

    // Actualizar el email del usuario autenticado
    Route::put('/update-email', [AccountController::class, 'updateEmail'])
        ->middleware('permission:account.update-email');

    // Cambiar la contraseña del usuario autenticado
    Route::put('/update-password', [AccountController::class, 'updatePassword'])
        ->middleware('permission:account.update-password');
});