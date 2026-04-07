<?php

use App\Http\Controllers\API\Zone\ZoneController;
use App\Http\Controllers\API\Sector\SectorController;
use App\Http\Controllers\API\Department\DepartmentController;
use App\Http\Controllers\API\City\CityController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS GEOGRÁFICAS (AUTENTICADAS)
 * ============================================================================
 *
 * Catálogos y entidades geográficas del sistema:
 * - Zonas
 * - Sectores
 * - Departamentos
 * - Ciudades
 *
 * Estas rutas requieren autenticación y email verificado.
 * Algunas rutas además exigen permisos específicos.
 */


// -------------------------------------------------------------------------
// ZONAS
// -------------------------------------------------------------------------

Route::prefix('zones')->group(function () {

    Route::get('/', [ZoneController::class, 'index'])
        ->middleware('permission:zones.index');

    Route::get('/{zone_id}', [ZoneController::class, 'show']);

    Route::post('/', [ZoneController::class, 'store']);

    Route::put('/{zone_id}', [ZoneController::class, 'update']);

    Route::delete('/{zone_id}', [ZoneController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// SECTORES
// -------------------------------------------------------------------------

Route::prefix('sectors')->group(function () {

    Route::get('/', [SectorController::class, 'index'])
        ->middleware('permission:sectors.index');

    Route::get('/{sector_id}', [SectorController::class, 'show']);

    Route::get('/{sector_id}/history', [SectorController::class, 'history']);

    Route::post('/', [SectorController::class, 'store']);

    Route::put('/{sector_id}', [SectorController::class, 'update']);

    Route::patch('/{sector_id}', [SectorController::class, 'partialUpdate']);

    Route::patch('/status/{sector_id}', [SectorController::class, 'changeStatus']);

    Route::delete('/{sector_id}', [SectorController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// DEPARTAMENTOS
// -------------------------------------------------------------------------

Route::prefix('departments')->group(function () {

    Route::get('/', [DepartmentController::class, 'index'])
        ->middleware('permission:departments.index');

    Route::get('/{department_id}', [DepartmentController::class, 'show']);

    Route::get('/{department_id}/history', [DepartmentController::class, 'history']);

    Route::post('/', [DepartmentController::class, 'store']);

    Route::put('/{department_id}', [DepartmentController::class, 'update']);

    Route::patch('/{department_id}', [DepartmentController::class, 'partialUpdate']);

    Route::delete('/{department_id}', [DepartmentController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// CIUDADES
// -------------------------------------------------------------------------

Route::prefix('cities')->group(function () {

    Route::get('/', [CityController::class, 'index']);

    Route::get('/{city_id}', [CityController::class, 'show']);

    Route::get('/department/{department_id}', [CityController::class, 'getByDepartment'])
        ->middleware('permission:cities.departments');

    Route::post('/', [CityController::class, 'store']);

    Route::put('/{city_id}', [CityController::class, 'update']);

    Route::patch('/{city_id}', [CityController::class, 'partialUpdate']);

    Route::delete('/{city_id}', [CityController::class, 'destroy']);

    Route::get('/{city_id}/history', [CityController::class, 'history']);
});