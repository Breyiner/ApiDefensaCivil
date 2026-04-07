<?php

use App\Http\Controllers\API\Notification\NotificationController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE NOTIFICACIONES (AUTENTICADAS)
 * ============================================================================
 *
 * Gestión de las notificaciones del sistema: listado, conteo,
 * filtrado por usuario y marcado como leídas.
 *
 * Prefijo base: /api/notifications
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */

Route::prefix('notifications')->group(function () {

    // Listar todas las notificaciones del sistema
    Route::get('/', [NotificationController::class, 'index']);

    // Contar notificaciones no leídas de un usuario específico
    Route::get('/user/count/{id}', [NotificationController::class, 'countUnreadByUser']);

    // Obtener notificaciones no leídas de un usuario específico
    Route::get('/user/unread/{id}', [NotificationController::class, 'getUnreadByUser']);

    // Obtener todas las notificaciones de un usuario específico
    Route::get('/user/{id}', [NotificationController::class, 'getByUser']);

    Route::get('/{id}', [NotificationController::class, 'show']);

    Route::post('/', [NotificationController::class, 'store']);

    Route::put('/{id}', [NotificationController::class, 'update']);

    Route::patch('/{id}', [NotificationController::class, 'partialUpdate']);

    Route::delete('/{id}', [NotificationController::class, 'destroy']);
});