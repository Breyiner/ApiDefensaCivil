<?php

use App\Http\Controllers\API\RiskFactor\RiskFactorController;
use App\Http\Controllers\API\RiskReductionAction\RiskReductionActionController;
use App\Http\Controllers\API\ThreatType\ThreatTypeController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE FACTORES DE RIESGO Y AMENAZAS (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de la evaluación de riesgos de los planes familiares:
 * tipos de amenaza, factores de riesgo y acciones de reducción.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// TIPOS DE AMENAZA
// Catálogo de categorías de amenaza (natural, antrópica, etc.).
// -------------------------------------------------------------------------

Route::prefix('threatTypes')->group(function () {

    Route::get('/', [ThreatTypeController::class, 'index']);

    Route::get('/{threatType_id}', [ThreatTypeController::class, 'show']);

    // Historial de cambios de un tipo de amenaza
    Route::get('/{threatType_id}/history', [ThreatTypeController::class, 'history']);

    Route::post('/', [ThreatTypeController::class, 'store']);

    Route::put('/{threatType_id}', [ThreatTypeController::class, 'update']);

    Route::patch('/{threatType_id}', [ThreatTypeController::class, 'partialUpdate']);

    // Activar / desactivar un tipo de amenaza sin eliminarlo
    Route::patch('/status/{threatType_id}', [ThreatTypeController::class, 'changeStatus']);

    Route::delete('/{threatType_id}', [ThreatTypeController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// FACTORES DE RIESGO
// Riesgos identificados y asociados a un plan familiar específico.
// -------------------------------------------------------------------------

Route::prefix('riskFactors')->group(function () {

    Route::get('/', [RiskFactorController::class, 'index']);

    Route::get('/{id}', [RiskFactorController::class, 'show']);

    // Obtener todos los factores de riesgo de un plan familiar
    Route::get('/familyPlan/{family_plan_id}', [RiskFactorController::class, 'getForPlan']);

    // Obtener factores de riesgo en formato simplificado para selectores
    Route::get('/familyPlan/select/{family_plan_id}', [RiskFactorController::class, 'getRiskFactorSelect']);

    Route::post('/', [RiskFactorController::class, 'store']);

    Route::put('/{id}', [RiskFactorController::class, 'update']);

    Route::patch('/{id}', [RiskFactorController::class, 'partialUpdate']);

    Route::delete('/{id}', [RiskFactorController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// ACCIONES DE REDUCCIÓN DE RIESGO
// Acciones propuestas para mitigar los factores de riesgo identificados.
// -------------------------------------------------------------------------

Route::prefix('riskReductionActions')->group(function () {

    Route::get('/', [RiskReductionActionController::class, 'index']);

    Route::get('/{id}', [RiskReductionActionController::class, 'show']);

    // Obtener acciones asociadas a un factor de riesgo específico
    Route::get('/riskFactor/{riskFactor_id}', [RiskReductionActionController::class, 'getByRiskFactor']);

    Route::post('/', [RiskReductionActionController::class, 'store']);

    Route::put('/{id}', [RiskReductionActionController::class, 'update']);

    Route::patch('/{id}', [RiskReductionActionController::class, 'partialUpdate']);

    Route::delete('/{id}', [RiskReductionActionController::class, 'destroy']);
});