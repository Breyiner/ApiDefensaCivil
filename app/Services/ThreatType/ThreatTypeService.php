<?php

namespace App\Services\ThreatType;

use App\Models\ThreatType\ThreatType;
use App\Models\Audit\Audit;

class ThreatTypeService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $threatTypes = ThreatType::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Registros de tipos de amenaza obtenidos exitosamente",
            "data" => $threatTypes,
        ];
    }

    public function getById($id)
    {
        $threatType = ThreatType::find($id);

        if (!$threatType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de amenaza no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de amenaza obtenido exitosamente",
            "data" => $threatType,
        ];
    }

    public function create(array $data)
    {
        $threatType = ThreatType::create($data);

        $currentData = $threatType->name;

        $threatType->audits()->create([
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
            "message" => "Tipo de amenaza creado exitosamente",
            "data" => $threatType,
        ];
    }

    public function update(array $data, $id)
    {
        $threatType = ThreatType::find($id);

        if (!$threatType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de amenaza no encontrado",
            ];
        }

        $statusOld  = $threatType->is_active ? 'Activo' : 'Inactivo';
        $dataOld    = $threatType->getOriginal('name');

        $threatType->update($data);
        $threatType->refresh();

        $statusNew  = $threatType->is_active ? 'Activo' : 'Inactivo';
        $dataNew    = $threatType->name;


        $threatType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => $statusOld,
            'status_new'     => $statusNew,
            'data_old'       => $dataOld,
            'data_new'       => $dataNew,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de amenaza actualizado exitosamente",
            "data" => $threatType,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $threatType = ThreatType::find($id);

        if (!$threatType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de amenaza no encontrado",
            ];
        }

        $statusOld  = $threatType->is_active ? 'Activo' : 'Inactivo';
        $dataOld    = $threatType->getOriginal('name');

        $threatType->update($data);
        $threatType->refresh();

        $statusNew  = $threatType->is_active ? 'Activo' : 'Inactivo';
        $dataNew    = $threatType->name;


        $threatType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => $statusOld,
            'status_new'     => $statusNew,
            'data_old'       => $dataOld,
            'data_new'       => $dataNew,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de amenaza actualizado parcialmente exitosamente",
            "data" => $threatType,
        ];
    }

    public function changeStatus(array $data, $id)
    {
        $threatType = ThreatType::find($id);

        if (!$threatType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de amenaza no encontrado",
            ];
        }

        if ($data['is_active'] == 0) {
            $activeCount = ThreatType::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $threatType->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar este tipo de amenaza, minimo un registro activo",
                ];
            }
        } 

        $statusOld  = $threatType->is_active ? 'Activo' : 'Inactivo';
        $dataOld    = $threatType->getOriginal('name');

        $threatType->update($data);
        $threatType->refresh();

        $statusNew  = $threatType->is_active ? 'Activo' : 'Inactivo';
        $dataNew    = $threatType->name;


        $threatType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Cambio de estado',
            'status_old'     => $statusOld,
            'status_new'     => $statusNew,
            'data_old'       => $dataOld,
            'data_new'       => $dataNew,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Cambio de estado del tipo de amenaza actualizado correctamente",
            "data" => $threatType,
        ];
    }

    public function delete($id)
    {
        $threatType = ThreatType::find($id);

        if (!$threatType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de amenaza no encontrado",
            ];
        }

        $statusOld  = $threatType->is_active ? 'Activo' : 'Inactivo';
        $dataOld    = $threatType->getOriginal('name');

        $threatType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => $statusOld,
            'status_new'     => null,
            'data_old'       => $dataOld,
            'data_new'       => null,
        ]);
            
        $threatType->delete();
            
        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de amenaza eliminado exitosamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $threatType = ThreatType::find($id);

        if (!$threatType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de amenaza no encontrado",
            ];
        }

        $data = $threatType->audits()
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
