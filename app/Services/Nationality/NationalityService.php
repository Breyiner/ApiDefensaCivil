<?php

namespace App\Services\Nationality;

use App\Models\Nationality\Nationality;
use App\Models\Audit\Audit;
use Illuminate\Support\Arr;

/**
 * Servicio encargado de la gestión de las nacionalidades del sistema.
 */
class NationalityService
{
    public static function getAll()
    {
        $nationality = Nationality::all();
        
        return [
            "error" => false,
            "code" => 200,
            "message" => "Nacionalidades obtenidas exitosamente",
            "data" => $nationality,
        ];
    }

    public function getById($id)
    {
        $nationality = Nationality::find($id);

        if (!$nationality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Nacionalidad no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Nacionalidad obtenida exitosamente",
            "data" => $nationality,
        ];
    }

    public function create(array $data)
    {
        $nationality = Nationality::create($data);

        
        $currentData = $nationality->name;

        $nationality->audits()->create([
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
            "message" => "Nacionalidad creada exitosamente",
            "data" => $nationality,
        ];
    }

    public function update(array $data, $id)
    {
        $nationality = Nationality::find($id);

        if (!$nationality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Nacionalidad no encontrada",
            ];
        }

        $statusOld  = $nationality->is_active ? 'Activo' : 'Inactivo';
        $dataOld    = $nationality->getOriginal('name');

        $nationality->update($data);
        $nationality->refresh();

        $statusNew  = $nationality->is_active ? 'Activo' : 'Inactivo';
        $dataNew    = $nationality->name;


        $nationality->audits()->create([
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
            "message" => "Nacionalidad actualizada exitosamente",
            "data" => $nationality,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $nationality = Nationality::find($id);

        if (!$nationality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Nacionalidad no encontrada",
            ];
        }

        $statusOld  = $nationality->is_active ? 'Activo' : 'Inactivo';
        $dataOld    = $nationality->getOriginal('name');

        $nationality->update($data);
        $nationality->refresh();

        $statusNew  = $nationality->is_active ? 'Activo' : 'Inactivo';
        $dataNew    = $nationality->name;


        $nationality->audits()->create([
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
            "message" => "Nacionalidad actualizada parcialmente exitosamente",
            "data" => $nationality,
        ];
    }

    public function changeStatus(array $data, $id)
    {
        $nationality = Nationality::find($id);

        if (!$nationality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Nacionalidad no encontrada",
            ];
        }

        if ($data['is_active'] == 0) {
            $activeCount = Nationality::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $nationality->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar esta nacionalidad, minimo un registro activo",
                ];
            }
        }  

        $statusOld  = $nationality->is_active ? 'Activo' : 'Inactivo';
        $dataOld    = $nationality->getOriginal('name');

        $nationality->update($data);
        $nationality->refresh();

        $statusNew  = $nationality->is_active ? 'Activo' : 'Inactivo';
        $dataNew    = $nationality->name;


        $nationality->audits()->create([
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
            "message" => "Cambio de estado de la nacionalidad actualizado correctamente",
            "data" => $nationality,
        ];
    }

    public function delete($id)
    {
        $nationality = Nationality::find($id);

        if (!$nationality){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Nacionalidad no encontrada",
            ];
        }

        $statusOld  = $nationality->is_active ? 'Activo' : 'Inactivo';
        $dataOld    = $nationality->getOriginal('name');


        $nationality->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => $statusOld,
            'status_new'     => null,
            'data_old'       => $dataOld,
            'data_new'       => null,
        ]);

        $nationality->delete();
            
        return [
            "error" => false,
            "code" => 200,
            "message" => "Nacionalidad eliminada exitosamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $nationality = Nationality::find($id);

        if (!$nationality) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Nacionalidad no encontrada",
            ];
        }

        $data = $nationality->audits()
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
