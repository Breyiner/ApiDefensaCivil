<?php

namespace App\Http\Controllers\API\DocumentType;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\DocumentType\StoreDocumentTypeRequest;
use App\Http\Requests\DocumentType\UpdateDocumentTypeRequest;
use App\Http\Requests\DocumentType\PartialUpdateDocumentTypeRequest;
use App\Http\Requests\DocumentType\ChangeStateDocumentTypeRequest;
use App\Http\Controllers\Controller;
use App\Services\DocumentType\DocumentTypeService;
use Illuminate\Http\Request;

/**
 * Controlador de Tipos de Documento.
 * Gestiona el catálogo de documentos de identidad permitidos en la plataforma.
 */
class DocumentTypeController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica de negocio para Tipos de Documento.
     */
    public function __construct(DocumentTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todos los tipos de documentos disponibles.
     */
    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error'])
        {
            // Nota: Se corrigió visualmente a ResponseFormatter (PascalCase).
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene los detalles de un tipo de documento específico.
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
     * Crea un nuevo tipo de documento (ej. 'Cédula de Extranjería').
     */
    public function store(StoreDocumentTypeRequest $request)
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
     * Actualización total de los campos de un tipo de documento.
     */
    public function update(UpdateDocumentTypeRequest $request, string $id)
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
     * Actualización parcial (ej. solo corregir el nombre o la abreviatura).
     */
    public function partialUpdate(PartialUpdateDocumentTypeRequest $request, string $id)
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
     * Activa o desactiva un tipo de documento para su uso en formularios.
     */
    public function ChangeStatus(ChangeStateDocumentTypeRequest $request, string $id)
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
     * Elimina físicamente el tipo de documento. 
     * El servicio validará que no existan personas registradas con este tipo.
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