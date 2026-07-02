<?php

namespace App\Services\Eps;

use App\Models\Eps\Eps;

class EpsService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $eps = Eps::all();

        if ($eps->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay registros de EPS",
                "data" => $eps,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de EPS obtenidos exitosamente",
            "data" => $eps,
        ];
    }

    public function getById($id)
    {
        $eps = Eps::find($id);

        if (!$eps) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "EPS no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "EPS obtenida exitosamente",
            "data" => $eps,
        ];
    }

    public function create(array $data)
    {
        $eps = Eps::create($data);

        $currentData = $eps->name;

        $eps->audits()->create([
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
            "message" => "EPS creada exitosamente",
            "data" => $eps,
        ];
    }

    public function update(array $data, $id)
    {
        $eps = Eps::find($id);

        if (!$eps) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "EPS no encontrada",
            ];
        }

        $oldData = $eps->name;

        $eps->update($data);

        $currentData = $eps->name;

        $eps->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => null,
            'status_new'     => null,
            'data_old'       => $oldData,
            'data_new'       => $currentData,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "EPS actualizada exitosamente",
            "data" => $eps,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $eps = Eps::find($id);

        if (!$eps) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "EPS no encontrada",
            ];
        }

        $oldData = $eps->name;

        $eps->update($data);

        $currentData = $eps->name;

        $eps->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado Parcialmente',
            'status_old'     => null,
            'status_new'     => null,
            'data_old'       => $oldData,
            'data_new'       => $currentData,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "EPS actualizada parcialmente exitosamente",
            "data" => $eps,
        ];
    }

    public function changeStatus($id)
    {
        $eps = Eps::find($id);

        if (!$eps) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "EPS no encontrada",
            ];
        }

        $oldStatus = $eps->is_active ? 'Activo' : 'Inactivo';

        $eps->is_active = !$eps->is_active;
        $eps->save();

        $newStatus = $eps->is_active ? 'Activo' : 'Inactivo';

        $eps->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Cambio de Estado',
            'status_old'     => $oldStatus,
            'status_new'     => $newStatus,
            'data_old'       => null,
            'data_new'       => null,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Estado de EPS cambiado exitosamente",
            "data" => $eps,
        ];
    }

    public function delete($id)
    {
        $eps = Eps::find($id);

        if (!$eps) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "EPS no encontrada",
            ];
        }

        $oldData = $eps->name;

        $eps->delete();

        $eps->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => null,
            'status_new'     => null,
            'data_old'       => $oldData,
            'data_new'       => null,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "EPS eliminada exitosamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $eps = Eps::find($id);

        if (!$eps) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "EPS no encontrada",
            ];
        }

        $data = $eps->audits()
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


