<?php

namespace App\Http\Controllers\housingInfoType;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\housingInfoType\storeHousingInfoType;
use App\Http\Requests\housingInfoType\updateHousingInfoType;
use App\Models\housingInfoType\HousingInfoType;
use App\Services\HousingInfoType\housingInfoTypeService;

class housingInfoTypeController extends Controller
{
    protected $service;

    public function __construct(housingInfoTypeService  $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function show(string $id)
    {
        $housingType = HousingInfoType::find($id);

        if (!$housingType) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->getById($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function store(storeHousingInfoType $request)
    {
        $data = $request->validated();

        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function update(updateHousingInfoType $request, string $id)
    {
        $housingType = HousingInfoType::find($id);

        if (!$housingType) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->update($request->validated(), $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function destroy(string $id)
    {
        $housingType = HousingInfoType::find($id);

        if (!$housingType) {
            return ResponseFormatter::error("Registro no encontrado", 404);
        }

        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }
}
