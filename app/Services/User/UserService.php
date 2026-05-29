<?php

namespace App\Services\User;

use App\Jobs\User\ApproveUserRequestsJob;
use App\Jobs\User\ChangeUserStatusJob;
use App\Jobs\User\RejectAndDeleteRequestsJob;
use App\Models\User\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Notification\NotificationService;

/**
 * Servicio para la gestión de cuentas de usuario.
 *
 * Este servicio centraliza la lógica de negocio relacionada con:
 * - Consulta de usuarios
 * - Creación, actualización y eliminación
 * - Cambio de estado y rol
 * - Gestión de peticiones de acceso
 * - Registro de auditoría
 */
class UserService
{
    /**
     * Obtiene la lista paginada de usuarios visibles para el usuario autenticado.
     *
     * Aplica el scope forAuthUser para restringir resultados según el rol
     * del usuario autenticado.
     *
     * @param int $perPage Cantidad de registros por página
     * @return array
     */
    public static function getAll(int $perPage = 15): array
    {
        $paginator = User::forAuthUser()
            ->with([
                'profile.gender',
                'profile.documentType',
                'profile.organization.sectional',
                'roles',
                'stateUser',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(function ($user) {
            $profile = $user->profile;
            $role = $user->roles->first();

            return [
                'id' => $user->id,
                'email' => $user->email,
                'full_name' => $profile ? $profile->names . ' ' . $profile->last_names : 'N/A',
                'document_number' => $profile && $profile->documentType
                    ? $profile->document_number . ' ' . $profile->documentType->acronym
                    : 'N/A',
                'organization' => $profile?->organization?->name,
                'sectional' => $profile?->organization?->sectional?->name,
                'rol' => $role?->name,
                'rol_id' => $role?->id,
                'status' => $user->stateUser?->name ?? 'SIN ESTADO',
                'status_id' => $user->state_user_id,
            ];
        });

        return [
            'error' => false,
            'code' => 200,
            'message' => $items->isEmpty()
                ? 'No hay usuarios registrados'
                : 'Usuarios obtenidos exitosamente',
            'data' => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    /**
     * Obtiene un usuario por su ID con sus relaciones principales.
     *
     * @param int|string $id ID del usuario
     * @return array
     */
    public function getById($id): array
    {
        $user = User::with([
            'profile.gender',
            'profile.documentType',
            'profile.organization.sectional',
            'roles',
            'stateUser',
        ])->find($id);

        if (!$user) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Usuario no encontrado',
            ];
        }

        $profile = $user->profile;
        $role = $user->roles->first();

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Usuario obtenido exitosamente',
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'names' => $profile?->names,
                'last_names' => $profile?->last_names,
                'document_type' => $profile?->documentType?->name,
                'document_number' => $profile?->document_number,
                'birth_date' => $profile?->birth_date,
                'gender' => $profile?->gender?->name,
                'phone' => $profile?->phone,
                'organization' => $profile?->organization?->name,
                'sectional' => $profile?->organization?->sectional?->name,
                'rol_id' => $role?->id,
                'rol' => $role?->name,
                'status_id' => $user->stateUser?->id,
                'status' => $user->stateUser?->name ?? 'SIN ESTADO',
            ],
        ];
    }

