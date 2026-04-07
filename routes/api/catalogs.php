<?php

use App\Http\Controllers\API\StateUser\StateUserController;
use App\Http\Controllers\API\Gender\GenderController;
use App\Http\Controllers\API\DocumentType\DocumentTypeController;
use App\Http\Controllers\API\StatusPlan\StatusPlanController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE CATÁLOGOS BÁSICOS (AUTENTICADAS)
 * ============================================================================
 *
 * Catálogos de referencia del sistema:
 * - Estados de plan
 * - Estados de usuario
 * - Géneros
 * - Tipos de documento
 *
 * Estas rutas requieren autenticación y email verificado.
 * Se cargan automáticamente desde bootstrap/app.php como rutas protegidas.
 */


// -------------------------------------------------------------------------
// ESTADOS DE PLAN
// -------------------------------------------------------------------------

Route::prefix('statusPlans')->group(function () {

    Route::get('/', [StatusPlanController::class, 'index']);

    Route::get('/{statusPlan_id}', [StatusPlanController::class, 'show']);

    Route::post('/', [StatusPlanController::class, 'store']);

    Route::put('/{statusPlan_id}', [StatusPlanController::class, 'update']);

    Route::delete('/{statusPlan_id}', [StatusPlanController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// ESTADOS DE USUARIO
// -------------------------------------------------------------------------

Route::prefix('stateUsers')->group(function () {

    Route::get('/', [StateUserController::class, 'index']);

    Route::get('/{state_user_id}', [StateUserController::class, 'show']);

    Route::post('/', [StateUserController::class, 'store']);

    Route::put('/{state_user_id}', [StateUserController::class, 'update']);

    Route::delete('/{state_user_id}', [StateUserController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// GÉNEROS
// -------------------------------------------------------------------------

Route::prefix('genders')->group(function () {

    Route::get('/', [GenderController::class, 'index']);

    Route::get('/{gender_id}', [GenderController::class, 'show']);

    Route::get('/{gender_id}/history', [GenderController::class, 'history']);

    Route::post('/', [GenderController::class, 'store']);

    Route::put('/{gender_id}', [GenderController::class, 'update']);

    Route::patch('/{gender_id}', [GenderController::class, 'partialUpdate']);

    Route::patch('/status/{gender_id}', [GenderController::class, 'changeStatus']);

    Route::delete('/{gender_id}', [GenderController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// TIPOS DE DOCUMENTO
// -------------------------------------------------------------------------

Route::prefix('documentTypes')->group(function () {

    Route::get('/', [DocumentTypeController::class, 'index']);

    Route::get('/{documentType_id}', [DocumentTypeController::class, 'show']);

    Route::get('/{documentType_id}/history', [DocumentTypeController::class, 'history']);

    Route::post('/', [DocumentTypeController::class, 'store']);

    Route::put('/{documentType_id}', [DocumentTypeController::class, 'update']);

    Route::patch('/{documentType_id}', [DocumentTypeController::class, 'partialUpdate']);

    Route::patch('/status/{documentType_id}', [DocumentTypeController::class, 'changeStatus']);

    Route::delete('/{documentType_id}', [DocumentTypeController::class, 'destroy']);
});