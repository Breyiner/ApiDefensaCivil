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
    Route::get('/', [NotificationController::class, 'index'])
        ->middleware('permission:notifications.index');

    // Contar notificaciones no leídas de un usuario específico
    Route::get('/user/count/{id}', [NotificationController::class, 'countUnreadByUser'])
        ->middleware('permission:notifications.count-unread');

    // Obtener notificaciones no leídas de un usuario específico
    Route::get('/user/unread/{id}', [NotificationController::class, 'getUnreadByUser'])
        ->middleware('permission:notifications.unread-by-user');

    // Obtener todas las notificaciones de un usuario específico
    Route::get('/user/{id}', [NotificationController::class, 'getByUser'])
        ->middleware('permission:notifications.by-user');

    Route::get('/{id}', [NotificationController::class, 'show'])
        ->middleware('permission:notifications.show');

    Route::post('/', [NotificationController::class, 'store'])
        ->middleware('permission:notifications.store');

    Route::put('/{id}', [NotificationController::class, 'update'])
        ->middleware('permission:notifications.update');

    Route::patch('/{id}', [NotificationController::class, 'partialUpdate'])
        ->middleware('permission:notifications.partial-update');

    Route::delete('/{id}', [NotificationController::class, 'destroy'])
        ->middleware('permission:notifications.destroy');
});