<?php

namespace App\Services\FamilyPlan;

use App\Models\FamilyPlan\FamilyPlan;
use App\Models\History\History;
use Illuminate\support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Servicio para la gestión de Planes Familiares.
 * Incluye lógica de auditoría para el seguimiento de registros.
 */
class FamilyPlanService
{
    /**
     * Obtiene todos los planes familiares registrados.
     * @return array
     */
    public static function getAll()
    {
        $familyPlan = FamilyPlan::all();

        if ($familyPlan->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay planes familiares registrados",
                "data" => $familyPlan,
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "planes familiares obtenidos exitosamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Obtiene un plan familiar específico por su ID.
     */
    public function getById($id)
    {
        $familyPlan = FamilyPlan::with('city.department')->find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        // Convertimos a array para poder modificarlo
        $data = $familyPlan->toArray();

        // Creamos department_id manualmente
        $data['department_id'] = $familyPlan->city->department->id ?? null;

        return [
            "error" => false,
            "code" => 200,
            "message" => "Plan familiar obtenido exitosamente",
            "data" => $data,
        ];
    }

    /**
     * Crea un plan familiar y registra la acción en el historial.
     * @param array $data Datos del plan.
     * @return array
     */
    public function create(array $data)
    {
        $familyPlan = FamilyPlan::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Plan familiar creado exitosamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Actualización completa de un plan familiar.
     */
    public function update(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        $familyPlan->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Plan familiar actualizado exitosamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Actualización parcial de campos del plan.
     */
    public function partialUpdate(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        $familyPlan->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Plan familiar actualizado parcialmente exitosamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Actualiza el estado actual del plan familiar.
     */
    public function changeStatus(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }
        $oldStatus = $familyPlan->statusPlan->name;
        $familyPlan->update($data);
        // 🔹 Estado nuevo
        $familyPlan->refresh()->load('statusPlan');
        $newStatus = $familyPlan->statusPlan->name;

        // 🔹 Validación
        if ($newStatus != 1 && $newStatus != 2 && $newStatus != 3) { // aquí pones el estado que quieras validar
            $familyPlan->audits()->create([
                'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
                'rol_name'       => auth()->user()->getRoleNames()->first(),
                'date_time'      => now(),
                'action_execute' => 'Cambio de estado del plan familiar',
                'status_old'     => $oldStatus,
                'status_new'     => $newStatus,
            ]);
        }
        return [
            "error" => false,
            "code" => 200,
            "message" => "Cambio de estado del plan familiar actualizado correctamente",
            "data" => $familyPlan,
        ];
    }
    /**
     * Actualiza los datos de identificación específicos del plan.
     */
    public function identify(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        $familyPlan->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Identificacion del plan familiar actualizado correctamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Elimina un plan familiar del sistema.
     */
    public function delete($id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }
        History::where('family_plan_id', $id)->delete();

        $familyPlan->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Plan familiar eliminado exitosamente",
        ];
    }

    public function checkAccess(string $planId): array
    {
        $user = auth()->user();
        $access = false;

        if (!$user) {
            return [
                "error" => false,
                "code" => 401,
                "message" => "Usuario no autenticado",
                "data" => [
                    "access_check" => $access
                ]
            ];
        }

        $plan = FamilyPlan::find($planId);

        if (!$plan) {
            return [
                "error" => false,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
                "data" => [
                    "access_check" => $access
                ]
            ];
        }

        $roleId = $user->roles->first()?->id ?? null;

        // 🔴 Si NO es 2 ni 3 → sin acceso
        if ($roleId != 2 && $roleId != 3) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "Verificación de acceso realizada",
                "data" => [
                    "access_check" => $access
                ]
            ];
        }

        // 🟢 Rol 3 → Voluntario
        if ($roleId == 3) {

            // No puede acceder si el estado es 4 o 6
            if (!in_array($plan->status_plan_id, [1, 2, 4, 6, 7])) {
                $access = $plan->user_id == $user->id;
            }
        }

        // 🔵 Rol 2 → Supervisor
        if ($roleId == 2) {
            if (
                $user->profile &&
                $user->profile->organization &&
                $user->profile->organization->sectional_id === $plan->sectional_id &&
                in_array($plan->status_plan_id, [4, 7])
            ) {
                $access = true;
            }
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Verificación de acceso realizada",
            "data" => [
                "access_check" => $access
            ]
        ];
    }

    public function getFamilyPlanByUser()
    {
        $user = auth()->user();

        if (!$user) {
            return [
                "error" => true,
                "code" => 401,
                "message" => "Usuario no autenticado",
            ];
        }

        $roleId = $user->roles->first()?->id ?? null;

        if ($roleId != 2 && $roleId != 3) {
            return [
                "error" => true,
                "code" => 403,
                "message" => "Este rol no tiene acceso a los planes familiares",
            ];
        }

        // 🟢 Rol 3 → Voluntario
        if ($roleId == 3) {

            $plans = FamilyPlan::where('user_id', $user->id)
                ->whereNotIn('status_plan_id', [4])
                ->with('statusPlan', 'city', 'city.department')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return [
                "error" => false,
                "code" => 200,
                "message" => $plans->isEmpty()
                    ? "No hay planes familiares disponibles"
                    : "Planes familiares obtenidos exitosamente",
                "data" => $plans->getCollection()->transform(function ($plan) {
                    return [
                        "id" => $plan->id,
                        "last_names" => $plan->last_names,
                        "city" => $plan->city->name,
                        "department" => $plan->city->department->name,
                        "status" => $plan->statusPlan->name,
                        "status_id" => $plan->statusPlan->id,
                        "date_create" => $plan->created_at->format('d/m/Y'),
                    ];
                }),
                "paginate" => [
                    'current_page' => $plans->currentPage(),
                    'per_page' => $plans->perPage(),
                    'total' => $plans->total(),
                    'last_page' => $plans->lastPage(),
                    'from' => $plans->firstItem(),
                    'to' => $plans->lastItem(),
                ],
            ];
        }

        // 🔵 Rol 2 → Supervisor
        if ($roleId == 2) {

            if (!$user->profile || !$user->profile->organization) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "Perfil u organización no encontrada",
                ];
            }

            $sectionalId = $user->profile->organization->sectional_id;

            $plans = FamilyPlan::where('sectional_id', $sectionalId)
                ->whereIn('status_plan_id', [2, 4, 5, 6, 7])
                ->with('statusPlan', 'city', 'city.department')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return [
                "error" => false,
                "code" => 200,
                "message" => $plans->isEmpty()
                    ? "No hay planes familiares disponibles"
                    : "Planes familiares obtenidos exitosamente",
                "data" => $plans->getCollection()->transform(function ($plan) {
                    return [
                        "id" => $plan->id,
                        "last_names" => $plan->last_names,
                        "city" => $plan->city->name,
                        "department" => $plan->city->department->name,
                        "status" => $plan->statusPlan->name,
                        "status_id" => $plan->statusPlan->id,
                        "date_create" => $plan->created_at->format('d/m/Y'),
                    ];
                }),
                "paginate" => [
                    'current_page' => $plans->currentPage(),
                    'per_page' => $plans->perPage(),
                    'total' => $plans->total(),
                    'last_page' => $plans->lastPage(),
                    'from' => $plans->firstItem(),
                    'to' => $plans->lastItem(),
                ],
            ];
        }
    }

    /**
     * Obtiene los planes familiares filtrados por estado.
     *
     * Retorna una lista paginada de planes familiares que coinciden con el ID de estado
     * proporcionado. Cada plan incluye información básica: apellidos, ubicación geográfica,
     * estado actual y fecha de creación.
     *
     * @param int $statusId ID del estado por el cual filtrar los planes
     * @return array Respuesta estructurada con datos paginados y metainformación
     */
    public function getByStatus(int $statusId)
    {
        // Obtiene planes familiares filtrados por estado con paginación de 10 registros
        $data = FamilyPlan::where('status_plan_id', $statusId)->paginate(10);

        // Transforma cada plan al formato de respuesta esperado
        $plans = $data->map(function ($plan) {
            return [
                "id" => $plan->id,
                "last_names" => $plan->last_names,
                "city" => $plan->city->name,
                "department" => $plan->city->department->name,
                "status" => $plan->statusPlan->name,
                "status_id" => $plan->statusPlan->id,
                "date_create" => $plan->created_at->format('d/m/Y'),
            ];
        });

        // Retorna respuesta estructurada con datos y metadatos de paginación
        return [
            "error" => false,
            "code" => 200,
            "message" => $plans->isEmpty()
                ? "No hay planes familiares disponibles"
                : "Planes familiares obtenidos exitosamente",
            "data" => $plans,
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

    public function generatePdf($id)
    {
        $plan = FamilyPlan::findOrFail($id);

        // 🔹 Crear HTML directo
        $html = '
            <h1>Plan Familiar #' . $plan->id . '</h1>
            <p><strong>Nombre:</strong> ' . $plan->last_names . '</p>
            <p><strong>Fecha:</strong> ' . $plan->created_at . '</p>
            <ul>
        ';

        $html .= '</ul>';

        $pdf = Pdf::loadHTML($html);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="plan_' . $plan->id . '.pdf"');
    }

    /**
     * Verifica si un plan familiar tiene al menos un integrante registrado.
     *
     * Se usa para validar antes de realizar operaciones que requieren
     * que el plan tenga integrantes (ej: activar, enviar, procesar).
     *
     * @param int $id ID del plan familiar a verificar
     * @return array{error: bool, code: int, message: string, data: array}
     */
    public function hasMembers(int $id): array
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                'error'   => true,
                'code'    => 404,
                'message' => 'Plan familiar no encontrado',
            ];
        }

        return [
            'error'   => false,
            'code'    => 200,
            'message' => 'Verificación de integrantes realizada',
            'data'    => [
                'has_members' => $familyPlan->familyMembers()->exists(),
            ],
        ];
    }
}
