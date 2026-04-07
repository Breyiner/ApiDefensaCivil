<?php

use App\Http\Controllers\API\Species\SpeciesController;
use App\Http\Controllers\API\AnimalGender\AnimalGenderController;
use App\Http\Controllers\API\Pet\PetController;
use App\Http\Controllers\API\PetVaccine\PetVaccineController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE MASCOTAS (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de las mascotas registradas en los planes familiares:
 * especies, géneros animales, mascotas y sus vacunas.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */


// -------------------------------------------------------------------------
// ESPECIES
// -------------------------------------------------------------------------

Route::prefix('species')->group(function () {

    Route::get('/', [SpeciesController::class, 'index'])
        ->middleware('permission:species.index');

    Route::get('/{specie_id}', [SpeciesController::class, 'show'])
        ->middleware('permission:species.show');

    // Historial de cambios de una especie
    Route::get('/{specie_id}/history', [SpeciesController::class, 'history'])
        ->middleware('permission:species.history');

    Route::post('/', [SpeciesController::class, 'store'])
        ->middleware('permission:species.store');

    Route::put('/{specie_id}', [SpeciesController::class, 'update'])
        ->middleware('permission:species.update');

    Route::patch('/{specie_id}', [SpeciesController::class, 'partialUpdate'])
        ->middleware('permission:species.partial-update');

    // Activar / desactivar una especie sin eliminarla
    Route::patch('/status/{specie_id}', [SpeciesController::class, 'changeStatus'])
        ->middleware('permission:species.change-status');

    Route::delete('/{specie_id}', [SpeciesController::class, 'destroy'])
        ->middleware('permission:species.destroy');
});


// -------------------------------------------------------------------------
// GÉNEROS ANIMALES
// -------------------------------------------------------------------------

Route::prefix('animalGenders')->group(function () {

    Route::get('/', [AnimalGenderController::class, 'index'])
        ->middleware('permission:animal-genders.index');

    Route::get('/{id}', [AnimalGenderController::class, 'show'])
        ->middleware('permission:animal-genders.show');

    Route::post('/', [AnimalGenderController::class, 'store'])
        ->middleware('permission:animal-genders.store');

    Route::put('/{id}', [AnimalGenderController::class, 'update'])
        ->middleware('permission:animal-genders.update');

    Route::delete('/{id}', [AnimalGenderController::class, 'destroy'])
        ->middleware('permission:animal-genders.destroy');
});


// -------------------------------------------------------------------------
// MASCOTAS
// -------------------------------------------------------------------------

Route::prefix('pets')->group(function () {

    Route::get('/', [PetController::class, 'index'])
        ->middleware('permission:pets.index');

    Route::get('/{id}', [PetController::class, 'show'])
        ->middleware('permission:pets.show');

    // Obtener mascotas pertenecientes a un plan familiar específico
    Route::get('/familyPlan/{plan_id}', [PetController::class, 'getPetsForPlan'])
        ->middleware('permission:pets.by-family-plan');

    Route::post('/', [PetController::class, 'store'])
        ->middleware('permission:pets.store');

    Route::put('/{id}', [PetController::class, 'update'])
        ->middleware('permission:pets.update');

    Route::patch('/{id}', [PetController::class, 'partialUpdate'])
        ->middleware('permission:pets.partial-update');

    Route::delete('/{id}', [PetController::class, 'destroy'])
        ->middleware('permission:pets.destroy');
});


// -------------------------------------------------------------------------
// VACUNAS DE MASCOTAS
// -------------------------------------------------------------------------

Route::prefix('petVaccines')->group(function () {

    Route::get('/', [PetVaccineController::class, 'index'])
        ->middleware('permission:pet-vaccines.index');

    Route::get('/{id}', [PetVaccineController::class, 'show'])
        ->middleware('permission:pet-vaccines.show');

    // Obtener todas las vacunas registradas para una mascota específica
    Route::get('/pet/{pet_id}', [PetVaccineController::class, 'getVaccinesForPets'])
        ->middleware('permission:pet-vaccines.by-pet');

    Route::post('/', [PetVaccineController::class, 'store'])
        ->middleware('permission:pet-vaccines.store');

    Route::put('/{id}', [PetVaccineController::class, 'update'])
        ->middleware('permission:pet-vaccines.update');

    Route::patch('/{id}', [PetVaccineController::class, 'partialUpdate'])
        ->middleware('permission:pet-vaccines.partial-update');

    Route::delete('/{id}', [PetVaccineController::class, 'destroy'])
        ->middleware('permission:pet-vaccines.destroy');
});