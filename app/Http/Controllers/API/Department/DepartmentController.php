<?php

namespace App\Http\Controllers\API\Department;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Requests\Department\PartialUpdateDepartmentRequest;
use App\Http\Requests\Department\ChangeStateDepartmentRequest;
use App\Http\Controllers\Controller;
use App\Services\Department\DepartmentService;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de departamentos.
 */
class DepartmentController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de departamentos.
     */
    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtiene la lista de todos los departamentos registrados.
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
     * Muestra la información detallada de un departamento específico.
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
     * Crea un nuevo registro de departamento.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error']) {    
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualización total del registro del departamento (PUT).
     */
    public function update(UpdateDepartmentRequest $request, string $id)
    {
        dd("¡Sí entra al controlador!", "ID recibido: " . $id, "Datos: ", $request->all());

        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function partialUpdate(UpdateDepartmentRequest $request, string $id)
    {
        // Reutiliza la validación parcial y el mismo método de actualización del servicio
        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina el registro de un departamento.
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
     * Obtiene el historial del departamento.
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
}