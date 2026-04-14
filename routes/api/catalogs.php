<?php

use App\Http\Controllers\API\StateUser\StateUserController;
use App\Http\Controllers\API\StatusPlan\StatusPlanController;
use App\Http\Controllers\API\Gender\GenderController;
use App\Http\Controllers\API\DocumentType\DocumentTypeController;
use App\Http\Controllers\API\BloodGroup\BloodGroupController;
use App\Http\Controllers\API\Nationality\NationalityController;
use App\Http\Controllers\API\Kinship\KinshipController;
use App\Http\Controllers\API\ConditionType\ConditionTypeController;
use App\Http\Controllers\API\HousingQuality\HousingQualityController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE CATÁLOGOS (AUTENTICADAS)
 * ============================================================================
 *
 * Tablas maestras y catálogos del sistema: estados de usuario y plan,
 * géneros, tipos de documento, grupos sanguíneos, nacionalidades,
 * parentescos, tipos de condición y calidades de vivienda.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 *
 * Permisos requeridos (Spatie): ver cada grupo de rutas.
 */


// -------------------------------------------------------------------------
// ESTADOS DE USUARIO
// Estados posibles de un usuario (activo, inactivo, pendiente, etc.).
// -------------------------------------------------------------------------

Route::prefix('stateUsers')->group(function () {

    Route::get('/', [StateUserController::class, 'index'])
        ->middleware('permission:state-users.index');

    Route::get('/{id}', [StateUserController::class, 'show'])
        ->middleware('permission:state-users.show');

    Route::post('/', [StateUserController::class, 'store'])
        ->middleware('permission:state-users.store');

    Route::put('/{id}', [StateUserController::class, 'update'])
        ->middleware('permission:state-users.update');

    Route::delete('/{id}', [StateUserController::class, 'destroy'])
        ->middleware('permission:state-users.destroy');
});


// -------------------------------------------------------------------------
// ESTADOS DE PLAN
// Estados posibles de un plan familiar (borrador, activo, cerrado, etc.).
// -------------------------------------------------------------------------

Route::prefix('statusPlans')->group(function () {

    Route::get('/', [StatusPlanController::class, 'index'])
        ->middleware('permission:status-plans.index');

    Route::get('/{id}', [StatusPlanController::class, 'show'])
        ->middleware('permission:status-plans.show');

    Route::post('/', [StatusPlanController::class, 'store'])
        ->middleware('permission:status-plans.store');

    Route::put('/{id}', [StatusPlanController::class, 'update'])
        ->middleware('permission:status-plans.update');

    Route::delete('/{id}', [StatusPlanController::class, 'destroy'])
        ->middleware('permission:status-plans.destroy');
});


// -------------------------------------------------------------------------
// GÉNEROS
// Catálogo de géneros de personas.
// -------------------------------------------------------------------------

Route::prefix('genders')->group(function () {

    Route::get('/', [GenderController::class, 'index'])
        ->middleware('permission:genders.index');

    Route::get('/{gender_id}', [GenderController::class, 'show'])
        ->middleware('permission:genders.show');

    // Historial de cambios de un género
    Route::get('/{gender_id}/history', [GenderController::class, 'history'])
        ->middleware('permission:genders.history');

    Route::post('/', [GenderController::class, 'store'])
        ->middleware('permission:genders.store');

    Route::put('/{gender_id}', [GenderController::class, 'update'])
        ->middleware('permission:genders.update');

    Route::patch('/{gender_id}', [GenderController::class, 'partialUpdate'])
        ->middleware('permission:genders.partial-update');

    // Activar / desactivar un género sin eliminarlo
    Route::patch('/status/{gender_id}', [GenderController::class, 'changeStatus'])
        ->middleware('permission:genders.change-status');

    Route::delete('/{gender_id}', [GenderController::class, 'destroy'])
        ->middleware('permission:genders.destroy');
});


// -------------------------------------------------------------------------
// TIPOS DE DOCUMENTO
// Catálogo de tipos de documento de identidad (CC, TI, CE, etc.).
// -------------------------------------------------------------------------

Route::prefix('documentTypes')->group(function () {

    Route::get('/', [DocumentTypeController::class, 'index'])
        ->middleware('permission:document-types.index');

    Route::get('/{documentType_id}', [DocumentTypeController::class, 'show'])
        ->middleware('permission:document-types.show');

    // Historial de cambios de un tipo de documento
    Route::get('/{documentType_id}/history', [DocumentTypeController::class, 'history'])
        ->middleware('permission:document-types.history');

    Route::post('/', [DocumentTypeController::class, 'store'])
        ->middleware('permission:document-types.store');

    Route::put('/{documentType_id}', [DocumentTypeController::class, 'update'])
        ->middleware('permission:document-types.update');

    Route::patch('/{documentType_id}', [DocumentTypeController::class, 'partialUpdate'])
        ->middleware('permission:document-types.partial-update');

    // Activar / desactivar un tipo de documento sin eliminarlo
    Route::patch('/status/{documentType_id}', [DocumentTypeController::class, 'changeStatus'])
        ->middleware('permission:document-types.change-status');

    Route::delete('/{documentType_id}', [DocumentTypeController::class, 'destroy'])
        ->middleware('permission:document-types.destroy');
});


