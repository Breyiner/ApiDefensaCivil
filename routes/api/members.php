<?php

use App\Http\Controllers\API\Member\MemberController;
use App\Http\Controllers\API\FamilyMember\FamilyMemberController;
use App\Http\Controllers\API\ConditionMember\ConditionMemberController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE MIEMBROS Y RELACIONES FAMILIARES (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de los miembros de un plan familiar, sus relaciones
 * y condiciones especiales.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// MIEMBROS
// Personas registradas dentro de un plan familiar.
// -------------------------------------------------------------------------

Route::prefix('members')->group(function () {

    Route::get('/', [MemberController::class, 'index'])
        ->middleware('permission:members.index');

    Route::get('/{id}', [MemberController::class, 'show'])
        ->middleware('permission:members.show');

    // Obtener los miembros de un plan familiar específico
    Route::get('/familyPlan/{family_plan_id}', [MemberController::class, 'getMembersForPlan'])
        ->middleware('permission:members.by-family-plan');

    // Obtener miembros en formato simplificado para selectores / dropdowns
    Route::get('/familyPlan/select/{family_plan_id}', [MemberController::class, 'getMembersSelect'])
        ->middleware('permission:members.select-by-family-plan');

    Route::post('/{plan_id}', [MemberController::class, 'store'])
        ->middleware('permission:members.store');

    Route::post('/', [MemberController::class, 'store'])
        ->middleware('permission:members.store');

    Route::put('/{id}', [MemberController::class, 'update'])
        ->middleware('permission:members.update');

    Route::patch('/{id}', [MemberController::class, 'partialUpdate'])
        ->middleware('permission:members.partial-update');

    Route::delete('/{id}', [MemberController::class, 'destroy'])
        ->middleware('permission:members.destroy');
});


// -------------------------------------------------------------------------
// RELACIONES FAMILIARES
// Vínculo entre dos miembros dentro del plan familiar.
// -------------------------------------------------------------------------

Route::prefix('familyMembers')->group(function () {

    Route::get('/', [FamilyMemberController::class, 'index'])
        ->middleware('permission:family-members.index');

    Route::get('/{id}', [FamilyMemberController::class, 'show'])
        ->middleware('permission:family-members.show');

    Route::post('/', [FamilyMemberController::class, 'store'])
        ->middleware('permission:family-members.store');

    Route::put('/{id}', [FamilyMemberController::class, 'update'])
        ->middleware('permission:family-members.update');

    Route::delete('/{id}', [FamilyMemberController::class, 'destroy'])
        ->middleware('permission:family-members.destroy');
});


// -------------------------------------------------------------------------
// CONDICIONES DE MIEMBROS
// Condiciones especiales asociadas a un miembro (discapacidades, enfermedades, etc.).
// -------------------------------------------------------------------------

Route::prefix('conditionMembers')->group(function () {

    Route::get('/', [ConditionMemberController::class, 'index'])
        ->middleware('permission:condition-members.index');

    Route::get('/{id}', [ConditionMemberController::class, 'show'])
        ->middleware('permission:condition-members.show');

    // Obtener todas las condiciones registradas para un miembro específico
    Route::get('/member/{member_id}', [ConditionMemberController::class, 'getByMember'])
        ->middleware('permission:condition-members.by-member');

    Route::post('/', [ConditionMemberController::class, 'store'])
        ->middleware('permission:condition-members.store');

    Route::put('/{id}', [ConditionMemberController::class, 'update'])
        ->middleware('permission:condition-members.update');

    Route::patch('/{id}', [ConditionMemberController::class, 'partialUpdate'])
        ->middleware('permission:condition-members.partial-update');

    Route::delete('/{id}', [ConditionMemberController::class, 'destroy'])
        ->middleware('permission:condition-members.destroy');
});