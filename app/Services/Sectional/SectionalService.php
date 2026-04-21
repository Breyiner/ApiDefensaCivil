<?php

namespace App\Services\Sectional;

use App\Models\Sectional\Sectional;

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

        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Creado',
            'status_old'     => null,
            'status_new'     => 'Activo',
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

        $sectional->update($data);

        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => $oldStatus,
            'status_new'     => $sectional->is_active ? "Activo" : "Inactivo",
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

        $sectional->update($data);

        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado parcialmente',
            'status_old'     => $oldStatus,
            'status_new'     => $sectional->is_active ? "Activo" : "Inactivo",
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

        $sectional->update($data);

        $newStatus = $sectional->is_active ? "Activo" : "Inactivo";

        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Cambio de estado',
            'status_old'     => $oldStatus,
            'status_new'     => $newStatus,
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

        $originalData = $sectional->toArray();

        // Guardamos auditoría antes de eliminar
        $sectional->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => $originalData['is_active'] ? "Activo" : "Inactivo",
            'status_new'     => null,
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
}
