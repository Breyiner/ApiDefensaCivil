<?php

use App\Http\Controllers\API\Sectional\SectionalController;
use App\Http\Controllers\API\Organization\OrganizationController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE ESTRUCTURA ORGANIZACIONAL (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de:
 * - Seccionales
 * - Organizaciones
 * - Perfiles
 *
 * Estas rutas requieren autenticación y email verificado.
 */


// -------------------------------------------------------------------------
// SECCIONALES
// -------------------------------------------------------------------------

Route::prefix('sectionals')->group(function () {

    Route::get('/', [SectionalController::class, 'index']);

    Route::get('/active-with-organizations', [SectionalController::class, 'getActiveWithOrganization']);

    Route::get('/{sectional_id}', [SectionalController::class, 'show']);

    Route::get('/{sectional_id}/history', [SectionalController::class, 'history']);

    Route::post('/', [SectionalController::class, 'store']);

    Route::put('/{sectional_id}', [SectionalController::class, 'update']);

    Route::patch('/{sectional_id}', [SectionalController::class, 'partialUpdate']);

    Route::patch('/status/{sectional_id}', [SectionalController::class, 'changeStatus']);

    Route::delete('/{sectional_id}', [SectionalController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// ORGANIZACIONES
// -------------------------------------------------------------------------

Route::prefix('organizations')->group(function () {

    Route::get('/', [OrganizationController::class, 'index']);

    Route::get('/{organization_id}', [OrganizationController::class, 'show']);

    Route::get('/sectional/{sectional_id}', [OrganizationController::class, 'getSectional']);

    Route::get('/{organization_id}/history', [OrganizationController::class, 'history']);

    Route::post('/', [OrganizationController::class, 'store']);

    Route::put('/{organization_id}', [OrganizationController::class, 'update']);

    Route::patch('/{organization_id}', [OrganizationController::class, 'partialUpdate']);

    Route::patch('/status/{organization_id}', [OrganizationController::class, 'changeStatus']);

    Route::delete('/{organization_id}', [OrganizationController::class, 'destroy']);
});