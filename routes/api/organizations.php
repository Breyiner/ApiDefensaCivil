<?php

use App\Http\Controllers\API\Sectional\SectionalController;
use App\Http\Controllers\API\Organization\OrganizationController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE ESTRUCTURA ORGANIZACIONAL (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de seccionales y organizaciones del sistema.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// SECCIONALES
// -------------------------------------------------------------------------

Route::prefix('sectionals')->group(function () {

    Route::get('/', [SectionalController::class, 'index'])
        ->middleware('permission:sectionals.index');

    Route::get('/{sectional_id}', [SectionalController::class, 'show'])
        ->middleware('permission:sectionals.show');

    // Listar seccionales activas junto con sus organizaciones
    Route::get('/active-with-organizations', [SectionalController::class, 'activeWithOrganizations'])
        ->middleware('permission:sectionals.active-with-organizations');

    // Historial de cambios de una seccional
    Route::get('/{sectional_id}/history', [SectionalController::class, 'history'])
        ->middleware('permission:sectionals.history');

    Route::post('/', [SectionalController::class, 'store'])
        ->middleware('permission:sectionals.store');

    Route::put('/{sectional_id}', [SectionalController::class, 'update'])
        ->middleware('permission:sectionals.update');

    Route::patch('/{sectional_id}', [SectionalController::class, 'partialUpdate'])
        ->middleware('permission:sectionals.partial-update');

    // Activar / desactivar una seccional sin eliminarla
    Route::patch('/status/{sectional_id}', [SectionalController::class, 'changeStatus'])
        ->middleware('permission:sectionals.change-status');

    Route::delete('/{sectional_id}', [SectionalController::class, 'destroy'])
        ->middleware('permission:sectionals.destroy');

    Route::get('/{id}/stats_supervisor', [SectionalController::class, 'getStatsSupervisor'])
    //    ->middleware('permission:sectionals.stats-supervisor')
    ;
});


// -------------------------------------------------------------------------
// ORGANIZACIONES
// -------------------------------------------------------------------------

Route::prefix('organizations')->group(function () {

    Route::get('/', [OrganizationController::class, 'index'])
        ->middleware('permission:organizations.index');

    Route::get('/{organization_id}', [OrganizationController::class, 'show'])
        ->middleware('permission:organizations.show');

    // Filtrar organizaciones por seccional
    Route::get('/sectional/{sectional_id}', [OrganizationController::class, 'bySectional'])
        ->middleware('permission:organizations.by-sectional');

    // Historial de cambios de una organización
    Route::get('/{organization_id}/history', [OrganizationController::class, 'history'])
        ->middleware('permission:organizations.history');

    Route::post('/', [OrganizationController::class, 'store'])
        ->middleware('permission:organizations.store');

    Route::put('/{organization_id}', [OrganizationController::class, 'update'])
        ->middleware('permission:organizations.update');

    Route::patch('/{organization_id}', [OrganizationController::class, 'partialUpdate'])
        ->middleware('permission:organizations.partial-update');

    // Activar / desactivar una organización sin eliminarla
    Route::patch('/status/{organization_id}', [OrganizationController::class, 'changeStatus'])
        ->middleware('permission:organizations.change-status');

    Route::delete('/{organization_id}', [OrganizationController::class, 'destroy'])
        ->middleware('permission:organizations.destroy');
});