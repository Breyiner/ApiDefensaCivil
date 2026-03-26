<?php

namespace App\Http\Controllers\API\Nationality;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Nationality\StoreNationalityRequest;
use App\Http\Requests\Nationality\UpdateNationalityRequest;
use App\Http\Requests\Nationality\PartialUpdateNationalityRequest;
use App\Http\Requests\Nationality\ChangeStateNationalityRequest;
use App\Http\Controllers\Controller;
use App\Services\Nationality\NationalityService;
use Illuminate\Http\Request;

/**
 * Controlador de Nacionalidades.
 * Gestiona el catálogo de nacionalidades para la caracterización de perfiles.
 */
class NationalityController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica para la entidad Nationality.
     */
    public function __construct(NationalityService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todas las nacionalidades registradas.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    /**
     * Obtiene la información de una nacionalidad específica mediante su ID.
     */
    public function show(string $id)
    {
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

    /**
     * Crea una nueva nacionalidad en el sistema.
     */
    public function store(StoreNationalityRequest $request)
    {
        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    /**
     * Actualización total de los datos de una nacionalidad (PUT).
     */
    public function update(UpdateNationalityRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    /**
     * Actualización parcial de un registro de nacionalidad (PATCH).
     */
    public function partialUpdate(PartialUpdateNationalityRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->partialUpdate($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    /**
     * Cambia el estado de activación de la nacionalidad (Activa/Inactiva).
     */
    public function changeStatus(ChangeStateNationalityRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->changeStatus($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    /**
     * Elimina un registro de nacionalidad.
     * El servicio valida que no existan perfiles asociados antes de proceder.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
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
