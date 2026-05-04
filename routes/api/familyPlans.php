<?php

use App\Http\Controllers\API\FamilyPlan\FamilyPlanController;
use App\Http\Controllers\API\HousingInfo\HousingInfoController;
use App\Http\Controllers\API\HousingGraphic\HousingGraphicController;
use App\Http\Controllers\API\FamilyType\familyTypeController;
use App\Http\Controllers\housingInfoType\housingInfoTypeController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE PLANES FAMILIARES Y VIVIENDA (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de planes familiares, información de vivienda y gráficos
 * asociados a cada vivienda.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// PLANES FAMILIARES
// -------------------------------------------------------------------------

Route::prefix('familyPlans')->group(function () {

    Route::get('/', [FamilyPlanController::class, 'index'])
        ->middleware('permission:family-plans.index');

    // Filtrar planes familiares por estado
    Route::get('/by-status', [FamilyPlanController::class, 'byStatus'])
        ->middleware('permission:family-plans.by-status');

    // Obtener los planes del usuario autenticado
    Route::get('/by-user', [FamilyPlanController::class, 'byUser'])
        ->middleware('permission:family-plans.by-user');

    // Verificar si el usuario autenticado tiene acceso a un plan específico
    Route::get('/check-access/{id}', [FamilyPlanController::class, 'checkAccess'])
        ->middleware('permission:family-plans.check-access');

    // Verificar si un plan familiar ya tiene miembros registrados
    Route::get('/has-members/{id}', [FamilyPlanController::class, 'hasMembers'])
        ->middleware('permission:family-plans.has-members');

    // Validar que el plan familiar cumple todos los requisitos
    Route::get('/validate-requirements/{id}', [FamilyPlanController::class, 'validateRequirements'])
        ->middleware('permission:family-plans.validate-requirements');

    Route::get('/{id}', [FamilyPlanController::class, 'show'])
        ->middleware('permission:family-plans.show');

    // Descargar el PDF completo del plan familiar
    Route::get('/{id}/download-pdf', [FamilyPlanController::class, 'downloadPdf'])
        ->middleware('permission:family-plans.download-pdf');

    Route::post('/', [FamilyPlanController::class, 'store'])
        ->middleware('permission:family-plans.store');

    Route::put('/{id}', [FamilyPlanController::class, 'update'])
        ->middleware('permission:family-plans.update');

    Route::patch('/{id}', [FamilyPlanController::class, 'partialUpdate'])
        ->middleware('permission:family-plans.partial-update');

    // Identificar / etiquetar el plan familiar
    Route::patch('/{id}/identify', [FamilyPlanController::class, 'identify'])
        ->middleware('permission:family-plans.identify');

    // Cambiar el estado del plan familiar
    Route::patch('/{id}/change-status', [FamilyPlanController::class, 'changeStatus'])
        ->middleware('permission:family-plans.change-status');

    Route::patch('/{id}/change-family-type', [FamilyPlanController::class, 'patchFamilyType']);
        // ->middleware('permission:family-plans.change-family-type');

    Route::delete('/{id}', [FamilyPlanController::class, 'destroy'])
        ->middleware('permission:family-plans.destroy');

    
});

Route::prefix('familyTypes')->group(function(){
    Route::get('/', [familyTypeController::class, 'index'])
        ->middleware('permission:family-type.index');
    
    Route::post('/', [familyTypeController::class, 'store'])
        ->middleware('permission:family-type.store');

    Route::get('/{id}', [familyTypeController::class, 'show'])
        ->middleware('permission:family-type.show');

    Route::put('/{id}', [familyTypeController::class, 'update'])
        ->middleware('permission:family-type.update');

    Route::delete('/{id}', [familyTypeController::class, 'destroy'])
        ->middleware('permission:family-type.destroy');
});


// -------------------------------------------------------------------------
// INFORMACIÓN DE VIVIENDA
// Datos de la vivienda asociada a un plan familiar.
// -------------------------------------------------------------------------

Route::prefix('housingInfoTypes')->group(function(){
    Route::get('/', [housingInfoTypeController::class, 'index'])
        ->middleware('permission:housing-info-type.index');
    
    Route::post('/', [housingInfoTypeController::class, 'store'])
        ->middleware('permission:housing-info-type.store');

    Route::get('/{id}', [housingInfoTypeController::class, 'show'])
        ->middleware('permission:housing-info-type.show');

    Route::put('/{id}', [housingInfoTypeController::class, 'update'])
        ->middleware('permission:housing-info-type.update');

    Route::delete('/{id}', [housingInfoTypeController::class, 'destroy'])
        ->middleware('permission:housing-info-type.destroy');
});


Route::prefix('housingInfo')->group(function () {

    Route::get('/', [HousingInfoController::class, 'index'])
        ->middleware('permission:housing-info.index');

    Route::get('/{id}', [HousingInfoController::class, 'show'])
        ->middleware('permission:housing-info.show');

    Route::post('/', [HousingInfoController::class, 'store'])
        ->middleware('permission:housing-info.store');

    Route::delete('/{id}', [HousingInfoController::class, 'destroy'])
        ->middleware('permission:housing-info.destroy');

    
    Route::get('/{familyPlanId}/type/{typeId}', [HousingInfoController::class, 'getByType']);
    
    Route::delete('/{familyPlanId}/type/{typeId}', [HousingInfoController::class, 'destroyByType']);

    Route::post('/{familyPlanId}/type/{typeId}', [HousingInfoController::class, 'updateByType']);
});


// -------------------------------------------------------------------------
// GRÁFICOS DE VIVIENDA
// Imágenes o planos del croquis de la vivienda.
// -------------------------------------------------------------------------

Route::prefix('housingGraphics')->group(function () {

    Route::get('/', [HousingGraphicController::class, 'index'])
        ->middleware('permission:housing-graphics.index');

    Route::get('/{id}', [HousingGraphicController::class, 'show'])
        ->middleware('permission:housing-graphics.show');

    // Obtener los gráficos asociados a un plan familiar específico
    Route::get('/familyPlan/{family_plan_id}', [HousingGraphicController::class, 'getByFamilyPlan'])
        ->middleware('permission:housing-graphics.by-family-plan');

    Route::post('/', [HousingGraphicController::class, 'store'])
        ->middleware('permission:housing-graphics.store');

    // Actualizar únicamente la descripción del gráfico
    Route::patch('/{id}/description', [HousingGraphicController::class, 'updateDescription'])
        ->middleware('permission:housing-graphics.update-description');

    Route::delete('/{id}', [HousingGraphicController::class, 'destroy'])
        ->middleware('permission:housing-graphics.destroy');
});