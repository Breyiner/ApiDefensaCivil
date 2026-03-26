<?php

namespace App\Services\DocumentType;

use App\Models\DocumentType\DocumentType;
use App\Models\Audit\Audit;
use Illuminate\Support\Arr;

class DocumentTypeService
{
    public static function getAll()
    {
        $documentType = DocumentType::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipos de documento obtenidos exitosamente",
            "data" => $documentType,
        ];
    }

    public function getById($id)
    {
        $documentType = DocumentType::find($id);

        if (!$documentType){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de documento no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de documento obtenido exitosamente",
            "data" => $documentType,
        ];
    }

    public function create(array $data)
    {
        $documentType = DocumentType::create($data);

        $documentType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Creado',
            'status_old'     => null,
            'status_new'     => "Activo",
        ]);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Tipo de documento creado exitosamente",
            "data" => $documentType,
        ];
    }

    public function update(array $data, $id)
    {
        $documentType = DocumentType::find($id);

        if (!$documentType){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de documento no encontrado",
            ];
        }

        $oldStatus = $documentType->is_active ? "Activo" : "Inactivo";

        $documentType->update($data);

        $documentType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => $oldStatus,
            'status_new'     => $documentType->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de documento actualizado exitosamente",
            "data" => $documentType,
        ];
    }

    public function partialUpdate(array $data,$id)
    {
        $documentType = DocumentType::find($id);

        if (!$documentType){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de documento no encontrado",
            ];
        }

        $oldStatus = $documentType->is_active ? "Activo" : "Inactivo";

        $documentType->update($data);

        $documentType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado parcialmente',
            'status_old'     => $oldStatus,
            'status_new'     => $documentType->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de documento actualizado parcialmente exitosamente",
            "data" => $documentType,
        ];
    }

    public function changeStatus(array $data,$id)
    {
        $documentType = DocumentType::find($id);

        if (!$documentType){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de documento no encontrado",
            ];
        }

        if ($data['is_active'] == 0) {
            $activeCount = DocumentType::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $documentType->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar este tipo de documento, minimo un registro activo",
                ];
            }
        }

        $oldStatus = $documentType->is_active ? "Activo" : "Inactivo";

        $documentType->update($data);

        $documentType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Cambio de estado',
            'status_old'     => $oldStatus,
            'status_new'     => $documentType->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Cambio de estado actualizado correctamente",
            "data" => $documentType,
        ];
    }

    public function delete($id)
    {
        $documentType = DocumentType::find($id);

        if (!$documentType){
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de documento no encontrado",
            ];
        }

        if ($documentType->profile->count()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar porque tiene registros relacionados",
            ];
        }

        $oldStatus = $documentType->is_active ? "Activo" : "Inactivo";

        $documentType->delete();

        $documentType->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Eliminado',
            'status_old'     => $oldStatus,
            'status_new'     => null,
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de documento eliminado exitosamente",
        ];
    }

    public function history($id, $perPage)
    {
        $documentType = DocumentType::find($id);

        if (!$documentType) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Tipo de documento no encontrado",
            ];
        }

        $data = $documentType->audits()->paginate($perPage);

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
            ],
        ];
    }
}
