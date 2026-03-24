<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\PartialUpdateUserRequest;
use App\Http\Requests\User\ChangeStateUserRequest;
use App\Http\Requests\User\ChangeRoleUserRequest;
use App\Http\Requests\User\FilterByStatusUserRequest;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador de Usuarios.
 * Gestiona las cuentas de acceso al sistema, vinculando credenciales, roles y estados.
 */
class UserController extends Controller
{
    protected $service;

    /**
     * Inyección del servicio de lógica de negocio para usuarios.
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtiene el listado de todos los usuarios registrados con paginación.
     * 
     * 🔹 Aplica filtros automáticos por rol usando el scope forAuthUser
     * 🔹 Acepta parámetro per_page para personalizar la paginación
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // 🔹 Obtener parámetro de paginación (default: 15)
        $perPage = $request->input('per_page', 15);

        $response = UserService::getAll($perPage);

        if ($response['error'])
        {
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
     * Muestra la información de una cuenta de usuario específica.
     * 
     * 🔹 Valida acceso automáticamente mediante scope forAuthUser
     * 🔹 Retorna 404 si el usuario no existe o el usuario no tiene acceso
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
     * Registra un nuevo usuario en la plataforma.
     * Habitualmente gestiona el hashing de contraseñas y asignación inicial de roles.
     * 
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
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
     * Actualización integral del usuario (PUT).
     * 
     * @param UpdateUserRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
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
     * Actualización parcial del usuario (PATCH). 
     * Útil para cambiar solo la contraseña o el estado sin afectar otros campos.
     * 
     * @param PartialUpdateUserRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function partialUpdate(PartialUpdateUserRequest $request, string $id): JsonResponse
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
     * Cambia el estado de un usuario y registra auditoría.
     * 
     * 🔹 Registra el cambio de estado en la tabla de auditorías
     * 
     * @param ChangeStateUserRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function ChangeStatus(ChangeStateUserRequest $request, string $id): JsonResponse
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
     * Cambia el rol de un usuario y registra auditoría.
     * 
     * 🔹 Registra el cambio de rol en la tabla de auditorías
     * 
     * @param ChangeRoleUserRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function ChangeRole(ChangeRoleUserRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->changeRole($data, $id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []); 
    }

    /**
     * Elimina una cuenta de usuario.
     * Nota: Se recomienda implementar "Soft Deletes" para mantener integridad referencial en el historial.
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $response = $this->service->delete($id);

        if ($response['error'])
        {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene el historial de auditoría de un usuario específico.
     * 
     * 🔹 Retorna todos los cambios de estado y rol registrados
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function history(string $id): JsonResponse
    {
        $response = $this->service->history($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene las peticiones de usuarios pendientes para administradores.
     * 
     * 🔹 Muestra usuarios con state_user_id = 3 (Petición)
     * 🔹 Acepta parámetro per_page para personalizar la paginación
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getRequestsAdmins(Request $request): JsonResponse
    {
        // 🔹 Obtener parámetro de paginación (default: 10)
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
     * Obtiene las peticiones de usuarios para supervisores.
     * 
     * 🔹 Solo muestra peticiones de la misma seccional del supervisor
     * 🔹 Acepta parámetro per_page para personalizar la paginación
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getRequestsSupervisors(Request $request): JsonResponse
    {
        // 🔹 Obtener parámetro de paginación (default: 10)
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
     * Obtiene usuarios para administradores (excluye peticiones y rol admin).
     * 
     * 🔹 Filtra usuarios con state_user_id != 3
     * 🔹 Excluye usuarios con rol Administrador (id: 1)
     * 🔹 Acepta parámetro per_page para personalizar la paginación
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserForAdmins(Request $request): JsonResponse
    {
        // 🔹 Obtener parámetro de paginación (default: 10)
        $perPage = $request->input('per_page', 10);

        $response = $this->service->getUserForAdmins($perPage);

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
     * Obtiene usuarios para supervisores.
     * 
     * 🔹 Solo muestra usuarios de la misma seccional
     * 🔹 Excluye roles Administrador (1) y Supervisor (2)
     * 🔹 Acepta parámetro per_page para personalizar la paginación
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserForSupervisors(Request $request): JsonResponse
    {
        // 🔹 Obtener parámetro de paginación (default: 10)
        $perPage = $request->input('per_page', 10);

        $response = $this->service->getUserForSupervisors($perPage);

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
     * Obtiene usuarios filtrados por estado.
     *
     * Endpoint para consultar usuarios según su estado actual.
     * Recibe el ID del estado como query parameter y retorna una lista paginada.
     *
     * GET /users/by-status?status={statusId}&per_page={perPage}
     *
     * Query Parameters:
     * - status (required, int): ID del estado por el cual filtrar
     * - per_page (optional, int): Cantidad de registros por página (default: 10)
     *
     * Response 200:
     * {
     *   "data": [
     *     {
     *       "id": 1,
     *       "full_name": "Juan Pérez García",
     *       "email": "juan.perez@example.com",
     *       "organization": "Cruz Roja Bogotá",
     *       "sectional": "Cundinamarca",
     *       "document_number": "1234567890 CC",
     *       "status": "Activo",
     *       "status_id": 1,
     *       "rol": "Voluntario",
     *       "rol_id": 3
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
     * @param FilterByStatusUserRequest $request Validación del query parameter
     * @return JsonResponse
     */
    public function getByStatus(FilterByStatusUserRequest $request): JsonResponse
    {
        // 🔹 Obtener parámetros validados
        $statusId = $request->validated()['status'];
        $perPage = $request->input('per_page', 10);

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
}