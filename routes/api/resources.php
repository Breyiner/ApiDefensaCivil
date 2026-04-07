<?php

use App\Http\Controllers\API\Resource\ResourceController;
use App\Http\Controllers\API\AvailableResource\AvailableResourceController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE RECURSOS (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión del catálogo de recursos y los recursos disponibles
 * asociados a cada plan familiar.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// RECURSOS
// Catálogo de recursos que pueden asignarse a un plan familiar.
// -------------------------------------------------------------------------

Route::prefix('resources')->group(function () {

    Route::get('/', [ResourceController::class, 'index']);

    Route::get('/{resource_id}', [ResourceController::class, 'show']);

    // Historial de cambios de un recurso
    Route::get('/{resource_id}/history', [ResourceController::class, 'history']);

    Route::post('/', [ResourceController::class, 'store']);

    Route::put('/{resource_id}', [ResourceController::class, 'update']);

    Route::patch('/{resource_id}', [ResourceController::class, 'partialUpdate']);

    // Activar / desactivar un recurso sin eliminarlo
    Route::patch('/status/{resource_id}', [ResourceController::class, 'changeStatus']);

    Route::delete('/{resource_id}', [ResourceController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// RECURSOS DISPONIBLES
// Recursos concretos con los que cuenta un plan familiar específico.
// -------------------------------------------------------------------------

Route::prefix('availableResources')->group(function () {

    Route::get('/', [AvailableResourceController::class, 'index']);

    Route::get('/{id}', [AvailableResourceController::class, 'show']);

    // Obtener los recursos disponibles de un plan familiar
    Route::get('/familyPlan/{family_plan_id}', [AvailableResourceController::class, 'getForPlan']);

    Route::post('/', [AvailableResourceController::class, 'store']);

    Route::put('/{id}', [AvailableResourceController::class, 'update']);

    Route::patch('/{id}', [AvailableResourceController::class, 'partialUpdate']);

    Route::delete('/{id}', [AvailableResourceController::class, 'destroy']);
});