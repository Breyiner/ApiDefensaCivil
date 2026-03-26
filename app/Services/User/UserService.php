<?php

namespace App\Services\User;

use App\Models\User\User;
use Illuminate\support\Arr;

/**
 * Servicio para la gestión de cuentas de usuario.
 * Maneja las credenciales y la vinculación con la tabla de perfiles.
 */
class UserService
{
    /**
     * Obtiene la lista de todos los usuarios (credenciales) con paginación.
     * 
     * 🔹 Aplica filtros automáticos por rol usando el scope forAuthUser
     * 
     * @param int $perPage Cantidad de registros por página (default: 15)
     * @return array Respuesta con datos paginados y metadata
     */
    public static function getAll(int $perPage = 15)
    {
        $paginator = User::forAuthUser()
            ->with([
                'profile.gender',
                'profile.documentType',
                'profile.organization.sectional',
                'roles',
                'stateUser'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->map(function ($user) {
            $profile = $user->profile;
            $role = $user->roles->first();

            return [
                "id" => $user->id,
                "email" => $user->email,
                "full_name" => $profile ? $profile->names . ' ' . $profile->last_names : 'N/A',
                "document_number" => $profile && $profile->documentType 
                    ? $profile->document_number . ' ' . $profile->documentType->acronym 
                    : 'N/A',
                "organization" => $profile?->organization?->name,
                "sectional" => $profile?->organization?->sectional?->name,
                "rol" => $role?->name,
                "rol_id" => $role?->id,
                "status" => $user->stateUser?->name,
                "status_id" => $user->state_user_id,
            ];
        });

        return [
            "error" => false,
            "code" => 200,
            "message" => $items->isEmpty()
                ? "No hay usuarios registrados"
                : "Usuarios obtenidos exitosamente",
            "data" => $items,
            "paginate" => [
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
     * Obtiene un usuario específico por su ID.
     */
    public function getById($id)
    {
        $user = User::forAuthUser()
            ->with([
                'profile.gender',
                'profile.documentType',
                'profile.organization.sectional',
                'roles',
                'stateUser'
            ])->find($id);

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        $profile = $user->profile;
        $role = $user->roles->first();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Usuario obtenido exitosamente",
            "data" => [
                "id" => $user->id,
                "email" => $user->email,

                "names" => $profile?->names,
                "last_names" => $profile?->last_names,
                "document_type" => $profile?->documentType?->name,
                "document_number" => $profile?->document_number,
                "birth_date" => $profile?->birth_date,
                "gender" => $profile?->gender?->name,
                "phone" => $profile?->phone,
                "organization" => $profile?->organization?->name,
                "sectional" => $profile?->organization?->sectional?->name,

                "rol_id" => $role?->id,
                "rol" => $role?->name,

                "status_id" => $user->stateUser?->id,
                "status" => $user->stateUser?->name
            ],
        ];
    }

    /**
     * Obtiene las peticiones de usuarios pendientes (state_user_id = 3).
     * 
     * 🔹 Administradores ven todas las peticiones
     * 🔹 Supervisores solo ven peticiones de su seccional
     * 
     * @param int $perPage Cantidad de registros por página (default: 10)
     * @return array Respuesta con datos paginados
     */
    public function getRequestsAdmins(int $perPage = 10)
    {
        $paginator = User::with(['profile.organization.sectional', 'profile.documentType'])
            ->where('state_user_id', 3)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->map(function ($item) {
            return [
                "id" => $item->id,
                "full_name" => $item->profile->names.' '.$item->profile->last_names,
                "email" => $item->email,
                "organization" => $item->profile->organization->name,
                "sectional" => $item->profile->organization->sectional->name,
                "document_number" => $item->profile->document_number.' '.$item->profile->documentType->acronym,
            ];
        });
        
        return [
            "error" => false,
            "code" => 200,
            "message" => $items->isEmpty()
                ? "No hay peticiones de usuarios para acceder al sistema"
                : "Peticiones de usuarios para acceder al sistema obtenidos exitosamente",
            "data" => $items,
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
     * Obtiene las peticiones de usuarios para supervisores.
     * Solo muestra peticiones de la misma seccional del supervisor.
     * 
     * @param int $perPage Cantidad de registros por página (default: 10)
     * @return array Respuesta con datos paginados
     */
    public function getRequestsSupervisors(int $perPage = 10)
    {
        $authUser = auth()->user();

        // Obtener la sectional del usuario autenticado
        $authSectionalId = $authUser->profile->organization->sectional_id;

        $paginator = User::with([
                'profile.organization.sectional',
                'profile.documentType'
            ])
            ->where('state_user_id', 3)
            ->whereHas('profile.organization', function ($query) use ($authSectionalId) {
                $query->where('sectional_id', $authSectionalId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->map(function ($item) {
            return [
                "id" => $item->id,
                "full_name" => $item->profile->names . ' ' . $item->profile->last_names,
                "email" => $item->email,
                "organization" => $item->profile->organization->name,
                "sectional" => $item->profile->organization->sectional->name,
                "document_number" => $item->profile->document_number . ' ' . $item->profile->documentType->acronym,
            ];
        });

        return [
            "error" => false,
            "code" => 200,
            "message" => $items->isEmpty()
                ? "No hay peticiones de usuarios para acceder al sistema"
                : "Peticiones de usuarios para acceder al sistema obtenidos exitosamente",
            "data" => $items,
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
     * Obtiene usuarios para administradores.
     * Excluye usuarios con estado 3 (peticiones) y rol 1 (administradores).
     * 
     * @param int $perPage Cantidad de registros por página (default: 10)
     * @return array Respuesta con datos paginados
     */
    public function getUserForAdmins(int $perPage = 10)
    {
        $paginator = User::with([
            'profile.organization.sectional',
            'profile.documentType',
            'stateUser',
            'roles'
        ])
        ->where('state_user_id','!=', 3)
        ->whereDoesntHave('roles', function ($q) {
            $q->where('id', 1);
        })
        ->orderBy('users.created_at', 'desc')
        ->paginate($perPage);

        $items = $paginator->map(function ($item) {
            return [
                "id" => $item->id,
                "full_name" => $item->profile->names.' '.$item->profile->last_names,
                "email" => $item->email,
                "organization" => $item->profile->organization->name,
                "sectional" => $item->profile->organization->sectional->name,
                "document_number" => $item->profile->document_number.' '.$item->profile->documentType->acronym,
                "state_user" => $item->stateUser->name,
                "state_user_id" => $item->state_user_id,
                "rol" => $item->getRoleNames()->first(),
                "rol_id" => $item->roles->first()->id,
            ];
        });
        
        return [
            "error" => false,
            "code" => 200,
            "message" => $items->isEmpty()
                ? "No hay usuarios disponibles"
                : "Usuarios obtenidos exitosamente",
            "data" => $items,
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
     * Obtiene usuarios para supervisores.
     * Solo muestra usuarios de la misma seccional, excluye roles 1 y 2.
     * 
     * @param int $perPage Cantidad de registros por página (default: 10)
     * @return array Respuesta con datos paginados
     */
    public function getUserForSupervisors(int $perPage = 10)
    {
        $authUser = auth()->user();

        // Obtener la sectional del usuario autenticado
        $authSectionalId = $authUser->profile->organization->sectional_id;

        $paginator = User::with([
                'profile.organization.sectional',
                'profile.documentType',
                'stateUser',
                'roles'
            ])
            ->where('state_user_id','!=', 3)
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

        $items = $paginator->map(function ($item) {
            return [
                "id" => $item->id,
                "full_name" => $item->profile->names.' '.$item->profile->last_names,
                "email" => $item->email,
                "organization" => $item->profile->organization->name,
                "sectional" => $item->profile->organization->sectional->name,
                "document_number" => $item->profile->document_number.' '.$item->profile->documentType->acronym,
                "state_user" => $item->stateUser->name,
                "state_user_id" => $item->state_user_id,
                "rol" => $item->getRoleNames()->first(),
                "rol_id" => optional($item->roles->first())->id,
            ];
        });
        
        return [
            "error" => false,
            "code" => 200,
            "message" => $items->isEmpty()
                ? "No hay usuarios disponibles"
                : "Usuarios obtenidos exitosamente",
            "data" => $items,
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
     * 🔹 Aplica el scope forAuthUser para respetar permisos por rol
     * 
     * @param int $statusId ID del estado por el cual filtrar
     * @param int $perPage Cantidad de registros por página (default: 10)
     * @return array Respuesta con datos paginados
     */
    public function getByStatus(int $statusId, int $perPage = 10)
    {
        $paginator = User::forAuthUser()
            ->with([
                'profile.organization.sectional',
                'profile.documentType',
                'stateUser',
                'roles'
            ])
            ->where('state_user_id', $statusId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $paginator->map(function ($user) {
            $profile = $user->profile;
            $role = $user->roles->first();

            return [
                "id" => $user->id,
                "full_name" => $profile ? $profile->names . ' ' . $profile->last_names : 'N/A',
                "email" => $user->email,
                "organization" => $profile?->organization?->name,
                "sectional" => $profile?->organization?->sectional?->name,
                "document_number" => $profile && $profile->documentType 
                    ? $profile->document_number . ' ' . $profile->documentType->acronym 
                    : 'N/A',
                "status" => $user->stateUser?->name,
                "status_id" => $user->state_user_id,
                "rol" => $role?->name,
                "rol_id" => $role?->id,
            ];
        });

        return [
            "error" => false,
            "code" => 200,
            "message" => $items->isEmpty()
                ? "No hay usuarios con este estado"
                : "Usuarios obtenidos exitosamente",
            "data" => $items,
            "paginate" => [
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
     * Importante: Los datos deben incluir email, password (hasheado) y state_user_id.
     */
    public function create(array $data)
    {
        $user = User::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Usuario creado exitosamente",
            "data" => $user,
        ];
    }

    /**
     * Actualización total de la cuenta de usuario.
     */
    public function update(array $data, $id)
    {
        $user = User::find($id);

        if (!$user){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        $user->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Usuario actualizado exitosamente",
            "data" => $user,
        ];
    }

    /**
     * Actualización parcial (ej. cambio de contraseña o cambio de estado).
     */
    public function partialUpdate(array $data,$id)
    {
        $user = User::find($id);

        if (!$user){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        $user->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Usuario actualizado parcialmente exitosamente",
            "data" => $user,
        ];
    }

    public function changeStatus(array $data,$id)
    {
        $user = User::with(['stateUser'])->find($id);

        if (!$user){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }
        
        $oldStatus = $user->stateUser->name;
        $user->update($data);
        $user->refresh()->load('stateUser');
        $newStatus = $user->stateUser->name;    

        $user->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => $oldStatus == 'Peticion' ? 'Aprobacion Peticion' : 'Cambio de Estado',
            'status_old'     => $oldStatus,
            'status_new'     => $user->stateUser->name,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Aprobacion de usuario realizada exitosamente",
        ];
    }

    /**
     * Elimina el usuario validando que no tenga un perfil asociado.
     * Si el perfil existe, se recomienda eliminar primero el perfil o usar soft deletes.
     */
    public function changeRole(array $data, $id)
    {
        $user = User::with(['roles'])->find($id);

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        $oldRole = $user->roles->first()?->name;

        $user->syncRoles([$data['role']]);

        $user->refresh()->load('roles');

        $newRole = $user->roles->first()?->name;

        $user->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Cambio de Rol',
            'status_old'     => $oldRole,
            'status_new'     => $newRole,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Cambio de rol realizado exitosamente",
        ];
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (!$user){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Peticion no encontrada",
            ];
        }
        if ($user->state_user_id !== 3)
        {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El usuario ya fue aprobado no se puede eliminar",
            ];
        }
        // Validación de integridad: Un usuario no puede ser borrado si tiene un perfil humano activo
        $profile = $user->profile;
        
        $profile->delete();

        $user->delete();
        $user->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Rechazar Peticion',
            'status_old'     => 'Peticion',
            'status_new'     => 'Rechazado y Eliminado',
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Peticion eliminada exitosamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $user = User::find($id);

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        $data = $user->audits()
            ->orderBy('date_time', 'desc')
            ->paginate($perPage);

        $history = $data->map(fn($audit) => [
                'date_time'      => $audit->date_time,
                'user_name'      => $audit->user_name,
                'rol'            => $audit->rol_name,
                'action_execute' => $audit->action_execute,
                'status_old'     => $audit->status_old,
                'status_new'     => $audit->status_new,
            ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Historial de auditoría obtenido exitosamente",
            "data" => $history,
            "paginate" => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ];
    }
}