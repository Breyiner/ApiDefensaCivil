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
// Catálogo de especies animales (perro, gato, etc.).
// -------------------------------------------------------------------------

Route::prefix('species')->group(function () {

    Route::get('/', [SpeciesController::class, 'index']);

    Route::get('/{specie_id}', [SpeciesController::class, 'show']);

    // Historial de cambios de una especie
    Route::get('/{specie_id}/history', [SpeciesController::class, 'history']);

    Route::post('/', [SpeciesController::class, 'store']);

    Route::put('/{specie_id}', [SpeciesController::class, 'update']);

    Route::patch('/{specie_id}', [SpeciesController::class, 'partialUpdate']);

    // Activar / desactivar una especie sin eliminarla
    Route::patch('/status/{specie_id}', [SpeciesController::class, 'changeStatus']);

    Route::delete('/{specie_id}', [SpeciesController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// GÉNEROS ANIMALES
// Catálogo de géneros para las mascotas (macho, hembra).
// -------------------------------------------------------------------------

Route::prefix('animalGenders')->group(function () {

    Route::get('/', [AnimalGenderController::class, 'index']);

    Route::get('/{id}', [AnimalGenderController::class, 'show']);

    Route::post('/', [AnimalGenderController::class, 'store']);

    Route::put('/{id}', [AnimalGenderController::class, 'update']);

    Route::delete('/{id}', [AnimalGenderController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// MASCOTAS
// Mascotas registradas dentro de un plan familiar.
// -------------------------------------------------------------------------

Route::prefix('pets')->group(function () {

    Route::get('/', [PetController::class, 'index']);

    Route::get('/{id}', [PetController::class, 'show']);

    // Obtener mascotas pertenecientes a un plan familiar específico
    Route::get('/familyPlan/{plan_id}', [PetController::class, 'getPetsForPlan']);

    Route::post('/', [PetController::class, 'store']);

    Route::put('/{id}', [PetController::class, 'update']);

    Route::patch('/{id}', [PetController::class, 'partialUpdate']);

    Route::delete('/{id}', [PetController::class, 'destroy']);
});


// -------------------------------------------------------------------------
// VACUNAS DE MASCOTAS
// Registro de vacunas aplicadas a cada mascota.
// -------------------------------------------------------------------------

Route::prefix('petVaccines')->group(function () {

    Route::get('/', [PetVaccineController::class, 'index']);

    Route::get('/{id}', [PetVaccineController::class, 'show']);

    // Obtener todas las vacunas registradas para una mascota específica
    Route::get('/pet/{pet_id}', [PetVaccineController::class, 'getVaccinesForPets']);

    Route::post('/', [PetVaccineController::class, 'store']);

    Route::put('/{id}', [PetVaccineController::class, 'update']);

    Route::patch('/{id}', [PetVaccineController::class, 'partialUpdate']);

    Route::delete('/{id}', [PetVaccineController::class, 'destroy']);
});