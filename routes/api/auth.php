<?php

use App\Http\Controllers\API\Auth\AuthenticationController;
use App\Http\Controllers\API\EmailVerification\EmailVerificationController;
use App\Http\Controllers\API\PasswordReset\PasswordResetController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS PÚBLICAS DE AUTENTICACIÓN
 * ============================================================================
 *
 * Estas rutas NO requieren autenticación (sin auth:sanctum).
 * Se cargan desde bootstrap/app.php como rutas públicas.
 *
 * NOTA: Los permisos de Spatie no aplican aquí ya que son rutas públicas.
 * El control de acceso comienza una vez el usuario está autenticado.
 */


// -------------------------------------------------------------------------
// AUTENTICACIÓN BÁSICA
// -------------------------------------------------------------------------

// Registro de un nuevo usuario en el sistema
Route::post('/register', [AuthenticationController::class, 'register']);

// Inicio de sesión con credenciales
Route::post('/login', [AuthenticationController::class, 'login']);


// -------------------------------------------------------------------------
// VERIFICACIÓN DE EMAIL
// -------------------------------------------------------------------------

// Aviso de verificación pendiente (requiere sesión activa)
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth:sanctum');

// Verificación del enlace enviado al correo (requiere firma digital)
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    // ->middleware(['auth:sanctum', 'signed'])
    ->middleware(['signed'])
    ->name('verification.verify');

// Reenvío del correo de verificación (máx. 6 intentos por minuto)
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth:sanctum', 'throttle:6,1']);


// -------------------------------------------------------------------------
// RECUPERACIÓN DE CONTRASEÑA
// -------------------------------------------------------------------------

// Solicitar enlace/código de restablecimiento (máx. 5 intentos por minuto)
Route::post('/password/forgot', [PasswordResetController::class, 'forgot'])
    ->middleware('throttle:5,1');

// Reenviar el código de restablecimiento (máx. 5 intentos por minuto)
Route::post('/password/resend', [PasswordResetController::class, 'resend'])
    ->middleware('throttle:5,1');

// Verificar el código recibido antes de permitir el cambio
Route::post('/password/verify', [PasswordResetController::class, 'verify']);

// Restablecer la contraseña con el código ya verificado
Route::post('/password/reset', [PasswordResetController::class, 'reset']);


// -------------------------------------------------------------------------
// RUTAS AUTENTICADAS
// -------------------------------------------------------------------------

Route::middleware('auth:sanctum')->group(function () {

    // Cierre de sesión del usuario autenticado (invalida el token actual)
    Route::post('/logout', [AuthenticationController::class, 'logOut']);

    // Renovación del access token usando el refresh token
    // Solo disponible para tokens con la habilidad 'issue-access-token'
    Route::post('/refresh-token', [AuthenticationController::class, 'refreshToken'])
        ->middleware('ability:' . \App\Enums\TokenAbility::ISSUE_ACCESS_TOKEN->value);
});