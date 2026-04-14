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
 * catálogo de acciones, tipos, planes y sus relaciones.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// ACCIONES
// Catálogo general de acciones disponibles.
// -------------------------------------------------------------------------

Route::prefix('actions')->group(function () {

    Route::get('/', [ActionController::class, 'index'])
        ->middleware('permission:actions.index');

    Route::get('/{action_id}', [ActionController::class, 'show'])
        ->middleware('permission:actions.show');

    Route::post('/', [ActionController::class, 'store'])
        ->middleware('permission:actions.store');

    Route::put('/{action_id}', [ActionController::class, 'update'])
        ->middleware('permission:actions.update');

    Route::delete('/{action_id}', [ActionController::class, 'destroy'])
        ->middleware('permission:actions.destroy');
});


// -------------------------------------------------------------------------
// TIPOS DE ACCIÓN
// Categorías que clasifican las acciones (preventiva, correctiva, etc.).
// -------------------------------------------------------------------------

Route::prefix('actionTypes')->group(function () {

    Route::get('/', [ActionTypeController::class, 'index'])
        ->middleware('permission:action-types.index');

    Route::get('/{id}', [ActionTypeController::class, 'show'])
        ->middleware('permission:action-types.show');

    Route::post('/', [ActionTypeController::class, 'store'])
        ->middleware('permission:action-types.store');

    Route::put('/{id}', [ActionTypeController::class, 'update'])
        ->middleware('permission:action-types.update');

    Route::delete('/{id}', [ActionTypeController::class, 'destroy'])
        ->middleware('permission:action-types.destroy');
});


// -------------------------------------------------------------------------
// PLANES DE ACCIÓN
// Plan de intervención vinculado a un plan familiar.
// -------------------------------------------------------------------------

Route::prefix('actionPlans')->group(function () {

    Route::get('/', [ActionPlanController::class, 'index'])
        ->middleware('permission:action-plans.index');

    Route::get('/{id}', [ActionPlanController::class, 'show'])
        ->middleware('permission:action-plans.show');

    // Obtener el plan de acción asociado a un plan familiar
    Route::get('/familyPlan/{id}', [ActionPlanController::class, 'getByPlan'])
        ->middleware('permission:action-plans.by-family-plan');

    // Verificar en boolean si existe un plan de acción para un plan familiar
    Route::get('/familyPlan/boolean/{id}', [ActionPlanController::class, 'getByPlanBoolean'])
        ->middleware('permission:action-plans.boolean-by-family-plan');

    Route::post('/', [ActionPlanController::class, 'store'])
        ->middleware('permission:action-plans.store');

    Route::put('/{id}', [ActionPlanController::class, 'update'])
        ->middleware('permission:action-plans.update');

    Route::delete('/{id}', [ActionPlanController::class, 'destroy'])
        ->middleware('permission:action-plans.destroy');
});


// -------------------------------------------------------------------------
// ACCIONES DEL PLAN DE ACCIÓN (TABLA PIVOTE)
// Acciones específicas asignadas a un plan de acción.
// -------------------------------------------------------------------------

Route::prefix('actionPlanActions')->group(function () {

    Route::get('/', [ActionPlanActionController::class, 'index'])
        ->middleware('permission:action-plan-actions.index');

    Route::get('/{id}', [ActionPlanActionController::class, 'show'])
        ->middleware('permission:action-plan-actions.show');

    // Obtener las acciones pertenecientes a un plan de acción específico
    Route::get('/actionPlan/{id}', [ActionPlanActionController::class, 'getActionForActionPlan'])
        ->middleware('permission:action-plan-actions.by-action-plan');

    Route::post('/', [ActionPlanActionController::class, 'store'])
        ->middleware('permission:action-plan-actions.store');

    Route::put('/{id}', [ActionPlanActionController::class, 'update'])
        ->middleware('permission:action-plan-actions.update');

    Route::patch('/{id}', [ActionPlanActionController::class, 'partialUpdate'])
        ->middleware('permission:action-plan-actions.partial-update');

    Route::delete('/{id}', [ActionPlanActionController::class, 'destroy'])
        ->middleware('permission:action-plan-actions.destroy');
});