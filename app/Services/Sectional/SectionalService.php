<?php

namespace App\Services\Sectional;

use App\Models\FamilyPlan\FamilyPlan;
use App\Models\Sectional\Sectional;
use App\Models\User\User;

class SectionalService
{
    /**
     * Obtener todas
     */
    public static function getAll()
    {
        $sectional = Sectional::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Seccionales obtenidas exitosamente",
            "data" => $sectional,
        ];
    }

    /**
     * Obtener las seccionales activas y que tiene al menos una organizacion asociada
     */
    public function getActiveWithOrganization()
    {
        $sectionals = Sectional::active()->withOrganizations()->get();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Seccionales activas obtenidas exitosamente",
            "data" => $sectionals,
        ];
    }

    /**
     * Obtener por ID
     */
    public function getById($id)
    {
        $sectional = Sectional::find($id);

        if (!$sectional) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Seccional no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Seccional obtenida exitosamente",
            "data" => $sectional,
        ];
    }

    /**
     * Crear
     */
    public function create(array $data)
    {
        $sectional = Sectional::create($data);
        $currentData = $sectional->name;

        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Creado',
            'status_old'     => null,
            'status_new'     => 'Activo',
            'data_old'       => null,
            'data_new'       => $currentData,
        ]);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Seccional creada exitosamente",
            "data" => $sectional,
        ];
    }

    /**
     * Update completo
     */
    public function update(array $data, $id)
    {
        $sectional = Sectional::find($id);

        if (!$sectional) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Seccional no encontrada",
            ];
        }

        $oldStatus = $sectional->is_active ? "Activo" : "Inactivo";
        $dataOld = $sectional->getOriginal('name');

        $sectional->update($data);

        $newStatus  = $sectional->is_active ? 'Activo' : 'Inactivo';
        $dataNew = $sectional->name;

        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => $oldStatus,
            'status_new'     => $newStatus,
            'data_old'       => $dataOld,
            'data_new'       => $dataNew,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Seccional actualizada exitosamente",
            "data" => $sectional,
        ];
    }

    /**
     * Update parcial
     */
    public function partialUpdate(array $data, $id)
    {
        $sectional = Sectional::find($id);

        if (!$sectional) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Seccional no encontrada",
            ];
        }

        $oldStatus = $sectional->is_active ? "Activo" : "Inactivo";
        $dataOld = $sectional->getOriginal('name');

        $sectional->update($data);

        $newStatus  = $sectional->is_active ? 'Activo' : 'Inactivo';
        $dataNew = $sectional->name;

        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado parcialmente',
            'status_old'     => $oldStatus,
            'status_new'     => $newStatus,
            'data_old'       => $dataOld,
            'data_new'       => $dataNew,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Seccional actualizada parcialmente exitosamente",
            "data" => $sectional,
        ];
    }

    /**
     * Cambio de estado
     */
    public function changeStatus(array $data, $id)
    {
        $sectional = Sectional::find($id);

        if (!$sectional) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Seccional no encontrada",
            ];
        }

        // Validación: si están intentando desactivar
        if ($data['is_active'] == 0) {
            $activeCount = Sectional::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $sectional->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar esta seccional, minimo un registro activo",
                ];
            }
        }

        $oldStatus = $sectional->is_active ? "Activo" : "Inactivo";
        $dataOld = $sectional->getOriginal('name');

        $sectional->update($data);

        $newStatus  = $sectional->is_active ? 'Activo' : 'Inactivo';
        $dataNew = $sectional->name;

        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Cambio de estado',
            'status_old'     => $oldStatus,
            'status_new'     => $newStatus,
            'data_old'       => $dataOld,
            'data_new'       => $dataNew,

        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Cambio de estado actualizado correctamente",
            "data" => $sectional,
        ];
    }

    /**
     * Eliminar
     */
    public function delete($id)
    {
        $sectional = Sectional::find($id);

        if (!$sectional) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Seccional no encontrada",
            ];
        }

        if ($sectional->organizations()->exists()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar porque tiene organizaciones relacionadas",
            ];
        }

        if ($sectional->familyPlans()->exists()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar porque tiene planes familiares relacionados",
            ];
        }

        $oldStatus = $sectional->is_active ? "Activo" : "Inactivo";
        $dataOld = $sectional->getOriginal('name');
        // $originalData = $sectional->toArray();

        // Guardamos auditoría antes de eliminar
        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => $oldStatus,
            'status_new'     => null,
            'data_old'       => $dataOld,
            'data_new'       => null,
        ]);

        $sectional->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Seccional eliminada exitosamente",
        ];
    }

    /**
     * Historial
     */
    public function history($id, $perPage = 10)
    {
        $sectional = Sectional::find($id);

        if (!$sectional) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Seccional no encontrada",
            ];
        }

        $data = $sectional->audits()
            ->orderBy('date_time', 'desc')
            ->paginate($perPage);

        $history = $data->map(function ($audit) {
            return [
                'date_time'      => $audit->date_time,
                'user_name'      => $audit->user_name,
                'rol'            => $audit->rol_name,
                'action_execute' => $audit->action_execute,
                'status_old'     => $audit->status_old,
                'status_new'     => $audit->status_new,
                'data_old'       => $audit->data_old,
                'data_new'       => $audit->data_new,
                'id'             => $audit->id
            ];
        });

        return [
            "error" => false,
            "code" => 200,
            "message" => "Historial obtenido exitosamente",
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


    public function getStatsSupervisor(int $id): array
    {

        $user = auth()->user();

        if ($user && $user->roles()->whereIn('id', [1, 3])->exists()) {
            return [
                'error'   => true,
                'code'    => 403,
                'message' => 'Acceso denegado: Los voluntarios y administradores no tienen permisos para consultar estadísticas del supervisor.',
            ];
        }

        if ($user && $user->roles()->where('id', 2)->exists()) {
            $userSectionalId = $user->profile?->organization?->sectional_id;

            if (!$userSectionalId || (int)$userSectionalId !== $id) {
                return [
                    'error'   => true,
                    'code'    => 403,
                    'message' => 'Acceso denegado: No tienes permisos para ver las estadísticas de otra seccional.',
                ];
            }
        }

        $sectional = Sectional::find($id);

        if (!$sectional) {
            return [
                'error'   => true,
                'code'    => 404,
                'message' => 'Seccional no encontrada',
            ];
        }

        // $planes = FamilyPlan::where('sectional_id', $id)->with('city')->get();
        $planes = FamilyPlan::whereHas('user.profile.organization', function ($query) use ($id) {
            $query->where('sectional_id', $id);
        })->with('city')->get();

        $totalPlanes = $planes->count();
        $totalAprobados = $planes->where('status_plan_id', 7)->count();
        $totalRechazados = $planes->where('status_plan_id', 6)->count();
        $totalPendientes = $planes->where('status_plan_id', 4)->count();

        $dona = [
            'aprobados'  => $totalAprobados,
            'pendientes' => $totalPendientes,
            'rechazados' => $totalRechazados,
        ];

        $familiasVulnerables = $planes->where('family_type_id', 1)->count();
        $familiasNoVulnerables = $planes->where('family_type_id', 2)->count();

        $tiposFamilias = [
            'Familias Vulnerables' => $familiasVulnerables,
            'Familias no Vulnerables' => $familiasNoVulnerables,
        ];

        $ciudades = $planes->groupBy('city_id')->map(function ($grupo) {

            $ciudad = $grupo->first()->city;

            return [
                'city_id'   => $ciudad?->id,
                'city'      => $ciudad?->name ?? 'Sin ciudad',
                'department' => $ciudad->department?->name ?? 'Sin departamento',
                'total'     => $grupo->count(),
                'aprobados' => $grupo->where('status_plan_id', 7)->count(),
            ];
        })
            ->values();

        $voluntariosAct = User::whereHas('roles', function ($rol) {
            $rol->where('id', 3);
        })->where('state_user_id', 1)->whereHas('profile.organization', function ($rol) use ($id) {
            $rol->where('sectional_id', $id);
        })->count();

        $promedioVol = $voluntariosAct > 0
            ? round($totalPlanes / $voluntariosAct, 2) : 0;

        return [
            'error'   => false,
            'code'    => 200,
            'message' => 'Estadísticas de la seccional obtenidas exitosamente',
            'data'    => [
                'total_planes'         => $totalPlanes,
                'total_aprobados'      => $totalAprobados,
                'familias_registradas' => $tiposFamilias,
                'dona'                 => $dona,
                'por_ciudad'           => $ciudades,
                'voluntarios_activos'  => $voluntariosAct,
                'promedio_planes_por_voluntario' => $promedioVol,

            ],
        ];
    }
}
