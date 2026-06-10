<?php

namespace App\Http\Controllers\API\Audit;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Audit\BulkAuditIdsRequest;
use App\Services\Audit\AuditService;
use Illuminate\Http\JsonResponse;

class AuditController extends Controller
{
    protected $service;

    public function __construct(AuditService $service)
    {
        $this->service = $service;
    }

    /**
     * Resumen de estados de usuarios
     */
    public function dashBoardAdmin()
    {
        $response = $this->service->DashBoardAdmin();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'],$response['code']);
        }

        return ResponseFormatter::success($response['message'],$response['code'],$response['data'] ?? []
        );
    }
    public function dashBoardSupervisor()
    {
        $response = $this->service->dashBoardSupervisor();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'],$response['code']);
        }

        return ResponseFormatter::success($response['message'],$response['code'],$response['data'] ?? []
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function bulkDestroy(BulkAuditIdsRequest $request): JsonResponse
    {
        $auditIds = $request->input('audit_ids');

        $response = $this->service->bulkDelete($auditIds);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code']);
    }
}
