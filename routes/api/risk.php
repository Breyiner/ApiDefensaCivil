<?php

use App\Http\Controllers\API\RiskFactor\RiskFactorController;
use App\Http\Controllers\API\RiskReductionAction\RiskReductionActionController;
use App\Http\Controllers\API\ThreatType\ThreatTypeController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE RIESGOS Y AMENAZAS (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de la evaluación de riesgos: tipos de amenaza,
 * factores de riesgo y acciones de reducción.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// TIPOS DE AMENAZA
// -------------------------------------------------------------------------

Route::prefix('threatTypes')->group(function () {

    Route::get('/', [ThreatTypeController::class, 'index'])
        ->middleware('permission:threat-types.index');

    Route::get('/{threatType_id}', [ThreatTypeController::class, 'show'])
        ->middleware('permission:threat-types.show');

    // Historial de cambios de un tipo de amenaza
    Route::get('/{threatType_id}/history', [ThreatTypeController::class, 'history'])
        ->middleware('permission:threat-types.history');

    Route::post('/', [ThreatTypeController::class, 'store'])
        ->middleware('permission:threat-types.store');

    Route::put('/{threatType_id}', [ThreatTypeController::class, 'update'])
        ->middleware('permission:threat-types.update');

    Route::patch('/{threatType_id}', [ThreatTypeController::class, 'partialUpdate'])
        ->middleware('permission:threat-types.partial-update');

    // Activar / desactivar un tipo de amenaza sin eliminarlo
    Route::patch('/status/{threatType_id}', [ThreatTypeController::class, 'changeStatus'])
        ->middleware('permission:threat-types.change-status');

    Route::delete('/{threatType_id}', [ThreatTypeController::class, 'destroy'])
        ->middleware('permission:threat-types.destroy');
});


// -------------------------------------------------------------------------
// FACTORES DE RIESGO
// -------------------------------------------------------------------------

Route::prefix('riskFactors')->group(function () {

    Route::get('/', [RiskFactorController::class, 'index'])
        ->middleware('permission:risk-factors.index');

    Route::get('/{id}', [RiskFactorController::class, 'show'])
        ->middleware('permission:risk-factors.show');

    // Obtener todos los factores de riesgo de un plan familiar
    Route::get('/familyPlan/{family_plan_id}', [RiskFactorController::class, 'getForPlan'])
        ->middleware('permission:risk-factors.by-family-plan');

    // Obtener factores de riesgo en formato simplificado para selectores
    Route::get('/familyPlan/select/{family_plan_id}', [RiskFactorController::class, 'getRiskFactorSelect'])
        ->middleware('permission:risk-factors.select-by-family-plan');

    Route::post('/', [RiskFactorController::class, 'store'])
        ->middleware('permission:risk-factors.store');

    Route::put('/{id}', [RiskFactorController::class, 'update'])
        ->middleware('permission:risk-factors.update');

    Route::patch('/{id}', [RiskFactorController::class, 'partialUpdate'])
        ->middleware('permission:risk-factors.partial-update');

    Route::delete('/{id}', [RiskFactorController::class, 'destroy'])
        ->middleware('permission:risk-factors.destroy');
});


// -------------------------------------------------------------------------
// ACCIONES DE REDUCCIÓN DE RIESGO
// -------------------------------------------------------------------------

Route::prefix('riskReductionActions')->group(function () {

    Route::get('/', [RiskReductionActionController::class, 'index'])
        ->middleware('permission:risk-reduction-actions.index');

    Route::get('/{id}', [RiskReductionActionController::class, 'show'])
        ->middleware('permission:risk-reduction-actions.show');

    // Obtener acciones asociadas a un factor de riesgo específico
    Route::get('/riskFactor/{riskFactor_id}', [RiskReductionActionController::class, 'getByRiskFactor'])
        ->middleware('permission:risk-reduction-actions.by-risk-factor');

    Route::post('/', [RiskReductionActionController::class, 'store'])
        ->middleware('permission:risk-reduction-actions.store');

    Route::put('/{id}', [RiskReductionActionController::class, 'update'])
        ->middleware('permission:risk-reduction-actions.update');

    Route::patch('/{id}', [RiskReductionActionController::class, 'partialUpdate'])
        ->middleware('permission:risk-reduction-actions.partial-update');

    Route::delete('/{id}', [RiskReductionActionController::class, 'destroy'])
        ->middleware('permission:risk-reduction-actions.destroy');
});