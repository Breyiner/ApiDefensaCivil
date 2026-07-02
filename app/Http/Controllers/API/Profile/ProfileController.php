<?php

namespace App\Http\Controllers\API\Profile;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Profile\StoreProfileRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\PartialUpdateProfileRequest;
use App\Http\Controllers\Controller;
use App\Services\Profile\ProfileService;
use Illuminate\Http\Request;

/**
 * Controlador de Perfiles.
 * Maneja la información personal y demográfica de los sujetos registrados en el sistema.
 */
class ProfileController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de perfiles.
     */
    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtiene la lista de todos los perfiles registrados.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error'])
        {
            // Nota: Corregido a ResponseFormatter (PascalCase)
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Muestra la información de un perfil específico.
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
     * Crea un nuevo perfil. 
     * Vincula datos como nombres, documentos de identidad y contactos.
     */
    public function store(StoreProfileRequest $request)
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
     * Actualización total del perfil (PUT).
     */
    public function update(UpdateProfileRequest $request, string $id)
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
     * Actualización parcial del perfil (PATCH).
     * Ideal para cambios de teléfono, dirección o correo sin reenviar todo el objeto.
     */
    public function partialUpdate(PartialUpdateProfileRequest $request, string $id)
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
     * Elimina un perfil del sistema.
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