<?php

namespace App\Http\Controllers\API\HousingInfo;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use App\Http\Requests\HousingInfo\StoreHousingInfoRequest;
use App\Http\Controllers\Controller;
use App\Services\HousingInfo\HousingInfoService;

/**
 * Controlador de Información de Vivienda.
 * Gestiona los datos técnicos y socioeconómicos del inmueble donde reside la familia.
 */
class HousingInfoController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica para Información de Vivienda.
     */
    public function __construct(HousingInfoService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todos los registros de información de vivienda.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error']) {
            // Nota: Se recomienda normalizar a PascalCase (ResponseFormatter).
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene los detalles de la vivienda para un registro específico.
     */
    public function show(string $id)
    {
        $response = $this->service->getById($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Registra la información técnica de una vivienda.
     * Habitualmente vincula datos como tipo de piso, techo y acceso a servicios.
     */
    public function store(StoreHousingInfoRequest $request)
    {
        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un registro de información de vivienda.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getByType(string $familyPlanId, string $typeId)
    {
        $response = $this->service->getByType($familyPlanId, $typeId);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data']
        );
    }

    public function updateByType(Request $request, string $familyPlanId, string $typeId)
    {
        $request->validate([
            'path' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $response = $this->service->updateByType($familyPlanId, $typeId, $request->file('path'));

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data']
        );
    }

    public function destroyByType(string $familyPlanId, string $typeId)
    {
        $response = $this->service->deleteByType($familyPlanId, $typeId);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code']
        );
    }
}