// -------------------------------------------------------------------------
// GRUPOS SANGUÍNEOS
// Catálogo de grupos sanguíneos (A+, O-, etc.).
// -------------------------------------------------------------------------

Route::prefix('bloodGroups')->group(function () {

    Route::get('/', [BloodGroupController::class, 'index'])
        ->middleware('permission:blood-groups.index');

    Route::get('/{id}', [BloodGroupController::class, 'show'])
        ->middleware('permission:blood-groups.show');

    Route::post('/', [BloodGroupController::class, 'store'])
        ->middleware('permission:blood-groups.store');

    Route::put('/{id}', [BloodGroupController::class, 'update'])
        ->middleware('permission:blood-groups.update');

    Route::delete('/{id}', [BloodGroupController::class, 'destroy'])
        ->middleware('permission:blood-groups.destroy');
});


// -------------------------------------------------------------------------
// NACIONALIDADES
// Catálogo de nacionalidades de los miembros familiares.
// -------------------------------------------------------------------------

Route::prefix('nationalities')->group(function () {

    Route::get('/', [NationalityController::class, 'index'])
        ->middleware('permission:nationalities.index');

    Route::get('/{nationality_id}', [NationalityController::class, 'show'])
        ->middleware('permission:nationalities.show');

    // Historial de cambios de una nacionalidad
    Route::get('/{nationality_id}/history', [NationalityController::class, 'history'])
        ->middleware('permission:nationalities.history');

    Route::post('/', [NationalityController::class, 'store'])
        ->middleware('permission:nationalities.store');

    Route::put('/{nationality_id}', [NationalityController::class, 'update'])
        ->middleware('permission:nationalities.update');

    Route::patch('/{nationality_id}', [NationalityController::class, 'partialUpdate'])
        ->middleware('permission:nationalities.partial-update');

    // Activar / desactivar una nacionalidad sin eliminarla
    Route::patch('/status/{nationality_id}', [NationalityController::class, 'changeStatus'])
        ->middleware('permission:nationalities.change-status');

    Route::delete('/{nationality_id}', [NationalityController::class, 'destroy'])
        ->middleware('permission:nationalities.destroy');
});


// -------------------------------------------------------------------------
// PARENTESCOS
// Catálogo de tipos de parentesco (padre, madre, hijo, hermano, etc.).
// -------------------------------------------------------------------------

Route::prefix('kinships')->group(function () {

    Route::get('/', [KinshipController::class, 'index'])
        ->middleware('permission:kinships.index');

    Route::get('/{id}', [KinshipController::class, 'show'])
        ->middleware('permission:kinships.show');

    Route::post('/', [KinshipController::class, 'store'])
        ->middleware('permission:kinships.store');

    Route::put('/{id}', [KinshipController::class, 'update'])
        ->middleware('permission:kinships.update');

    Route::delete('/{id}', [KinshipController::class, 'destroy'])
        ->middleware('permission:kinships.destroy');
});


// -------------------------------------------------------------------------
// TIPOS DE CONDICIÓN
// Categorías de condición especial de un miembro (discapacidad, enfermedad, etc.).
// -------------------------------------------------------------------------

Route::prefix('conditionTypes')->group(function () {

    Route::get('/', [ConditionTypeController::class, 'index'])
        ->middleware('permission:condition-types.index');

    Route::get('/{id}', [ConditionTypeController::class, 'show'])
        ->middleware('permission:condition-types.show');

    Route::post('/', [ConditionTypeController::class, 'store'])
        ->middleware('permission:condition-types.store');

    Route::put('/{id}', [ConditionTypeController::class, 'update'])
        ->middleware('permission:condition-types.update');

    Route::delete('/{id}', [ConditionTypeController::class, 'destroy'])
        ->middleware('permission:condition-types.destroy');
});


// -------------------------------------------------------------------------
// CALIDADES DE VIVIENDA
// Catálogo del tipo/calidad de la vivienda (propia, arrendada, etc.).
// -------------------------------------------------------------------------

Route::prefix('housingQualities')->group(function () {

    Route::get('/', [HousingQualityController::class, 'index'])
        ->middleware('permission:housing-qualities.index');

    Route::get('/{housingQuality_id}', [HousingQualityController::class, 'show'])
        ->middleware('permission:housing-qualities.show');

    // Historial de cambios de una calidad de vivienda
    Route::get('/{housingQuality_id}/history', [HousingQualityController::class, 'history'])
        ->middleware('permission:housing-qualities.history');

    Route::post('/', [HousingQualityController::class, 'store'])
        ->middleware('permission:housing-qualities.store');

    Route::put('/{housingQuality_id}', [HousingQualityController::class, 'update'])
        ->middleware('permission:housing-qualities.update');

    Route::patch('/{housingQuality_id}', [HousingQualityController::class, 'partialUpdate'])
        ->middleware('permission:housing-qualities.partial-update');

    // Activar / desactivar una calidad de vivienda sin eliminarla
    Route::patch('/status/{housingQuality_id}', [HousingQualityController::class, 'changeStatus'])
        ->middleware('permission:housing-qualities.change-status');

    Route::delete('/{housingQuality_id}', [HousingQualityController::class, 'destroy'])
        ->middleware('permission:housing-qualities.destroy');
});