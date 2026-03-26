<?php

namespace App\Services\Species;

use App\Models\Species\Species;
use App\Models\Audit\Audit;

class SpecieServices
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $species = Species::all();

        if ($species->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay registros de especies",
                "data" => $species,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de especies obtenidos exitosamente",
            "data" => $species,
        ];
    }

    public function getById($id)
    {
        $species = Species::find($id);

        if (!$species) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Especie no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Especie obtenida exitosamente",
            "data" => $species,
        ];
    }

    public function create(array $data)
    {
        $species = Species::create($data);

        $species->audits()->create([
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
            "message" => "Especie creada exitosamente",
            "data" => $species,
        ];
    }

    public function update(array $data, $id)
    {
        $species = Species::find($id);

        if (!$species) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Especie no encontrada",
            ];
        }

        $oldStatus = $species->is_active ? "Activo" : "Inactivo";
        $species->update($data);

        $species->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => $oldStatus,
            'status_new'     => $species->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Especie actualizada exitosamente",
            "data" => $species,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $species = Species::find($id);

        if (!$species) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Especie no encontrada",
            ];
        }

        $oldStatus = $species->is_active ? "Activo" : "Inactivo";
        $species->update($data);

        $species->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado parcialmente',
            'status_old'     => $oldStatus,
            'status_new'     => $species->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Especie actualizada parcialmente exitosamente",
            "data" => $species,
        ];
    }

    public function changeStatus(array $data, $id)
    {
        $species = Species::find($id);

        if (!$species) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Especie no encontrada",
            ];
        }

        if ($data['is_active'] == 0) {
            $activeCount = Species::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $species->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar esta especie, minimo un registro activo",
                ];
            }
        }

        $oldStatus = $species->is_active ? "Activo" : "Inactivo";
        $species->update($data);
        $newStatus = $species->is_active ? "Activo" : "Inactivo";

        $species->audits()->create([
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
            "message" => "Cambio de estado de la especie actualizado correctamente",
            "data" => $species,
        ];
    }

    public function delete($id)
    {
        $species = Species::find($id);

        if (!$species) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Especie no encontrada",
            ];
        }

        $originalData = $species->toArray();
        $species->delete();

        $species->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => $originalData['is_active'] ? "Activo" : "Inactivo",
            'status_new'     => null,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Especie eliminada exitosamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $species = Species::find($id);

        if (!$species) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Especie no encontrada",
            ];
        }

        $data = $species->audits()
            ->orderBy('date_time', 'desc')
            ->paginate($perPage);

        $history = $data->map(function($audit) {
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
