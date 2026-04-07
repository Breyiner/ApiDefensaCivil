<?php

use App\Http\Controllers\API\Account\AccountController;
use App\Http\Controllers\API\Account\AccountPasswordController;
use App\Http\Controllers\API\Account\AccountVerificationController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE CUENTA (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de:
 * - Verificación de contraseña para acciones sensibles
 * - Cambio de email
 * - Cambio de contraseña
 *
 * Estas rutas requieren autenticación mediante Sanctum.
 */


// -------------------------------------------------------------------------
// CUENTA
// -------------------------------------------------------------------------


Route::prefix('account')->group(function () {


    // Genera un token temporal tras validar la contraseña actual
    Route::post('/verify-password', [AccountVerificationController::class, 'verify']);


    // Cambio de email con verificación adicional
    Route::patch('/email', [AccountController::class, 'updateEmail'])
        ->middleware('password.verify:change_email');


    // Cambio de contraseña validando la contraseña actual en el body
    Route::patch('/password', [AccountPasswordController::class, 'updatePassword']);
});