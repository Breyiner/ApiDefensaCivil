<?php

use App\Http\Controllers\API\BloodGroup\BloodGroupController;
use App\Http\Controllers\API\Nationality\NationalityController;
use App\Http\Controllers\API\Kinship\KinshipController;
use App\Http\Controllers\API\Member\MemberController;
use App\Http\Controllers\API\FamilyMember\FamilyMemberController;
use App\Http\Controllers\API\ConditionType\ConditionTypeController;
use App\Http\Controllers\API\ConditionMember\ConditionMemberController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE MIEMBROS Y CONDICIONES (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de:
 * - Grupos sanguíneos
 * - Nacionalidades
 * - Parentescos
 * - Miembros del plan familiar
 * - Relaciones familiares
 * - Tipos de condición
 * - Condiciones de miembros
 *
 * Estas rutas requieren autenticación y email verificado.
 */


// -------------------------------------------------------------------------
// GRUPOS SANGUÍNEOS
// -------------------------------------------------------------------------

Route::prefix('bloodGroups')->group(function () {

    Route::get('/', [BloodGroupController::class, 'index']);

    Route::get('/{bloodGroup_id}', [BloodGroupController::class, 'show']);

    Route::post('/', [BloodGroupController::class, 'store']);

    Route::put('/{bloodGroup_id}', [BloodGroupController::class, 'update']);

    Route::delete('/{bloodGroup_id}', [BloodGroupController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// NACIONALIDADES
// -------------------------------------------------------------------------

Route::prefix('nationalities')->group(function () {

    Route::get('/', [NationalityController::class, 'index']);

    Route::get('/{nationality_id}', [NationalityController::class, 'show']);

    Route::get('/{nationality_id}/history', [NationalityController::class, 'history']);

    Route::post('/', [NationalityController::class, 'store']);

    Route::put('/{nationality_id}', [NationalityController::class, 'update']);

    Route::patch('/{nationality_id}', [NationalityController::class, 'partialUpdate']);

    Route::patch('/status/{nationality_id}', [NationalityController::class, 'changeStatus']);

    Route::delete('/{nationality_id}', [NationalityController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// PARENTESCOS
// -------------------------------------------------------------------------

Route::prefix('kinships')->group(function () {

    Route::get('/', [KinshipController::class, 'index']);

    Route::get('/{kinship_id}', [KinshipController::class, 'show']);

    Route::post('/', [KinshipController::class, 'store']);

    Route::put('/{kinship_id}', [KinshipController::class, 'update']);

    Route::delete('/{kinship_id}', [KinshipController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// MIEMBROS
// -------------------------------------------------------------------------

Route::prefix('members')->group(function () {

    Route::get('/', [MemberController::class, 'index']);

    Route::get('/{member_id}', [MemberController::class, 'show']);

    Route::get('/familyPlan/{plan_id}', [MemberController::class, 'getMembersForPlan']);

    Route::get('/familyPlan/select/{plan_id}', [MemberController::class, 'getMembersSelect']);

    Route::post('/{plan_id}', [MemberController::class, 'store']);

    Route::put('/{member_id}', [MemberController::class, 'update']);

    Route::patch('/{member_id}', [MemberController::class, 'partialUpdate']);

    Route::delete('/{member_id}', [MemberController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// RELACIONES FAMILIARES
// -------------------------------------------------------------------------

Route::prefix('familyMembers')->group(function () {

    Route::get('/', [FamilyMemberController::class, 'index']);

    Route::get('/{familyMember_id}', [FamilyMemberController::class, 'show']);

    Route::post('/', [FamilyMemberController::class, 'store']);

    Route::put('/{familyMember_id}', [FamilyMemberController::class, 'update']);

    Route::delete('/{familyMember_id}', [FamilyMemberController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// TIPOS DE CONDICIÓN
// -------------------------------------------------------------------------

Route::prefix('conditionTypes')->group(function () {

    Route::get('/', [ConditionTypeController::class, 'index']);

    Route::get('/{conditionType_id}', [ConditionTypeController::class, 'show']);

    Route::post('/', [ConditionTypeController::class, 'store']);

    Route::put('/{conditionType_id}', [ConditionTypeController::class, 'update']);

    Route::delete('/{conditionType_id}', [ConditionTypeController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// CONDICIONES DE MIEMBROS
// -------------------------------------------------------------------------

Route::prefix('conditionMembers')->group(function () {

    Route::get('/', [ConditionMemberController::class, 'index']);

    Route::get('/{conditionMember_id}', [ConditionMemberController::class, 'show']);

    Route::get('/member/{member_id}', [ConditionMemberController::class, 'getByMember']);

    Route::post('/', [ConditionMemberController::class, 'store']);

    Route::put('/{conditionMember_id}', [ConditionMemberController::class, 'update']);

    Route::patch('/{conditionMember_id}', [ConditionMemberController::class, 'partialUpdate']);

    Route::delete('/{conditionMember_id}', [ConditionMemberController::class, 'destroy']);
});