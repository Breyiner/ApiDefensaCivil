<?php

use App\Http\Controllers\API\Profile\ProfileController;
use App\Http\Controllers\API\User\UserController;
use Illuminate\Support\Facades\Route;

/**
 * ============================================================================
 * RUTAS DE GESTIÓN DE USUARIOS
 * ============================================================================
 * 
 * Middleware aplicado automáticamente desde bootstrap/app.php:
 * - auth:sanctum (autenticación requerida)
 * - verified (email verificado)
 * 
 * Middleware adicional por ruta:
 * - permission: Permisos específicos de Spatie
 * - role: Roles específicos
 */

Route::prefix('users')->group(function () {

    // -------------------------------------------------------------------------
    // LISTADOS Y CONSULTAS
    // -------------------------------------------------------------------------
    
    Route::get('/', [UserController::class, 'index']);
        // ->middleware('permission:users.index');

    Route::get('/by-status', [UserController::class, 'getByStatus']);
        // ->middleware('permission:users.index');

    Route::get('/requestsAdmins', [UserController::class, 'getRequestsAdmins']);
        // ->middleware('role:admin');

    Route::get('/requestsSupervisors', [UserController::class, 'getRequestsSupervisors']);
        // ->middleware('role:supervisor|admin');

    Route::get('/{user_id}', [UserController::class, 'show']);
        // ->middleware('permission:users.show');

    Route::get('/{user_id}/history', [UserController::class, 'history']);
        // ->middleware('permission:users.history');

    // -------------------------------------------------------------------------
    // CRUD BÁSICO
    // -------------------------------------------------------------------------

    Route::post('/', [UserController::class, 'store']);
        // ->middleware('permission:users.create');

    Route::put('/{user_id}', [UserController::class, 'update']);
        // ->middleware('permission:users.update');

    Route::patch('/{user_id}', [UserController::class, 'partialUpdate']);
        // ->middleware('permission:users.update');

    Route::delete('/{user_id}', [UserController::class, 'destroy']);
        // ->middleware('permission:users.delete');

    // -------------------------------------------------------------------------
    // ACCIONES ESPECIALES
    // -------------------------------------------------------------------------

    Route::patch('/role/{user_id}', [UserController::class, 'changeRole'])
        ->middleware('role:Administrador');

    Route::patch('/status/{user_id}', [UserController::class, 'changeStatus']);
        // ->middleware('permission:users.change-status');

    // -------------------------------------------------------------------------
    // OPERACIONES EN LOTE (BULK)
    // -------------------------------------------------------------------------

    Route::post('/approve', [UserController::class, 'approveRequests'])
        ->middleware('role:Administrador|supervisor');

    Route::post('/change-status', [UserController::class, 'changeUserStatus']);
        // ->middleware('permission:users.change-status');

    Route::post('/reject-delete', [UserController::class, 'rejectAndDeleteRequests'])
        ->middleware('role:Administrador|supervisor');
});




// -------------------------------------------------------------------------
// PERFILES
// -------------------------------------------------------------------------

Route::prefix('profiles')->group(function () {

    Route::get('/', [ProfileController::class, 'index']);

    Route::get('/{profile_id}', [ProfileController::class, 'show']);

    Route::post('/', [ProfileController::class, 'store']);

    Route::put('/{profile_id}', [ProfileController::class, 'update']);

    Route::patch('/{profile_id}', [ProfileController::class, 'partialUpdate']);

    Route::delete('/{profile_id}', [ProfileController::class, 'destroy']);
});