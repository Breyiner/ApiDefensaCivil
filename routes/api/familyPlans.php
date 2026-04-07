<?php

use App\Http\Controllers\API\FamilyPlan\FamilyPlanController;
use App\Http\Controllers\API\HousingInfo\HousingInfoController;
use App\Http\Controllers\API\HousingQuality\HousingQualityController;
use App\Http\Controllers\API\HousingGraphic\HousingGraphicController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE PLANES FAMILIARES Y VIVIENDA (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de:
 * - Planes familiares
 * - Información de vivienda
 * - Calidades de vivienda
 * - Gráficos o croquis de vivienda
 *
 * Estas rutas requieren autenticación y email verificado.
 * Algunas rutas además exigen permisos específicos.
 */


// -------------------------------------------------------------------------
// PLANES FAMILIARES
// -------------------------------------------------------------------------

Route::prefix('familyPlans')->group(function () {

    Route::get('/', [FamilyPlanController::class, 'index']);

    // Ruta para filtrar por estado usando query param (?status=1)
    Route::get('/by-status', [FamilyPlanController::class, 'getByStatus']);

    Route::get('/byUser', [FamilyPlanController::class, 'getFamilyPlanByUser']);

    Route::get('/{familyPlan_id}', [FamilyPlanController::class, 'show'])
        ->middleware('permission:family-plans.show');

    Route::post('/', [FamilyPlanController::class, 'store'])
        ->middleware('permission:family-plans.store');

    Route::put('/{familyPlan_id}', [FamilyPlanController::class, 'update']);

    Route::patch('/{familyPlan_id}', [FamilyPlanController::class, 'partialUpdate']);

    Route::patch('/identify/{familyPlan_id}', [FamilyPlanController::class, 'identify'])
        ->middleware('permission:family-plans.identify');

    Route::patch('/status/{familyPlan_id}', [FamilyPlanController::class, 'changeStatus']);

    Route::delete('/{familyPlan_id}', [FamilyPlanController::class, 'destroy']);

    Route::get('/checkAccess/{familyPlan_id}', [FamilyPlanController::class, 'checkAccess']);

    Route::get('/pdf/{id}', [FamilyPlanController::class, 'downloadPdf']);

    Route::get('/{familyPlan_id}/has-members', [FamilyPlanController::class, 'hasMembers']);

    Route::get('/{familyPlan_id}/validate-requirements', [FamilyPlanController::class, 'validateRequirements']);
});


// -------------------------------------------------------------------------
// INFORMACIÓN DE VIVIENDA
// -------------------------------------------------------------------------

Route::prefix('housingInfo')->group(function () {

    Route::get('/', [HousingInfoController::class, 'index']);

    Route::get('/{housingInfo_id}', [HousingInfoController::class, 'show'])
        ->middleware('permission:housing-info.show');

    Route::post('/', [HousingInfoController::class, 'store'])
        ->middleware('permission:housing-info.store');

    Route::delete('/{housingInfo_id}', [HousingInfoController::class, 'destroy'])
        ->middleware('permission:housing-info.destroy');
});


// -------------------------------------------------------------------------
// CALIDADES DE VIVIENDA
// -------------------------------------------------------------------------

Route::prefix('housingQualities')->group(function () {

    Route::get('/', [HousingQualityController::class, 'index'])
        ->middleware('permission:housing-qualities.index');

    Route::get('/{housingQuality_id}', [HousingQualityController::class, 'show']);

    Route::get('/{housingQuality_id}/history', [HousingQualityController::class, 'history']);

    Route::post('/', [HousingQualityController::class, 'store']);

    Route::put('/{housingQuality_id}', [HousingQualityController::class, 'update']);

    Route::patch('/{housingQuality_id}', [HousingQualityController::class, 'partialUpdate']);

    Route::patch('/status/{housingQuality_id}', [HousingQualityController::class, 'changeStatus']);

    Route::delete('/{housingQuality_id}', [HousingQualityController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// GRÁFICOS DE VIVIENDA
// -------------------------------------------------------------------------

Route::prefix('housingGraphics')->group(function () {

    Route::get('/', [HousingGraphicController::class, 'index']);

    Route::get('/{id}', [HousingGraphicController::class, 'show']);

    Route::get('/familyPlan/{id}', [HousingGraphicController::class, 'getByFamilyPlan']);

    Route::post('/', [HousingGraphicController::class, 'store']);

    Route::patch('/description/{id}', [HousingGraphicController::class, 'updateDescription']);

    Route::delete('/{id}', [HousingGraphicController::class, 'destroy']);
});