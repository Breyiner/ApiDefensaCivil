<?php

use App\Http\Controllers\API\DocumentType\DocumentTypeController;
use App\Http\Controllers\API\Gender\GenderController;
use App\Http\Controllers\API\Sectional\SectionalController;
use App\Http\Controllers\API\Organization\OrganizationController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * CATÁLOGOS PÚBLICOS
 * ============================================================================
 *
 * Endpoints que NO requieren autenticación.
 * Están pensados para formularios públicos, como registro de usuario
 * y consultas iniciales antes de iniciar sesión.
 *
 * Estas rutas se cargan desde bootstrap/app.php dentro del grupo público,
 * por lo que solo usan el middleware global de API.
 */


Route::prefix('public')->group(function () {

    // ---------------------------------------------------------------------
    // TIPOS DE DOCUMENTO
    // Listado público de tipos de documento disponibles.
    // Ejemplo: CC, TI, CE, Pasaporte, etc.
    // ---------------------------------------------------------------------
    Route::get('/document-types', [DocumentTypeController::class, 'index']);

    // ---------------------------------------------------------------------
    // GÉNEROS
    // Listado público de géneros disponibles para formularios.
    // ---------------------------------------------------------------------
    Route::get('/genders', [GenderController::class, 'index']);

    // ---------------------------------------------------------------------
    // SECCIONALES
    // Devuelve las seccionales activas junto con sus organizaciones,
    // útil para selects dependientes en formularios públicos.
    // ---------------------------------------------------------------------
    Route::get('/sectionals', [SectionalController::class, 'getActiveWithOrganization']);

    // ---------------------------------------------------------------------
    // ORGANIZACIONES POR SECCIONAL
    // Permite consultar las organizaciones asociadas a una seccional
    // específica sin necesidad de autenticación.
    // ---------------------------------------------------------------------
    Route::get('/organizations/sectional/{sectional_id}', [OrganizationController::class, 'getSectional']);
});