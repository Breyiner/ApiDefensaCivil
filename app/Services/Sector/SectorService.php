<?php

namespace App\Services\Sector;

use App\Models\Sector\Sector;
use App\Models\Audit\Audit;
use Illuminate\Support\Arr;

class SectorService
{
    public static function getAll()
    {
        $sector = Sector::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Sectores obtenidos exitosamente",
            "data" => $sector,
        ];
    }

    public function getById($id)
    {
        $sector = Sector::find($id);

        if (!$sector) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Sector no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Sector obtenido exitosamente",
            "data" => $sector,
        ];
    }

    public function create(array $data)
    {
        $sector = Sector::create($data);

        $currentData = $sector->name;

        $sector->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Creado',
            'status_old'     => null,
            'status_new'     => "Activo",
            'data_old'       => null,
            'data_new'       => $currentData,
        ]);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Sector creado exitosamente",
            "data" => $sector,
        ];
    }

    public function update(array $data, $id)
    {
        $sector = Sector::find($id);

        if (!$sector) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Sector no encontrado",
            ];
        }

        $oldStatus = $sector->is_active ? "Activo" : "Inactivo";
        $dataOld   = $sector->getOriginal('name');

        $sector->update($data);
        $sector->refresh();

        $newStatus = $sector->is_active ? "Activo" : "Inactivo";
        $dataNew   = $sector->name;

        $sector->audits()->create([
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
            "message" => "Sector actualizado exitosamente",
            "data" => $sector,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $sector = Sector::find($id);

        if (!$sector) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Sector no encontrado",
            ];
        }

        $oldStatus = $sector->is_active ? "Activo" : "Inactivo";
        $dataOld   = $sector->getOriginal('name');

        $sector->update($data);
        $sector->refresh();

        $newStatus = $sector->is_active ? "Activo" : "Inactivo";
        $dataNew   = $sector->name;

        $sector->audits()->create([
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
            "message" => "Sector actualizado parcialmente exitosamente",
            "data" => $sector,
        ];
    }

    public function changeStatus(array $data, $id)
    {
        $sector = Sector::find($id);

        if (!$sector) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Sector no encontrado",
            ];
        }

        if ($data['is_active'] == 0) {
            $activeCount = Sector::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $sector->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar este sector, minimo un registro activo",
                ];
            }
        }

        $oldStatus = $sector->is_active ? "Activo" : "Inactivo";
        $dataOld   = $sector->getOriginal('name');

        $sector->update($data);
        $sector->refresh();

        $newStatus = $sector->is_active ? "Activo" : "Inactivo";
        $dataNew   = $sector->name;

        $sector->audits()->create([
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
            "data" => $sector,
        ];
    }

    public function delete($id)
    {
        $sector = Sector::find($id);

        if (!$sector) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Sector no encontrado",
            ];
        }

        if ($sector->familyPlan->count()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar porque tiene registros relacionados",
            ];
        }

        $oldStatus = $sector->is_active ? "Activo" : "Inactivo";
        $dataOld   = $sector->name;

        $sector->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => $oldStatus,
            'status_new'     => null,
            'data_old'       => $dataOld,
            'data_new'       => null,
        ]);
            
        $sector->delete();
        
        return [
            "error" => false,
            "code" => 200,
            "message" => "Sector eliminado exitosamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $sector = Sector::find($id);

        if (!$sector) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Sector no encontrado",
            ];
        }

        $data = $sector->audits()
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
