<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\BulkUserIdsRequest;
use App\Http\Requests\User\ChangeRoleUserRequest;
use App\Http\Requests\User\ChangeStateUserRequest;
use App\Http\Requests\User\FilterByStatusUserRequest;
use App\Http\Requests\User\PartialUpdateUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador de Usuarios.
 *
 * Gestiona las cuentas de acceso al sistema, vinculando credenciales, roles y estados.
 * Delega toda la lógica de negocio a UserService y devuelve respuestas
 * estandarizadas mediante ResponseFormatter.
 *
 * Operaciones individuales : index, show, store, update, partialUpdate,
 *                            ChangeStatus, ChangeRole, destroy, history
 * Operaciones de consulta   : getRequestsAdmins, getRequestsSupervisors,
 *                             getUserForAdmins, getUserForSupervisors, getByStatus
 * Operaciones masivas (bulk): approveRequests, changeUserStatus,
 *                             rejectAndDeleteRequests
 */
class UserController extends Controller
{
    protected UserService $service;

    /**
     * Inyección del servicio de lógica de negocio para usuarios.
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    // =========================================================================
    //  CRUD INDIVIDUAL
    // =========================================================================

    /**
     * Retorna el listado paginado de todos los usuarios.
     *
     * 🔹 Aplica filtros automáticos por rol mediante el scope forAuthUser
     * 🔹 Acepta per_page como query parameter (default: 15)
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);

        $response = UserService::getAll($perPage);

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
     * Retorna la información de un usuario específico.
     *
     * 🔹 Valida acceso automáticamente mediante el scope forAuthUser
     * 🔹 Retorna 404 si el usuario no existe o no tiene acceso
     */
    public function show(string $id): JsonResponse
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
     * Registra un nuevo usuario en la plataforma.
     *
     * 🔹 El servicio gestiona el hashing de contraseña y la asignación inicial de rol
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $response = $this->service->create($request->validated());

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
     * Actualización integral del usuario (PUT).
     *
     * 🔹 Reemplaza todos los campos enviados en la petición
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $response = $this->service->update($request->validated(), $id);

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
     * Actualización parcial del usuario (PATCH).
     *
     * 🔹 Útil para cambiar solo la contraseña u otros campos puntuales
     * 🔹 No afecta los campos no enviados en la petición
     */
    public function partialUpdate(PartialUpdateUserRequest $request, string $id): JsonResponse
    {
        $response = $this->service->partialUpdate($request->validated(), $id);

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
     * Cambia el rol de un usuario individual y registra auditoría.
     *
     * 🔹 Registra old_role → new_role en la tabla de auditorías
     */
    public function ChangeRole(ChangeRoleUserRequest $request, string $id): JsonResponse
    {
        $response = $this->service->changeRole($request->validated(), $id);

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
     * Elimina una cuenta de usuario.
     *
     * 🔹 Se recomienda tener SoftDeletes activo para mantener integridad referencial
     */
    public function destroy(string $id): JsonResponse
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
     * Retorna el historial de auditoría de un usuario.
     *
     * 🔹 Incluye todos los cambios de estado y rol registrados
     * 🔹 Acepta per_page como query parameter (default: 10)
     */
    public function history(Request $request, string $id): JsonResponse
    {
        $perPage = $request->input('per_page', 10);

        $response = $this->service->history($id, $perPage);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? [],
            $response['paginate'] ?? []
        );
    }

    // =========================================================================
    //  CONSULTAS ESPECIALIZADAS
    // =========================================================================

    /**
     * Retorna peticiones pendientes visibles para administradores.
     *
     * 🔹 Filtra usuarios con state_user_id = 3 (Petición)
     * 🔹 Acepta per_page como query parameter (default: 10)
     */
    public function getRequestsAdmins(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);

        $response = $this->service->getRequestsAdmins($perPage);

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
     * Retorna peticiones pendientes visibles para supervisores.
     *
     * 🔹 Solo muestra peticiones de la misma seccional del supervisor autenticado
     * 🔹 Acepta per_page como query parameter (default: 10)
     */
    public function getRequestsSupervisors(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);

        $response = $this->service->getRequestsSupervisors($perPage);

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
     * Retorna usuarios filtrados por estado.
     *
     * 🔹 Recibe el ID del estado como query parameter (status)
     * 🔹 Acepta per_page como query parameter (default: 10)
     *
     * GET /users/by-status?status={statusId}&per_page={perPage}
     */
    public function getByStatus(FilterByStatusUserRequest $request): JsonResponse
    {
        $statusId = $request->validated()['status'];
        $perPage  = $request->input('per_page', 10);

        $response = $this->service->getByStatus($statusId, $perPage);

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

    // =========================================================================
    //  OPERACIONES MASIVAS (BULK)
    // =========================================================================

    /**
     * Aprueba peticiones de usuarios en masa.
     *
     * 🔹 Solo afecta usuarios con estado 'Peticion' (filtro en el servicio)
     * 🔹 Despacha ApproveUserRequestsJob por defecto (async=true)
     * 🔹 Enviar async=false para ejecución síncrona inmediata
     *
     * @param BulkUserIdsRequest $request  Valida: user_ids[], async?
     */
    public function approveRequests(BulkUserIdsRequest $request): JsonResponse
    {
        $userIds = $request->input('user_ids');
        $async   = $request->boolean('async', true);

        $response = $this->service->approveRequests($userIds, $async);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code']);
    }

    /**
     * Cambia el estado de múltiples usuarios en masa.
     *
     * 🔹 Aplica el nuevo estado a todos los user_ids recibidos
     * 🔹 Registra auditoría por cada usuario modificado
     * 🔹 Despacha ChangeUserStatusJob por defecto (async=true)
     * 🔹 Enviar async=false para ejecución síncrona inmediata
     *
     * @param ChangeStateUserRequest $request  Valida: user_ids[], state_user_id, async?
     */
    public function changeUserStatus(ChangeStateUserRequest $request): JsonResponse
    {
        $data    = $request->validated();
        $userIds = $data['user_ids'];
        $stateId = $data['state_user_id'];
        $async   = $request->boolean('async', true);

        $response = $this->service->changeUserStatus($userIds, $stateId, $async);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code']);
    }

    /**
     * Rechaza y elimina peticiones de usuarios en masa.
     *
     * 🔹 Solo afecta usuarios con estado 'Peticion' (filtro en el servicio)
     * 🔹 Elimina perfil y cuenta; la auditoría se registra ANTES del delete
     * 🔹 Despacha RejectAndDeleteRequestsJob por defecto (async=true)
     * 🔹 Enviar async=false para ejecución síncrona inmediata
     *
     * @param BulkUserIdsRequest $request  Valida: user_ids[], async?
     */
    public function rejectAndDeleteRequests(BulkUserIdsRequest $request): JsonResponse
    {
        $userIds = $request->input('user_ids');
        $async   = $request->boolean('async', true);

        $response = $this->service->rejectAndDeleteRequests($userIds, $async);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code']);
    }
}