    /**
     * Obtiene las peticiones pendientes para administradores.
     *
     * Muestra usuarios con state_user_id = 3.
     *
     * @param int $perPage Cantidad de registros por página
     * @return array
     */
    public function getRequestsAdmins(int $perPage = 10): array
    {
        $paginator = User::with(['profile.organization.sectional', 'profile.documentType'])
            ->where('state_user_id', 3)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'full_name' => trim(($item->profile->names ?? '') . ' ' . ($item->profile->last_names ?? '')),
                'email' => $item->email,
                'organization' => $item->profile?->organization?->name,
                'sectional' => $item->profile?->organization?->sectional?->name,
                'document_number' => $item->profile && $item->profile->documentType
                    ? $item->profile->document_number . ' ' . $item->profile->documentType->acronym
                    : 'N/A',
                'status_id' => $item->state_user_id,
                'status' => $item->stateUser?->name,
                'created_at' => $item->created_at,
            ];
        });

        return [
            'error' => false,
            'code' => 200,
            'message' => $items->isEmpty()
                ? 'No hay peticiones de usuarios para acceder al sistema'
                : 'Peticiones de usuarios para acceder al sistema obtenidas exitosamente',
            'data' => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    /**
     * Obtiene las peticiones pendientes visibles para supervisores.
     *
     * Solo muestra peticiones de usuarios pertenecientes a la misma seccional
     * del supervisor autenticado.
     *
     * @param int $perPage Cantidad de registros por página
     * @return array
     */
    public function getRequestsSupervisors(int $perPage = 10): array
    {
        $authUser = Auth::user();
        $authSectionalId = $authUser?->profile?->organization?->sectional_id;

        if (!$authSectionalId) {
            return [
                'error' => true,
                'code' => 403,
                'message' => 'No fue posible determinar la seccional del usuario autenticado',
            ];
        }

        $paginator = User::with([
            'profile.organization.sectional',
            'profile.documentType',
        ])
            ->where('state_user_id', 3)
            ->whereHas('profile.organization', function ($query) use ($authSectionalId) {
                $query->where('sectional_id', $authSectionalId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'full_name' => trim(($item->profile->names ?? '') . ' ' . ($item->profile->last_names ?? '')),
                'email' => $item->email,
                'organization' => $item->profile?->organization?->name,
                'sectional' => $item->profile?->organization?->sectional?->name,
                'document_number' => $item->profile && $item->profile->documentType
                    ? $item->profile->document_number . ' ' . $item->profile->documentType->acronym
                    : 'N/A',
                'status_id' => $item->state_user_id,
                'status' => $item->stateUser?->name,
                'created_at' => $item->created_at,
                // Carbon::parse($item->created_at)->format('d/m/Y'),
            ];
        });

        return [
            'error' => false,
            'code' => 200,
            'message' => $items->isEmpty()
                ? 'No hay peticiones de usuarios para acceder al sistema'
                : 'Peticiones de usuarios para acceder al sistema obtenidas exitosamente',
            'data' => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    /**
     * Obtiene usuarios visibles para administradores.
     *
     * Excluye:
     * - Usuarios en estado Peticion (state_user_id = 3)
     * - Usuarios con rol Administrador (id = 1)
     *
     * @param int $perPage Cantidad de registros por página
     * @return array
     */
    public function getUserForAdmins(int $perPage = 10): array
    {
        $paginator = User::with([
            'profile.organization.sectional',
            'profile.documentType',
            'stateUser',
            'roles',
        ])
            ->where('state_user_id', '!=', 3)
            ->whereDoesntHave('roles', function ($q) {
                $q->where('id', 1);
            })
            ->orderBy('users.created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(function ($item) {
            $role = $item->roles->first();

            return [
                'id' => $item->id,
                'full_name' => trim(($item->profile->names ?? '') . ' ' . ($item->profile->last_names ?? '')),
                'email' => $item->email,
                'organization' => $item->profile?->organization?->name,
                'sectional' => $item->profile?->organization?->sectional?->name,
                'document_number' => $item->profile && $item->profile->documentType
                    ? $item->profile->document_number . ' ' . $item->profile->documentType->acronym
                    : 'N/A',
                'state_user' => $item->stateUser?->name ?? 'SIN ESTADO',
                'state_user_id' => $item->state_user_id,
                'rol' => $role?->name,
                'rol_id' => $role?->id,
            ];
        });

        return [
            'error' => false,
            'code' => 200,
            'message' => $items->isEmpty()
                ? 'No hay usuarios disponibles'
                : 'Usuarios obtenidos exitosamente',
            'data' => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    /**
     * Obtiene usuarios visibles para supervisores.
     *
     * Solo muestra usuarios de la misma seccional del supervisor autenticado
     * y excluye roles Administrador (1) y Supervisor (2).
     *
     * @param int $perPage Cantidad de registros por página
     * @return array
     */
    public function getUserForSupervisors(int $perPage = 10): array
    {
        $authUser = auth()->user();
        $authSectionalId = $authUser?->profile?->organization?->sectional_id;

        if (!$authSectionalId) {
            return [
                'error' => true,
                'code' => 403,
                'message' => 'No fue posible determinar la seccional del usuario autenticado',
            ];
        }

        $paginator = User::with([
            'profile.organization.sectional',
            'profile.documentType',
            'stateUser',
            'roles',
        ])
            ->where('state_user_id', '!=', 3)
            ->whereDoesntHave('roles', function ($q) {
                $q->where('id', 1);
            })
            ->whereDoesntHave('roles', function ($q) {
                $q->where('id', 2);
            })
            ->whereHas('profile.organization', function ($query) use ($authSectionalId) {
                $query->where('sectional_id', $authSectionalId);
            })
            ->orderBy('users.created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(function ($item) {
            $role = $item->roles->first();

            return [
                'id' => $item->id,
                'full_name' => trim(($item->profile->names ?? '') . ' ' . ($item->profile->last_names ?? '')),
                'email' => $item->email,
                'organization' => $item->profile?->organization?->name,
                'sectional' => $item->profile?->organization?->sectional?->name,
                'document_number' => $item->profile && $item->profile->documentType
                    ? $item->profile->document_number . ' ' . $item->profile->documentType->acronym
                    : 'N/A',
                'state_user' => $item->stateUser?->name ?? 'SIN ESTADO',
                'state_user_id' => $item->state_user_id,
                'rol' => $role?->name,
                'rol_id' => $role?->id,
            ];
        });

        return [
            'error' => false,
            'code' => 200,
            'message' => $items->isEmpty()
                ? 'No hay usuarios disponibles'
                : 'Usuarios obtenidos exitosamente',
            'data' => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    /**
     * Obtiene usuarios filtrados por estado.
     *
     * Aplica el scope forAuthUser para respetar permisos según el rol
     * del usuario autenticado.
     *
     * @param int $statusId ID del estado del usuario
     * @param int $perPage Cantidad de registros por página
     * @return array
     */
    public function getByStatus(int $statusId, int $perPage = 10): array
    {
        $paginator = User::forAuthUser()
            ->with([
                'profile.organization.sectional',
                'profile.documentType',
                'stateUser',
                'roles',
            ])
            ->where('state_user_id', $statusId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(function ($user) {
            $profile = $user->profile;
            $role = $user->roles->first();

            return [
                'id' => $user->id,
                'full_name' => $profile ? $profile->names . ' ' . $profile->last_names : 'N/A',
                'email' => $user->email,
                'organization' => $profile?->organization?->name,
                'sectional' => $profile?->organization?->sectional?->name,
                'document_number' => $profile && $profile->documentType
                    ? $profile->document_number . ' ' . $profile->documentType->acronym
                    : 'N/A',
                'status' => $user->stateUser?->name ?? 'SIN ESTADO',
                'status_id' => $user->state_user_id,
                'rol' => $role?->name,
                'rol_id' => $role?->id,
            ];
        });

        return [
            'error' => false,
            'code' => 200,
            'message' => $items->isEmpty()
                ? 'No hay usuarios con este estado'
                : 'Usuarios obtenidos exitosamente',
            'data' => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    /**
     * Crea un nuevo usuario.
     *
     * Se espera que los datos ya vengan validados desde el FormRequest.
     *
     * @param array $data Datos del usuario
     * @return array
     */
    public function create(array $data): array
    {
        $user = User::create($data);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Usuario creado exitosamente',
            'data' => $user,
        ];
    }

    /**
     * Actualiza completamente un usuario existente.
     *
     * @param array $data Datos validados a actualizar
     * @param int|string $id ID del usuario
     * @return array
     */
    public function update(array $data, $id): array
    {
        $user = User::find($id);

        if (!$user) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Usuario no encontrado',
            ];
        }

        $user->update($data);

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Usuario actualizado exitosamente',
            'data' => $user,
        ];
    }

    /**
     * Actualiza parcialmente un usuario existente.
     *
     * Útil para cambios parciales como contraseña, email o estado.
     *
     * @param array $data Datos validados a actualizar
     * @param int|string $id ID del usuario
     * @return array
     */
    public function partialUpdate(array $data, $id): array
    {
        $user = User::find($id);

        if (!$user) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Usuario no encontrado',
            ];
        }

        $user->update($data);

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Usuario actualizado parcialmente exitosamente',
            'data' => $user,
        ];
    }

    /**
     * Aprueba peticiones de usuarios en masa.
     *
     * Si async es true, el proceso se delega a un Job en segundo plano.
     * Si async es false, se ejecuta inmediatamente.
     *
     * @param array $userIds IDs de usuarios a aprobar
     * @param bool $async Ejecutar en background
     * @return array
     */
    public function approveRequests(array $userIds, bool $async = true): array
    {
        if ($async) {
            ApproveUserRequestsJob::dispatch($userIds, auth()->id());

            return [
                'error' => false,
                'code' => 202,
                'message' => 'Aprobación en proceso en background',
            ];
        }

        return $this->processApproveRequests($userIds, auth()->id());
    }

    /**
     * Cambia el estado de múltiples usuarios.
     *
     * Si async es true, el proceso se delega a un Job.
     *
     * @param array $userIds IDs de usuarios
     * @param int $stateUserId Nuevo estado
     * @param bool $async Ejecutar en background
     * @return array
     */
    public function changeUserStatus(array $userIds, int $stateUserId, bool $async = true): array
    {
        if ($async) {
            ChangeUserStatusJob::dispatch($userIds, $stateUserId, auth()->id());

            return [
                'error' => false,
                'code' => 202,
                'message' => 'Cambio de estado en proceso en background',
            ];
        }

        return $this->processChangeUserStatus($userIds, $stateUserId, auth()->id());
    }

    /**
     * Rechaza y elimina peticiones de usuarios.
     *
     * Si async es true, el proceso se delega a un Job.
     *
     * @param array $userIds IDs de usuarios a rechazar y eliminar
     * @param bool $async Ejecutar en background
     * @return array
     */
    public function rejectAndDeleteRequests(array $userIds, bool $async = true): array
    {
        if ($async) {
            RejectAndDeleteRequestsJob::dispatch($userIds, auth()->id());

            return [
                'error' => false,
                'code' => 202,
                'message' => 'Rechazo y eliminación en proceso en background',
            ];
        }

        return $this->processRejectAndDeleteRequests($userIds, auth()->id());
    }

    /* ------------------ Procesos internos ------------------ */

    /**
     * Procesa la aprobación de peticiones de usuarios.
     *
     * Solo cambia de estado a usuarios que actualmente estén en estado 'Peticion'.
     * También registra auditoría de cada cambio.
     *
     * @param array $userIds IDs de usuarios a aprobar
     * @param int|null $performedByUserId ID del usuario que realiza la acción
     * @return array
     */
    public function processApproveRequests(array $userIds, ?int $performedByUserId = null): array
    {
        DB::beginTransaction();

        try {
            $users = User::with('stateUser')->whereIn('id', $userIds)->get();

            if ($users->isEmpty()) {
                DB::rollBack();

                return [
                    'error' => true,
                    'code' => 404,
                    'message' => 'No se encontraron usuarios',
                ];
            }

            $authUser = $performedByUserId
                ? User::with('profile', 'roles')->find($performedByUserId)
                : auth()->user();

            $fullName = $authUser?->profile
                ? $authUser->profile->names . ' ' . $authUser->profile->last_names
                : 'Sistema';

            $role = $authUser?->getRoleNames()?->first() ?? 'Sistema';

            foreach ($users as $user) {
                if (($user->stateUser?->name ?? null) !== 'Peticion') {
                    continue;
                }

                $oldStatus = $user->stateUser?->name ?? 'SIN ESTADO';

                $user->update(['state_user_id' => 1]);
                $user->refresh()->load('stateUser');

                $newStatus = $user->stateUser?->name ?? 'SIN ESTADO';

                $user->audits()->create([
                    'user_name' => $fullName,
                    'rol_name' => $role,
                    'date_time' => now(),
                    'action_execute' => 'Aprobacion Peticion',
                    'status_old' => $oldStatus,
                    'status_new' => $newStatus,
                ]);
            }

            DB::commit();

            return [
                'error' => false,
                'code' => 200,
                'message' => 'Peticiones aprobadas correctamente',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'error' => true,
                'code' => 500,
                'message' => 'Error al aprobar peticiones',
            ];
        }
    }

    /**
     * Cambia el estado de uno o varios usuarios y registra auditoría.
     *
     * Este método puede ser usado tanto de forma síncrona como desde un Job.
     * Si se ejecuta desde un Job, se recomienda pasar el ID del usuario que
     * realizó la acción para mantener trazabilidad en auditoría.
     *
     * @param array $userIds IDs de usuarios a actualizar
     * @param int $stateUserId ID del nuevo estado
     * @param int|null $performedByUserId ID del usuario que ejecuta la acción
     * @return array
     *
     * @throws \Throwable
     */
    public function processChangeUserStatus(array $userIds, int $stateUserId, ?int $performedByUserId = null): array
    {
        DB::beginTransaction();

        try {
            $users = User::with('stateUser', 'profile.organization')->whereIn('id', $userIds)->get();

            if ($users->isEmpty()) {
                DB::rollBack();

                return [
                    'error' => true,
                    'code' => 404,
                    'message' => 'No se encontraron usuarios',
                ];
            }

            $authUser = $performedByUserId
                ? User::with('profile', 'roles')->find($performedByUserId)
                : auth()->user();

            $fullName = $authUser?->profile
                ? $authUser->profile->names . ' ' . $authUser->profile->last_names
                : 'Sistema';

            $role = $authUser?->getRoleNames()?->first() ?? 'Sistema';

            foreach ($users as $user) {
                $oldStatus = $user->stateUser?->name ?? 'SIN ESTADO';
                $oldStatusId = (int) $user->state_user_id;

                $user->update(['state_user_id' => $stateUserId]);
                $user->refresh()->load('stateUser');

                $newStatus = $user->stateUser?->name ?? 'SIN ESTADO';
                $newStatusId = (int) $stateUserId;

                $audit = $user->audits()->create([
                    'user_name' => $fullName,
                    'rol_name' => $role,
                    'date_time' => now(),
                    'action_execute' => 'Cambio de Estado',
                    'status_old' => $oldStatus,
                    'status_new' => $newStatus,
                ]);

                if ($oldStatusId !== $newStatusId && in_array($newStatusId, [1, 2])) {
                    
                    // Extraemos de forma segura el id de la seccional a la que pertenece el usuario editado
                    $sectionalId = $user->profile?->organization?->sectional_id;

                    if ($sectionalId) {
                        NotificationService::notifySupervisoresBySectional($sectionalId, $audit->id);
                        NotificationService::notifyAdminsBySectional($sectionalId, $audit->id);
                    }
                }
            }

            DB::commit();

            return [
                'error' => false,
                'code' => 200,
                'message' => 'Estados actualizados correctamente',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'error' => true,
                'code' => 500,
                'message' => 'Error al cambiar el estado de los usuarios',
            ];
        }
    }

    /**
     * Procesa el rechazo y eliminación de peticiones de usuarios.
     *
     * Solo elimina usuarios en estado 'Peticion'. Antes de eliminar,
     * registra auditoría para conservar trazabilidad.
     *
     * @param array $userIds IDs de usuarios a eliminar
     * @param int|null $performedByUserId ID del usuario que ejecuta la acción
     * @return array
     */
    public function processRejectAndDeleteRequests(array $userIds, ?int $performedByUserId = null): array
    {
        DB::beginTransaction();

        try {
            $users = User::with(['profile', 'stateUser'])->whereIn('id', $userIds)->get();

            if ($users->isEmpty()) {
                DB::rollBack();

                return [
                    'error' => true,
                    'code' => 404,
                    'message' => 'No se encontraron usuarios',
                ];
            }

            $authUser = $performedByUserId
                ? User::with('profile', 'roles')->find($performedByUserId)
                : auth()->user();

            $fullName = $authUser?->profile
                ? $authUser->profile->names . ' ' . $authUser->profile->last_names
                : 'Sistema';

            $role = $authUser?->getRoleNames()?->first() ?? 'Sistema';

            foreach ($users as $user) {
                if (($user->stateUser?->name ?? null) !== 'Peticion') {
                    continue;
                }

                $user->audits()->create([
                    'user_name' => $fullName,
                    'rol_name' => $role,
                    'date_time' => now(),
                    'action_execute' => 'Rechazar Peticion',
                    'status_old' => 'Peticion',
                    'status_new' => 'Rechazado y Eliminado',
                ]);

                if ($user->profile) {
                    $user->profile->delete();
                }

                $user->delete();
            }

            DB::commit();

            return [
                'error' => false,
                'code' => 200,
                'message' => 'Peticiones eliminadas correctamente',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'error' => true,
                'code' => 500,
                'message' => 'Error al eliminar usuarios',
            ];
        }
    }

    /**
     * Cambia el rol de un usuario y registra auditoría.
     *
     * @param array $data Debe contener la llave 'role'
     * @param int|string $id ID del usuario
     * @return array
     */
    public function changeRole(array $data, $id): array
    {
        $user = User::with(['roles'])->find($id);

        if (!$user) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Usuario no encontrado',
            ];
        }

        $oldRole = $user->roles->first()?->name;

        $user->syncRoles([$data['role']]);
        $user->refresh()->load('roles');

        $newRole = $user->roles->first()?->name;

        $authUser = auth()->user();
        $fullName = $authUser?->profile
            ? $authUser->profile->names . ' ' . $authUser->profile->last_names
            : 'Sistema';

        $role = $authUser?->getRoleNames()?->first() ?? 'Sistema';

        $user->audits()->create([
            'user_name' => $fullName,
            'rol_name' => $role,
            'date_time' => now(),
            'action_execute' => 'Cambio de Rol',
            'status_old' => $oldRole ?? 'SIN ROL',
            'status_new' => $newRole ?? 'SIN ROL',
        ]);

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Cambio de rol realizado exitosamente',
        ];
    }

    /**
     * Elimina una petición de usuario individual.
     *
     * Solo permite eliminar usuarios que aún estén en estado 'Peticion'.
     * Registra auditoría antes de eliminar los datos.
     *
     * @param int|string $id ID del usuario
     * @return array
     */
    public function delete($id): array
    {
        $user = User::with(['profile', 'stateUser'])->find($id);

        if (!$user) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Peticion no encontrada',
            ];
        }

        if ((int) $user->state_user_id !== 3) {
            return [
                'error' => true,
                'code' => 422,
                'message' => "Solo se pueden eliminar usuarios en estado 'Peticion'",
            ];
        }

        DB::beginTransaction();

        try {
            $authUser = auth()->user();
            $fullName = $authUser?->profile
                ? $authUser->profile->names . ' ' . $authUser->profile->last_names
                : 'Sistema';

            $role = $authUser?->getRoleNames()?->first() ?? 'Sistema';

            $user->audits()->create([
                'user_name' => $fullName,
                'rol_name' => $role,
                'date_time' => now(),
                'action_execute' => 'Rechazar Peticion',
                'status_old' => 'Peticion',
                'status_new' => 'Rechazado y Eliminado',
            ]);

            if ($user->profile) {
                $user->profile->delete();
            }

            $user->delete();

            DB::commit();

            return [
                'error' => false,
                'code' => 200,
                'message' => 'Peticion eliminada exitosamente',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'error' => true,
                'code' => 500,
                'message' => 'Error al eliminar la peticion',
            ];
        }
    }

    /**
     * Obtiene el historial de auditoría de un usuario.
     *
     * @param int|string $id ID del usuario
     * @param int $perPage Cantidad de registros por página
     * @return array
     */
    public function history($id, $perPage = 10): array
    {
        $user = User::find($id);

        if (!$user) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Usuario no encontrado',
            ];
        }

        $data = $user->audits()
            ->orderBy('date_time', 'desc')
            ->paginate($perPage);

        $history = $data->getCollection()->map(function ($audit) {
            return [
                'date_time' => $audit->date_time,
                'user_name' => $audit->user_name,
                'rol' => $audit->rol_name,
                'action_execute' => $audit->action_execute,
                'status_old' => $audit->status_old,
                'status_new' => $audit->status_new,
            ];
        });

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Historial de auditoría obtenido exitosamente',
            'data' => $history,
            'paginate' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
        ];
    }
}