<?php

use App\Http\Controllers\API\Resource\ResourceController;
use App\Http\Controllers\API\AvailableResource\AvailableResourceController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE RECURSOS (AUTENTICADAS)
 * ============================================================================
 *
 * Catálogo de recursos y recursos disponibles por plan familiar.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// RECURSOS
// Catálogo maestro de recursos asignables.
// -------------------------------------------------------------------------

Route::prefix('resources')->group(function () {

    Route::get('/', [ResourceController::class, 'index'])
        ->middleware('permission:resources.index');

    Route::get('/{resource_id}', [ResourceController::class, 'show'])
        ->middleware('permission:resources.show');

    // Historial de cambios de un recurso
    Route::get('/{resource_id}/history', [ResourceController::class, 'history'])
        ->middleware('permission:resources.history');

    Route::post('/', [ResourceController::class, 'store'])
        ->middleware('permission:resources.store');

    Route::put('/{resource_id}', [ResourceController::class, 'update'])
        ->middleware('permission:resources.update');

    Route::patch('/{resource_id}', [ResourceController::class, 'partialUpdate'])
        ->middleware('permission:resources.partial-update');

    // Activar / desactivar un recurso sin eliminarlo
    Route::patch('/status/{resource_id}', [ResourceController::class, 'changeStatus'])
        ->middleware('permission:resources.change-status');

    Route::delete('/{resource_id}', [ResourceController::class, 'destroy'])
        ->middleware('permission:resources.destroy');
});


// -------------------------------------------------------------------------
// RECURSOS DISPONIBLES
// Recursos concretos con los que cuenta un plan familiar.
// -------------------------------------------------------------------------

Route::prefix('availableResources')->group(function () {

    Route::get('/', [AvailableResourceController::class, 'index'])
        ->middleware('permission:available-resources.index');

    Route::get('/{id}', [AvailableResourceController::class, 'show'])
        ->middleware('permission:available-resources.show');

    // Obtener los recursos disponibles de un plan familiar
    Route::get('/familyPlan/{family_plan_id}', [AvailableResourceController::class, 'getForPlan'])
        ->middleware('permission:available-resources.by-family-plan');

    Route::post('/', [AvailableResourceController::class, 'store'])
        ->middleware('permission:available-resources.store');

    Route::put('/{id}', [AvailableResourceController::class, 'update'])
        ->middleware('permission:available-resources.update');

    Route::patch('/{id}', [AvailableResourceController::class, 'partialUpdate'])
        ->middleware('permission:available-resources.partial-update');

    Route::delete('/{id}', [AvailableResourceController::class, 'destroy'])
        ->middleware('permission:available-resources.destroy');
});