<?php

namespace App\Http\Controllers\API\Audit;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Audit\BulkAuditIdsRequest;
use App\Services\Audit\AuditUserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuditUserController extends Controller
{
    protected $service;

    public function __construct(AuditUserService $service)
    {
        $this->service = $service;
    }

    public function getByUser(Request $request, string $userId)
    {
        $perPage = $request->input('per_page', 10);

        $response = $this->service->getByUser($userId, $perPage);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? [], $response['paginate'] ?? []);
    }

    public function destroy(string $id): JsonResponse
    {
        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code']);
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