<?php

namespace App\Http\Controllers\API\Sector;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Sector\StoreSectorRequest;
use App\Http\Requests\Sector\UpdateSectorRequest;
use App\Http\Requests\Sector\PartialUpdateSectorRequest;
use App\Http\Requests\Sector\ChangeStateSectorRequest;
use App\Http\Controllers\Controller;
use App\Services\Sector\SectorService;
use Illuminate\Http\Request;

/**
 * Controlador de Sectores.
 * Gestiona las unidades territoriales mínimas (barrios, zonas o sectores) 
 * vinculadas a las Seccionales para el despliegue operativo.
 */
class SectorController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica para Sectores.
     */
    public function __construct(SectorService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todos los sectores registrados.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error'])
        {
            // Nota: Se normalizó a ResponseFormatter (PascalCase).
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene los detalles de un sector específico.
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
     * Registra un nuevo sector geográfico.
     */
    public function store(StoreSectorRequest $request)
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
     * Actualización total del sector (PUT).
     */
    public function update(UpdateSectorRequest $request, string $id)
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
     * Actualización parcial del sector (PATCH).
     */
    public function partialUpdate(PartialUpdateSectorRequest $request, string $id)
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
     * Activa o desactiva un sector para la asignación de nuevos planes.
     */
    public function ChangeStatus(ChangeStateSectorRequest $request, string $id)
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
     * Elimina un sector. 
     * El servicio validará que no existan planes familiares asociados a esta zona.
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