<?php

namespace App\Http\Controllers\API\Sectional;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Sectional\StoreSectionalRequest;
use App\Http\Requests\Sectional\UpdateSectionalRequest;
use App\Http\Requests\Sectional\PartialUpdateSectionalRequest;
use App\Http\Requests\Sectional\ChangeStateSectionalRequest;
use App\Http\Controllers\Controller;
use App\Services\Sectional\SectionalService;
use Illuminate\Http\Request;

/**
 * Controlador de Seccionales.
 * Administra las sedes o divisiones regionales vinculadas a las Organizaciones.
 */
class SectionalController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica para Seccionales.
     */
    public function __construct(SectionalService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todas las seccionales registradas.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error'])
        {
            // Nota: Corregido a ResponseFormatter (PascalCase).
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Lista todas las seccionales que estén activas y que tengan al menos una organizacion asociada.
     */
    public function getActiveWithOrganization()
    {
        $response = $this->service->getActiveWithOrganization();

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene los detalles de una seccional específica por ID.
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
     * Registra una nueva seccional vinculada a una organización.
     */
    public function store(StoreSectionalRequest $request)
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
     * Actualización integral de los datos de la seccional (PUT).
     */
    public function update(UpdateSectionalRequest $request, string $id)
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
     * Actualización parcial de campos de la seccional (PATCH).
     */
    public function partialUpdate(PartialUpdateSectionalRequest $request, string $id)
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
     * Cambia el estado (Activa/Inactiva) de la seccional.
     */
    public function changeStatus(ChangeStateSectionalRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->changeStatus($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []); 
    }

    /**
     * Elimina una seccional tras validar que no tenga dependencias activas (como Sectores).
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

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

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? [], $response['paginate'] ?? []);
    }
}