<?php

use App\Http\Controllers\API\Zone\ZoneController;
use App\Http\Controllers\API\Sector\SectorController;
use App\Http\Controllers\API\Department\DepartmentController;
use App\Http\Controllers\API\City\CityController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE GEOGRAFÍA (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión del árbol geográfico del sistema: zonas, sectores,
 * departamentos y ciudades.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 *
 * Permisos requeridos (Spatie): ver cada grupo de rutas.
 */


// -------------------------------------------------------------------------
// ZONAS
// División geográfica de primer nivel.
// -------------------------------------------------------------------------

Route::prefix('zones')->group(function () {

    Route::get('/', [ZoneController::class, 'index'])
        ->middleware('permission:zones.index');

    Route::get('/{id}', [ZoneController::class, 'show'])
        ->middleware('permission:zones.show');

    Route::post('/', [ZoneController::class, 'store'])
        ->middleware('permission:zones.store');

    Route::put('/{id}', [ZoneController::class, 'update'])
        ->middleware('permission:zones.update');

    Route::delete('/{id}', [ZoneController::class, 'destroy'])
        ->middleware('permission:zones.destroy');
});


// -------------------------------------------------------------------------
// SECTORES
// Subdivisión dentro de una zona.
// -------------------------------------------------------------------------

Route::prefix('sectors')->group(function () {

    Route::get('/', [SectorController::class, 'index'])
        ->middleware('permission:sectors.index');

    Route::get('/{sector_id}', [SectorController::class, 'show'])
        ->middleware('permission:sectors.show');

    // Historial de cambios de un sector
    Route::get('/{sector_id}/history', [SectorController::class, 'history'])
        ->middleware('permission:sectors.history');

    Route::post('/', [SectorController::class, 'store'])
        ->middleware('permission:sectors.store');

    Route::put('/{sector_id}', [SectorController::class, 'update'])
        ->middleware('permission:sectors.update');

    Route::patch('/{sector_id}', [SectorController::class, 'partialUpdate'])
        ->middleware('permission:sectors.partial-update');

    // Activar / desactivar un sector sin eliminarlo
    Route::patch('/status/{sector_id}', [SectorController::class, 'changeStatus'])
        ->middleware('permission:sectors.change-status');

    Route::delete('/{sector_id}', [SectorController::class, 'destroy'])
        ->middleware('permission:sectors.destroy');
});


// -------------------------------------------------------------------------
// DEPARTAMENTOS
// División político-administrativa (Colombia: departamentos).
// -------------------------------------------------------------------------

Route::prefix('departments')->group(function () {

    Route::get('/', [DepartmentController::class, 'index'])
        ->middleware('permission:departments.index');

    Route::get('/{department_id}', [DepartmentController::class, 'show'])
        ->middleware('permission:departments.show');

    // Historial de cambios de un departamento
    Route::get('/{department_id}/history', [DepartmentController::class, 'history'])
        ->middleware('permission:departments.history');

    Route::post('/', [DepartmentController::class, 'store'])
        ->middleware('permission:departments.store');

    Route::put('/{department_id}', [DepartmentController::class, 'update'])
        ->middleware('permission:departments.update');

    Route::patch('/{department_id}', [DepartmentController::class, 'partialUpdate'])
        ->middleware('permission:departments.partial-update');

    Route::delete('/{department_id}', [DepartmentController::class, 'destroy'])
        ->middleware('permission:departments.destroy');
});


// -------------------------------------------------------------------------
// CIUDADES
// Municipios o ciudades dentro de un departamento.
// -------------------------------------------------------------------------

Route::prefix('cities')->group(function () {

    Route::get('/', [CityController::class, 'index'])
        ->middleware('permission:cities.index');

    Route::get('/{city_id}', [CityController::class, 'show'])
        ->middleware('permission:cities.show');

    // Filtrar ciudades pertenecientes a un departamento específico
    Route::get('/department/{department_id}', [CityController::class, 'getbyDepartment'])
        ->middleware('permission:cities.by-department');

    // Historial de cambios de una ciudad
    Route::get('/{city_id}/history', [CityController::class, 'history'])
        ->middleware('permission:cities.history');

    Route::post('/', [CityController::class, 'store'])
        ->middleware('permission:cities.store');

    Route::put('/{city_id}', [CityController::class, 'update'])
        ->middleware('permission:cities.update');

    Route::patch('/{city_id}', [CityController::class, 'partialUpdate'])
        ->middleware('permission:cities.partial-update');

    Route::delete('/{city_id}', [CityController::class, 'destroy'])
        ->middleware('permission:cities.destroy');
});