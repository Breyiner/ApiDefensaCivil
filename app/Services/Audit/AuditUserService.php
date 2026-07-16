<?php

namespace App\Services\Audit;

use App\Models\Audit\AuditUser;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class AuditUserService
{
    /**
     * Registra un evento de auditoría sobre un usuario.
     * Recibe directamente los valores viejos y nuevos (sin calcular diffs).
     */
    public function register(User $user, string $actionExecute, array $data): array
    {
        $audit = $user->auditUsers()->create(array_merge($data, [
            'user_name'      => auth()->user()->profile->names . ' ' . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => $actionExecute,
        ]));

        return [
            "error" => false,
            "code" => 201,
            "message" => "Auditoría de usuario registrada exitosamente",
            "data" => $audit,
        ];
    }

    private function formatAuditUser($audit)
    {
        // Pares old/new a comparar — solo se incluyen los que realmente cambiaron
        $fields = [
            'userName'        => 'Nombre de usuario',
            'lastName'        => 'Apellido',
            'userRol'         => 'Rol',
            'documentType'    => 'Tipo de documento',
            'numberDocument'  => 'Número de documento',
            'birthDate'       => 'Fecha de nacimiento',
            'gender'          => 'Género',
            'sectional'       => 'Seccional',
            'organization'    => 'Organización',
        ];

        $changes = [];

        foreach ($fields as $field => $label) {
            $old = $audit->{$field . '_old'};
            $new = $audit->{$field . '_new'};

            if ($old != $new) {
                $changes[] = [
                    'field' => $label,
                    'old'   => $old,
                    'new'   => $new,
                ];
            }
        }

        return [
            'id'             => $audit->id,
            'date_time'      => $audit->date_time,
            'user_name'      => $audit->user_name,
            'rol'            => $audit->rol_name,
            'action_execute' => $audit->action_execute,
            'status_old'     => $audit->status_old,
            'status_new'     => $audit->status_new,
            'name_model'     => $audit->historiable?->profile?->names . ' ' . $audit->historiable?->profile?->last_names,
            'changes'        => $changes,
        ];
    }
    
    public function getAll($perPage = 10)
    {
        $data = AuditUser::with('historiable')
            ->orderBy('date_time', 'desc')
            ->paginate($perPage);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Historial de auditoría de usuarios obtenido exitosamente",
            "data" => $data->getCollection()->map(fn($audit) => $this->formatAuditUser($audit)),
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



    public function getByUser($userId, $perPage = 10)
    {
        $user = User::find($userId);

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        $data = $user->auditUsers()
            ->orderBy('date_time', 'desc')
            ->paginate($perPage);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Historial de auditoría de usuario obtenido exitosamente",
            "data" => $data->items(),
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

    public function delete($id)
    {
        $audit = AuditUser::find($id);

        if (!$audit) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El registro de auditoría no fue encontrado.",
            ];
        }

        $audit->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Se eliminó exitosamente el registro de auditoría.",
        ];
    }

    public function bulkDelete(array $auditIds): array
    {
        DB::beginTransaction();

        try {
            $audits = AuditUser::whereIn('id', $auditIds)->get();

            if ($audits->isEmpty()) {
                DB::rollBack();

                return [
                    'error' => true,
                    'code' => 404,
                    'message' => 'No se encontraron registros de auditoría para eliminar.',
                ];
            }

            foreach ($audits as $audit) {
                $audit->delete();
            }

            DB::commit();

            return [
                'error' => false,
                'code' => 200,
                'message' => count($auditIds) === 1
                    ? 'Se eliminó exitosamente el registro de auditoría.'
                    : 'Se eliminaron exitosamente los registros de auditoría seleccionados.',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'error' => true,
                'code' => 500,
                'message' => 'Error al intentar eliminar los registros de auditoría.',
            ];
        }
    }
}