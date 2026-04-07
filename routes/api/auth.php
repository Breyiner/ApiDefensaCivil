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
 * Estas rutas NO requieren autenticación (sin auth:sanctum)
 * Se cargan desde bootstrap/app.php como rutas públicas
 */

// -------------------------------------------------------------------------
// AUTENTICACIÓN BÁSICA
// -------------------------------------------------------------------------

Route::post('/register', [AuthenticationController::class, 'register']);

Route::post('/login', [AuthenticationController::class, 'login']);

// -------------------------------------------------------------------------
// VERIFICACIÓN DE EMAIL
// -------------------------------------------------------------------------

Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth:sanctum');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth:sanctum', 'signed']);

Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth:sanctum', 'throttle:6,1']);

// -------------------------------------------------------------------------
// RECUPERACIÓN DE CONTRASEÑA
// -------------------------------------------------------------------------

Route::post('/password/forgot', [PasswordResetController::class, 'forgot'])
    ->middleware('throttle:5,1');

Route::post('/password/resend', [PasswordResetController::class, 'resend'])
    ->middleware('throttle:5,1');

Route::post('/password/verify', [PasswordResetController::class, 'verify']);

Route::post('/password/reset', [PasswordResetController::class, 'reset']);

// -------------------------------------------------------------------------
// RUTAS AUTENTICADAS
// -------------------------------------------------------------------------

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthenticationController::class, 'logOut']);
    
    Route::post('/refresh-token', [AuthenticationController::class, 'refreshToken'])
        ->middleware('ability:' . \App\Enums\TokenAbility::ISSUE_ACCESS_TOKEN->value);
});