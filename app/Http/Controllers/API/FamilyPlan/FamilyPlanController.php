<?php

namespace App\Http\Controllers\API\FamilyPlan;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\FamilyPlan\StoreFamilyPlanRequest;
use App\Http\Requests\FamilyPlan\UpdateFamilyPlanRequest;
use App\Http\Requests\FamilyPlan\PartialUpdateFamilyPlanRequest;
use App\Http\Requests\FamilyPlan\ChangeStatusFamilyPlanRequest;
use App\Http\Requests\FamilyPlan\GeoreFamilyPlanRequest;
use App\Http\Requests\FamilyPlan\IdentifyFamilyPlanRequest;
use App\Http\Requests\FamilyPlan\PatchFamilyTypeRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\FamilyPlan\FilterByStatusFamilyPlanRequest;
use App\Services\FamilyPlan\FamilyPlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador de Planes Familiares.
 * Gestiona el ciclo de vida del diagnóstico familiar, desde la creación 
 * hasta la georreferenciación y el seguimiento de estados.
 */
class FamilyPlanController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica para Planes Familiares.
     */
    public function __construct(FamilyPlanService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtiene todos los planes familiares paginados según el rol del usuario autenticado.
     * 
     * 🔹 Aplica filtros automáticos por rol mediante scopes
     * 🔹 Acepta parámetro per_page para personalizar la paginación
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // 🔹 Obtener parámetro de paginación (default: 15)
        $perPage = $request->input('per_page', 15);

        $response = FamilyPlanService::getAll($perPage);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? [],
            $response['paginate'] ?? null
        );
    }

    /**
     * Muestra el detalle completo de un Plan Familiar específico.
     * 
     * 🔹 Valida acceso automáticamente mediante scope forAuthUser
     * 🔹 Retorna 404 si el plan no existe o el usuario no tiene acceso
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $response = $this->service->getById($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Inicia la creación de un nuevo Plan Familiar.
     * 
     * 🔹 Valida datos mediante StoreFamilyPlanRequest
     * 🔹 Aplica valores por defecto del modelo (status_plan_id = 1)
     * 
     * @param StoreFamilyPlanRequest $request
     * @return JsonResponse
     */
    public function store(StoreFamilyPlanRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualización integral del Plan Familiar (PUT).
     * 
     * 🔹 Reemplaza todos los campos con los datos enviados
     * 
     * @param UpdateFamilyPlanRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateFamilyPlanRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->update($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualización de campos específicos del Plan (PATCH).
     * 
     * 🔹 Solo actualiza los campos enviados en la petición
     * 
     * @param PartialUpdateFamilyPlanRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function partialUpdate(PartialUpdateFamilyPlanRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->partialUpdate($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Cambia el estado del plan (ej. 'En Proceso', 'Completado', 'Validado').
     * 
     * 🔹 Registra auditoría del cambio de estado
     * 
     * @param ChangeStatusFamilyPlanRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function changeStatus(ChangeStatusFamilyPlanRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->changeStatus($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function patchFamilyType(PatchFamilyTypeRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->patchFamilyType($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Registra los datos de identificación oficial del núcleo familiar dentro del plan.
     * 
     * @param IdentifyFamilyPlanRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function identify(IdentifyFamilyPlanRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->identify($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un Plan Familiar (usualmente restringido o bajo Soft Deletes).
     * 
     * 🔹 Elimina primero el historial asociado antes de eliminar el plan
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Verifica si el usuario autenticado tiene acceso a un plan específico.
     * 
     * 🔹 Valida acceso según rol y reglas de negocio
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function checkAccess(string $id): JsonResponse
    {
        $response = $this->service->checkAccess($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Genera y descarga un PDF del plan familiar.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf($id)
    {
        return $this->service->generatePdf($id);
    }

    /**
     * Verifica si un plan familiar tiene al menos un integrante registrado.
     *
     * 🔹 Utilizado por el frontend para habilitar o deshabilitar acciones
     *    que requieren que el plan tenga integrantes antes de continuar.
     *
     * GET /family-plans/{id}/has-members
     *
     * Response 200:
     * {
     *   "data": {
     *     "has_members": true
     *   }
     * }
     *
     * @param int $id ID del plan familiar a verificar
     * @return JsonResponse
     */
    public function hasMembers(int $id): JsonResponse
    {
        $response = $this->service->hasMembers($id);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code'],
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data']
        );
    }

    /**
     * Obtiene planes familiares filtrados por estado.
     *
     * Endpoint para consultar planes familiares según su estado actual.
     * Recibe el ID del estado como query parameter y retorna una lista paginada.
     *
     * GET /familyPlans/by-status?status={statusId}
     *
     * Query Parameters:
     * - status (required, int): ID del estado por el cual filtrar
     *
     * Response 200:
     * {
     *   "data": [
     *     {
     *       "id": 1,
     *       "last_names": "García Pérez",
     *       "city": "Bogotá",
     *       "department": "Cundinamarca",
     *       "status": "En Proceso",
     *       "status_id": 2,
     *       "date_create": "15/03/2026"
     *     }
     *   ],
     *   "paginate": {
     *     "current_page": 1,
     *     "per_page": 10,
     *     "total": 25,
     *     "last_page": 3,
     *     "from": 1,
     *     "to": 10
     *   }
     * }
     *
     * @param FilterByStatusFamilyPlanRequest $request Validación del query parameter
     * @return JsonResponse
     */
    public function getByStatus(FilterByStatusFamilyPlanRequest $request)
    {

        // 🔹 Obtener parámetro de paginación (default: 15)
        $perPage = $request->input('per_page', 15);

        $statusId = $request->validated()['status'];
        $response = $this->service->getByStatus($statusId, $perPage);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? [],
            $response['paginate']
        );
    }

    /**
     * Valida si un plan familiar cumple los requisitos mínimos para procesamiento.
     *
     * 🔹 **Requisitos obligatorios:**
     *    - Al menos 1 integrante (`familyMembers`)
     *    - Al menos 1 factor de riesgo (`riskFactors`)
     * 🔹 Retorna código 422 si no cumple (estándar Laravel para validación)
     * 🔹 Incluye conteos para debugging y UX del frontend
     *
     * GET /familyPlans/{familyPlan_id}/validate-requirements
     *
     * Response 200 (válido):
     * {
     *   "data": {
     *     "is_valid": true,
     *     "has_members": true,
     *     "members_count": 3,
     *     "has_risk_factors": true,
     *     "risk_factors_count": 2
     *   }
     * }
     *
     * Response 422 (inválido):
     * {
     *   "data": {
     *     "is_valid": false,
     *     "has_members": false,
     *     "members_count": 0,
     *     "has_risk_factors": true,
     *     "risk_factors_count": 1
     *   }
     * }
     *
     * @param int $familyPlan_id ID del plan familiar a verificar
     * @return JsonResponse
     */
    public function validateRequirements(int $familyPlan_id): JsonResponse
    {
        $response = $this->service->validateRequirements($familyPlan_id);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data']
        );
    }
}
