<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resource\StoreResourceRequest;
use App\Http\Requests\Resource\UpdateResourceRequest;
use App\Http\Requests\Resource\PartialUpdateResourceRequest;
use App\Http\Requests\Resource\ChangeStateResourceRequest;
use App\Models\Resource\Resource;
use App\Services\Resource\ResourceService;

class ResourceController extends Controller
{
    protected $service;

    public function __construct(ResourceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function show(string $id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->getById($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function store(StoreResourceRequest $request)
    {

        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function update(UpdateResourceRequest $request, string $id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->update($request->validated(), $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function partialUpdate(PartialUpdateResourceRequest $request, string $id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->partialUpdate($request->validated(), $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function changeStatus(ChangeStateResourceRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->changeStatus($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []); 
    }

    public function destroy(string $id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    
    public function history(string $id)
    {
        $response = $this->service->history($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? [], $response['paginate'] ?? []);
    }
}
