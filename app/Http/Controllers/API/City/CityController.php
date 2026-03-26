<?php

namespace App\Http\Controllers\API\City;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\City\StoreCityRequest;
use App\Http\Requests\City\UpdateCityRequest;
use App\Http\Requests\City\PartialUpdateCityRequest;
use App\Http\Requests\City\ChangeStateCityRequest;
use App\Http\Controllers\Controller;
use App\Services\City\CityService;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Ciudades.
 * Administra la división política/territorial necesaria para la ubicación de planes familiares.
 */
class CityController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica de ciudades.
     */
    public function __construct(CityService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todas las ciudades registradas.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Muestra una ciudad específica por su identificador.
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
     * Registra una nueva ciudad tras validar los datos.
     */
    public function store(StoreCityRequest $request)
    {
        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualización completa (PUT) de los datos de la ciudad.
     */
    public function update(UpdateCityRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualización parcial (PATCH) de la ciudad.
     */
    public function partialUpdate(PartialUpdateCityRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->partialUpdate($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina una ciudad si no tiene dependencias activas.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene el historial de auditoría de una ciudad.
     * @param string $id ID de la ciudad.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function history(Request $request, string $id)
    {

        $perPage = $request->input('per_page', 10);

        $response = $this->service->history($id, $perPage);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? [], $response['paginate'] ?? []);
    }

    /**
     * Obtiene todas las ciudades que pertenecen a un departamento específico.
     * Útil para filtrar propiedades o planes por ubicación geográfica.
     */
    public function getByDepartment(string $department_id)
    {
        $response = $this->service->getAllByDepartment($department_id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
