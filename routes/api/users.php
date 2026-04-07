<?php

use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Profile\ProfileController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE USUARIOS Y PERFILES (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión completa de usuarios del sistema y sus perfiles asociados:
 * listado, filtros, historial, operaciones CRUD, cambios de rol/estado
 * y acciones masivas.
 *
 * Prefijo base: /api
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 *
 * Permisos requeridos (Spatie):
 * - users.*    → gestión de usuarios
 * - profiles.* → gestión de perfiles
 */


// -------------------------------------------------------------------------
// USUARIOS
// -------------------------------------------------------------------------

Route::prefix('users')->group(function () {

    // Listar todos los usuarios del sistema
    Route::get('/', [UserController::class, 'index'])
        ->middleware('permission:users.index');

    // Filtrar usuarios según su estado (activo, inactivo, pendiente, etc.)
    Route::get('/by-status', [UserController::class, 'byStatus'])
        ->middleware('permission:users.by-status');

    // Ver peticiones de acceso pendientes visibles para el rol Administrador
    Route::get('/requests/admins', [UserController::class, 'requestsAdmins'])
        ->middleware('permission:users.requests-admins');

    // Ver peticiones de acceso pendientes visibles para el rol Supervisor
    Route::get('/requests/supervisors', [UserController::class, 'requestsSupervisors'])
        ->middleware('permission:users.requests-supervisors');

    // Ver el detalle de un usuario específico
    Route::get('/{id}', [UserController::class, 'show'])
        ->middleware('permission:users.show');

    // Ver el historial de cambios de un usuario
    Route::get('/{id}/history', [UserController::class, 'history'])
        ->middleware('permission:users.history');

    // Crear un nuevo usuario en el sistema
    Route::post('/', [UserController::class, 'store'])
        ->middleware('permission:users.store');

    // Actualizar completamente un usuario
    Route::put('/{id}', [UserController::class, 'update'])
        ->middleware('permission:users.update');

    // Actualizar parcialmente un usuario
    Route::patch('/{id}', [UserController::class, 'partialUpdate'])
        ->middleware('permission:users.partial-update');

    // Eliminar un usuario del sistema
    Route::delete('/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:users.destroy');

    // -----------------------------------------------------------------------
    // ACCIONES ESPECIALES DE USUARIO
    // -----------------------------------------------------------------------

    // Cambiar el rol asignado a un usuario
    Route::patch('/{id}/change-role', [UserController::class, 'changeRole'])
        ->middleware('permission:users.change-role');

    // Cambiar el estado de un usuario de forma individual
    Route::patch('/{id}/change-status', [UserController::class, 'changeStatus'])
        ->middleware('permission:users.change-status');

    // -----------------------------------------------------------------------
    // ACCIONES MASIVAS (BULK)
    // -----------------------------------------------------------------------

    // Aprobar múltiples peticiones de acceso en un solo request
    Route::post('/approve', [UserController::class, 'approveBulk'])
        ->middleware('permission:users.approve-bulk');

    // Cambiar el estado de múltiples usuarios simultáneamente
    Route::patch('/change-status', [UserController::class, 'changeStatusBulk'])
        ->middleware('permission:users.change-status-bulk');

    // Rechazar y eliminar múltiples peticiones de acceso a la vez
    Route::delete('/reject-delete', [UserController::class, 'rejectDeleteBulk'])
        ->middleware('permission:users.reject-delete-bulk');
});


// -------------------------------------------------------------------------
// PERFILES
// -------------------------------------------------------------------------

Route::prefix('profiles')->group(function () {

    // Listar todos los perfiles registrados
    Route::get('/', [ProfileController::class, 'index'])
        ->middleware('permission:profiles.index');

    // Ver el detalle de un perfil específico
    Route::get('/{id}', [ProfileController::class, 'show'])
        ->middleware('permission:profiles.show');

    // Crear un nuevo perfil
    Route::post('/', [ProfileController::class, 'store'])
        ->middleware('permission:profiles.store');

    // Actualizar completamente un perfil
    Route::put('/{id}', [ProfileController::class, 'update'])
        ->middleware('permission:profiles.update');

    // Actualizar parcialmente un perfil
    Route::patch('/{id}', [ProfileController::class, 'partialUpdate'])
        ->middleware('permission:profiles.partial-update');

    // Eliminar un perfil del sistema
    Route::delete('/{id}', [ProfileController::class, 'destroy'])
        ->middleware('permission:profiles.destroy');
});