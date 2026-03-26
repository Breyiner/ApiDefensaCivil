<?php

namespace App\Http\Controllers\API\HousingQuality;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\HousingQuality\StoreHousingQualityRequest;
use App\Http\Requests\HousingQuality\UpdateHousingQualityRequest;
use App\Http\Requests\HousingQuality\PartialUpdateHousingQualityRequest;
use App\Http\Requests\HousingQuality\ChangeStateHousingQualityRequest;
use App\Http\Controllers\Controller;
use App\Services\HousingQuality\HousingQualityService;
use Illuminate\Http\Request;

/**
 * Controlador para la Calidad de la Vivienda.
 * Administra los criterios técnicos sobre el estado físico de los hogares 
 * (ej. Estado de techos, paredes, pisos y habitabilidad general).
 */
class HousingQualityController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica para Calidad de Vivienda.
     */
    public function __construct(HousingQualityService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtiene todos los registros de calidad de vivienda.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error'])
        {
            // Nota: Se corrigió a ResponseFormatter (PascalCase) para consistencia.
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Muestra un registro específico de calidad de vivienda por ID.
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
     * Registra un nuevo criterio o evaluación de calidad.
     */
    public function store(StoreHousingQualityRequest $request)
    {
        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error'])
        {    
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualización integral de los datos de calidad (PUT).
     */
    public function update(UpdateHousingQualityRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualización parcial de atributos de calidad (PATCH).
     */
    public function partialUpdate(PartialUpdateHousingQualityRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->partialUpdate($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []); 
    }

    /**
     * Cambia el estado (activo/inactivo) de un criterio de calidad.
     */
    public function ChangeStatus(ChangeStateHousingQualityRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->changeStatus($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []); 
    }

    public function history(Request $request, string $id)
    {
        $perPage = $request->input('per_page', 10);

        $response = $this->service->history($id, $perPage);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un registro de calidad de vivienda.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? [], $response['paginate'] ?? []);
    }
}