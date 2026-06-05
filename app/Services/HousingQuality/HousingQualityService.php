<?php

namespace App\Services\HousingQuality;

use App\Models\HousingQuality\HousingQuality;
use App\Models\Audit\Audit;
use Illuminate\Support\Arr;

class HousingQualityService
{
    public static function getAll()
    {
        $housingQuality = HousingQuality::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Calidades de vivienda obtenidas exitosamente",
            "data" => $housingQuality,
        ];
    }

    public function getById($id)
    {
        $housingQuality = HousingQuality::find($id);

        if (!$housingQuality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Calidad de vivienda no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Calidad de vivienda obtenida exitosamente",
            "data" => $housingQuality,
        ];
    }

    public function create(array $data)
    {
        $housingQuality = HousingQuality::create($data);

        $currentData = $housingQuality->name;

        $housingQuality->audits()->create([
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
            "message" => "Calidad de vivienda creada exitosamente",
            "data" => $housingQuality,
        ];
    }

    public function update(array $data, $id)
    {
        $housingQuality = HousingQuality::find($id);

        if (!$housingQuality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Calidad de vivienda no encontrada",
            ];
        }

        $oldStatus = $housingQuality->is_active ? "Activo" : "Inactivo";
        $dataOld   = $housingQuality->getOriginal('name');

        $housingQuality->update($data);
        $housingQuality->refresh();

        $newStatus = $housingQuality->is_active ? "Activo" : "Inactivo";
        $dataNew   = $housingQuality->name;

        $housingQuality->audits()->create([
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
            "message" => "Calidad de vivienda actualizada exitosamente",
            "data" => $housingQuality,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $housingQuality = HousingQuality::find($id);

        if (!$housingQuality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Calidad de vivienda no encontrada",
            ];
        }

        $oldStatus = $housingQuality->is_active ? "Activo" : "Inactivo";
        $dataOld   = $housingQuality->getOriginal('name');

        $housingQuality->update($data);
        $housingQuality->refresh();

        $newStatus = $housingQuality->is_active ? "Activo" : "Inactivo";
        $dataNew   = $housingQuality->name;

        $housingQuality->audits()->create([
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
            "message" => "Calidad de vivienda actualizada parcialmente exitosamente",
            "data" => $housingQuality,
        ];
    }

    public function changeStatus(array $data, $id)
    {
        $housingQuality = HousingQuality::find($id);

        if (!$housingQuality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Calidad de vivienda no encontrada",
            ];
        }

        if ($data['is_active'] == 0) {
            $activeCount = HousingQuality::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $housingQuality->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar esta calidad de vivienda, minimo un registro activo",
                ];
            }
        }    

        $oldStatus = $housingQuality->is_active ? "Activo" : "Inactivo";
        $dataOld   = $housingQuality->getOriginal('name');

        $housingQuality->update($data);
        $housingQuality->refresh();

        $newStatus = $housingQuality->is_active ? "Activo" : "Inactivo";
        $dataNew   = $housingQuality->name;

        $housingQuality->audits()->create([
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
            "data" => $housingQuality,
        ];
    }

    public function delete($id)
    {
        $housingQuality = HousingQuality::find($id);

        if (!$housingQuality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Calidad de vivienda no encontrada",
            ];
        }

        if ($housingQuality->familyPlan->count()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar porque tiene registros relacionados",
            ];
        }

        $oldStatus = $housingQuality->is_active ? "Activo" : "Inactivo";
        $dataOld   = $housingQuality->name;

        // CORRECCIÓN CRÍTICA: Primero se guarda el historial de auditoría y luego se elimina físicamente de la BD
        $housingQuality->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => $oldStatus,
            'status_new'     => null,
            'data_old'       => $dataOld,
            'data_new'       => null,
        ]);

        $housingQuality->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Calidad de vivienda eliminada exitosamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $housingQuality = HousingQuality::find($id);

        if (!$housingQuality) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Calidad de vivienda no encontrada",
            ];
        }

        $data = $housingQuality->audits()
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
                    'subData_old'    => $audit->subData_old,
                    'subData_new'    => $audit->subData_new,
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
