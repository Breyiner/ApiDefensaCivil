<?php

use App\Http\Controllers\API\Audit\AuditController;
use Illuminate\Support\Facades\Route;


/**
 * ============================================================================
 * RUTAS DE AUDITORÍA Y DASHBOARDS (AUTENTICADAS)
 * ============================================================================
 *
 * Endpoints para los dashboards de estadísticas y actividad del sistema,
 * diferenciados por el rol del usuario.
 *
 * Prefijo base: /api/audits
 * Middleware heredado: ['api', 'auth:sanctum', 'verified']
 */

Route::prefix('audits')->group(function () {

    // Dashboard con métricas y actividad reciente para el rol Administrador
    Route::get('/dashBoardAdmin', [AuditController::class, 'dashBoardAdmin'])
        ->middleware('permission:audits.dashboard-admin');

    // Dashboard con métricas y actividad reciente para el rol Supervisor
    Route::get('/dashBoardSupervisor', [AuditController::class, 'dashBoardSupervisor'])
        ->middleware('permission:audits.dashboard-supervisor');

    Route::delete('/bulk_delete', [AuditController::class, 'bulkDestroy'])
        ->middleware('permission:audits.delete_bulk-admin')
    ;
    Route::delete('/{id}/delete_audit', [AuditController::class, 'destroy'])
        ->middleware('permission:audits.delete_id-admin')
    ;
});