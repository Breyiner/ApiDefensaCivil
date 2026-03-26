<?php

namespace App\Services\Resource;

use App\Models\Resource\Resource;
use App\Models\Audit\Audit;

class ResourceService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $resources = Resource::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de recursos obtenidos correctamente",
            "data" => $resources,
        ];
    }

    public function getById($id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Recurso obtenido correctamente",
            "data" => $resource,
        ];
    }

    public function create(array $data)
    {
        $resource = Resource::create($data);

        $resource->audits()->create([
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
            "message" => "Recurso creado correctamente",
            "data" => $resource,
        ];
    }

    public function update(array $data, $id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso no encontrado",
            ];
        }

        $oldStatus = $resource->is_active ? "Activo" : "Inactivo";
        $resource->update($data);

        $resource->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => $oldStatus,
            'status_new'     => $resource->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Recurso actualizado correctamente",
            "data" => $resource,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso no encontrado",
            ];
        }

        $oldStatus = $resource->is_active ? "Activo" : "Inactivo";
        $resource->update($data);

        $resource->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado parcialmente',
            'status_old'     => $oldStatus,
            'status_new'     => $resource->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Recurso actualizado parcialmente correctamente",
            "data" => $resource,
        ];
    }

    public function changeStatus(array $data, $id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso no encontrado",
            ];
        }

        if ($data['is_active'] == 0) {
            $activeCount = Resource::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $resource->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar este recurso, minimo un registro activo",
                ];
            }
        }

        $oldStatus = $resource->is_active ? "Activo" : "Inactivo";
        $resource->update($data);
        $newStatus = $resource->is_active ? "Activo" : "Inactivo";

        $resource->audits()->create([
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
            "message" => "Cambio de estado del recurso actualizado correctamente",
            "data" => $resource,
        ];
    }

    public function delete($id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso no encontrado",
            ];
        }

        $originalData = $resource->toArray();
        $resource->delete();

        $resource->audits()->create([
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
            "message" => "Recurso eliminado correctamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Recurso no encontrado",
            ];
        }

        $data = $resource->audits()
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
