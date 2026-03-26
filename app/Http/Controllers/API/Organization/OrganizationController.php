<?php

namespace App\Http\Controllers\API\Organization;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Http\Requests\Organization\PartialUpdateOrganizationRequest;
use App\Http\Requests\Organization\ChangeStateOrganizationRequest;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationService;
use Illuminate\Http\Request;

/**
 * Controlador de Organizaciones.
 * Gestiona la entidad principal que agrupa las dependencias administrativas y territoriales.
 */
class OrganizationController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica para Organizaciones.
     */
    public function __construct(OrganizationService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todas las organizaciones registradas.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error'])
        {
            // Nota: Se recomienda normalizar a ResponseFormatter (PascalCase).
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Muestra los detalles de una organización específica.
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
     * Registra una nueva organización en el sistema.
     */
    public function store(StoreOrganizationRequest $request)
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
     * Actualización total de los datos de la organización (PUT).
     */
    public function update(UpdateOrganizationRequest $request, string $id)
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
     * Actualización parcial de campos (PATCH).
     */
    public function partialUpdate(PartialUpdateOrganizationRequest $request, string $id)
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
     * Cambia el estado (Activa/Inactiva) de la organización.
     */
    public function ChangeStatus(ChangeStateOrganizationRequest $request, string $id)
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
     * Elimina una organización tras verificar que no tenga seccionales vinculadas.
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

    /**
     * Obtiene todas las seccionales que pertenecen a una organización específica.
     * Método clave para la navegación jerárquica de la estructura.
     */
    public function getSectional(string $id)
    {
        $response = $this->service->getAllForSectional($id);

        if ($response['error']) {
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