<?php

use App\Http\Controllers\API\Action\ActionController;
use App\Http\Controllers\API\ActionType\ActionTypeController;
use App\Http\Controllers\API\ActionPlan\ActionPlanController;
use App\Http\Controllers\API\ActionPlanAction\ActionPlanActionController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE PLANES DE ACCIÓN (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de los planes de acción asociados a los planes familiares:
 * acciones, tipos de acción, planes de acción y sus relaciones.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// ACCIONES
// Catálogo general de acciones disponibles para un plan de acción.
// -------------------------------------------------------------------------

Route::prefix('actions')->group(function () {

    Route::get('/', [ActionController::class, 'index']);

    Route::get('/{action_id}', [ActionController::class, 'show']);

    Route::post('/', [ActionController::class, 'store']);

    Route::put('/{action_id}', [ActionController::class, 'update']);

    Route::delete('/{action_id}', [ActionController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// TIPOS DE ACCIÓN
// Categorías que clasifican las acciones (preventiva, correctiva, etc.).
// -------------------------------------------------------------------------

Route::prefix('actionTypes')->group(function () {

    Route::get('/', [ActionTypeController::class, 'index']);

    Route::get('/{id}', [ActionTypeController::class, 'show']);

    Route::post('/', [ActionTypeController::class, 'store']);

    Route::put('/{id}', [ActionTypeController::class, 'update']);

    Route::delete('/{id}', [ActionTypeController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// PLANES DE ACCIÓN
// Plan de intervención asociado a un plan familiar.
// -------------------------------------------------------------------------

Route::prefix('actionPlans')->group(function () {

    Route::get('/', [ActionPlanController::class, 'index']);

    Route::get('/{id}', [ActionPlanController::class, 'show']);

    // Obtener el plan de acción asociado a un plan familiar
    Route::get('/familyPlan/{id}', [ActionPlanController::class, 'getByPlan']);

    // Verificar en boolean si existe un plan de acción para un plan familiar
    Route::get('/familyPlan/boolean/{id}', [ActionPlanController::class, 'getByPlanBoolean']);

    Route::post('/', [ActionPlanController::class, 'store']);

    Route::put('/{id}', [ActionPlanController::class, 'update']);

    Route::delete('/{id}', [ActionPlanController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// ACCIONES DEL PLAN DE ACCIÓN (TABLA PIVOTE)
// Acciones específicas asignadas a un plan de acción.
// -------------------------------------------------------------------------

Route::prefix('actionPlanActions')->group(function () {

    Route::get('/', [ActionPlanActionController::class, 'index']);

    Route::get('/{id}', [ActionPlanActionController::class, 'show']);

    // Obtener las acciones pertenecientes a un plan de acción específico
    Route::get('/actionPlan/{id}', [ActionPlanActionController::class, 'getActionForActionPlan']);

    Route::post('/', [ActionPlanActionController::class, 'store']);

    Route::put('/{id}', [ActionPlanActionController::class, 'update']);

    Route::patch('/{id}', [ActionPlanActionController::class, 'partialUpdate']);

    Route::delete('/{id}', [ActionPlanActionController::class, 'destroy']);
});