<?php

namespace App\Http\Controllers\API\VulnerableQuestion;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Services\VulnerableQuestion\VulnerableQuestionService;

use App\Http\Requests\VulnerableQuestion\StoreVulnerableQuestionRequest;
use App\Http\Requests\VulnerableQuestion\UpdateVulnerableQuestionRequest;
use App\Http\Requests\VulnerableQuestion\PartialUpdateVulnerableQuestionRequest;
use App\Http\Requests\VulnerableQuestion\ChangeStateVulnerableQuestionRequest;
use Illuminate\Http\Request;

/**
 * Controlador de Preguntas de Vulnerabilidad.
 * Administra el banco de preguntas utilizadas para caracterizar socioeconómicamente 
 * a las familias y determinar indicadores de riesgo.
 */
class VulnerableQuestionController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica para Preguntas de Vulnerabilidad.
     */
    public function __construct(VulnerableQuestionService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todas las preguntas de vulnerabilidad.
     */
    public function index()
    {
        $response = $this->service->getAll();

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
     * Obtiene una pregunta específica por su ID.
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
     * Registra una nueva pregunta en el banco de vulnerabilidad.
     */
    public function store(StoreVulnerableQuestionRequest $request)
    {
        $data = $request->validated();
        $response = $this->service->create($data);

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
     * Actualiza integralmente una pregunta (PUT).
     */
    public function update(UpdateVulnerableQuestionRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->update($data, $id);

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
     * Actualiza parcialmente campos de una pregunta (PATCH).
     */
    public function partialUpdate(PartialUpdateVulnerableQuestionRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->partialUpdate($data, $id);

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
     * Cambia el estado (Activa/Inactiva) de una pregunta.
     * Útil para retirar preguntas de las encuestas sin eliminar el histórico.
     */
    public function changeStatus(ChangeStateVulnerableQuestionRequest $request, string $id)
    {
        $data = $request->validated();
        $response = $this->service->changeStatus($data, $id);

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
     * Elimina una pregunta si no tiene respuestas vinculadas.
     */
    public function destroy(string $id)
    {
        $response = $this->service->delete($id);

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
     * Devuelve las preguntas en formato paginado.
     * Ideal para vistas de administración con grandes volúmenes de datos.
     */
    public function paginate()
    {
        $response = $this->service->paginate();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? [],$response['paginate']);
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