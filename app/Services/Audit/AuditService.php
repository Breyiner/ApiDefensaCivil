<?php

namespace App\Services\Audit;

use App\Models\User\User;
use App\Models\Audit\Audit;
use App\Models\FamilyPlan\FamilyPlan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AuditService
{
    public function DashBoardAdmin()
    {
        // 🔹 1. RESUMEN USUARIOS
        $summaryData = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Administrador');
        })
            ->selectRaw("
                SUM(CASE WHEN state_user_id = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN state_user_id = 2 THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN state_user_id = 3 THEN 1 ELSE 0 END) as request
            ")
            ->first();

        $summary = [
            "active"   => (int) ($summaryData->active ?? 0),
            "inactive" => (int) ($summaryData->inactive ?? 0),
            "request"  => (int) ($summaryData->request ?? 0),
        ];

        // 🔹 2. ROLES
        $rolesData = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->selectRaw("
                    SUM(CASE WHEN roles.name = 'Supervisor' THEN 1 ELSE 0 END) as supervisor,
                    SUM(CASE WHEN roles.name = 'Voluntario' THEN 1 ELSE 0 END) as volunteer
                ")
            ->first();

        $rols = [
            "supervisor" => (int) ($rolesData->supervisor ?? 0),
            "volunteer"  => (int) ($rolesData->volunteer ?? 0),
        ];

        // 🔹 3. CAMBIOS ÚLTIMOS 6 MESES (AUDIT)
        $now = Carbon::now();
        $startDate = $now->copy()->subMonths(5)->startOfMonth();
        $endDate = $now->copy()->endOfMonth();

        $rawAudit = Audit::where('historiable_type', '!=', User::class)
            ->where('historiable_type', '!=', FamilyPlan::class)
            ->whereBetween('date_time', [$startDate, $endDate])
            ->selectRaw("
                    DATE_FORMAT(date_time, '%Y-%m') as 'year_month',
                    COUNT(*) as total
                ")
            ->groupBy(DB::raw("DATE_FORMAT(date_time, '%Y-%m')"))
            ->orderBy('year_month')
            ->get()
            ->keyBy('year_month');

        $monthly = [];
        Carbon::setLocale('es');
        for ($i = 5; $i >= 0; $i--) {

            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');

            $monthly[] = [
                "month" => $date->translatedFormat('F'),
                "total" => (int) ($rawAudit[$key]->total ?? 0)
            ];
        }

        $historyGeneral = Audit::with('historiable')
            ->whereNotIn('historiable_type', [
                FamilyPlan::class,
                Member::class,
                User::class
            ])
            ->latest('date_time')
            ->take(10)
            ->get()
            ->map(fn($audit) => $this->formatAudit($audit));

        $historyMembers = Audit::with('historiable')
            ->where('historiable_type', User::class)
            ->latest('date_time')
            ->take(10)
            ->get()
            ->map(fn($audit) => $this->formatAudit($audit));

        // 🔹 RESPUESTA FINAL
        return [
            "error" => false,
            "code" => 200,
            "message" => "Dashboard obtenido correctamente",
            "data" => [
                "summary" => $summary,
                "rols" => $rols,
                "monthly_changes" => $monthly,
                "history_general" => $historyGeneral,
                "history_members" => $historyMembers
            ]
        ];
    }

    private function formatAudit($audit)
    {
        return [
            'date_time'      => $audit->date_time,
            'user_name'      => $audit->user_name,
            'rol'            => $audit->rol_name,
            'action_execute' => $audit->action_execute,
            'status_old'     => $audit->status_old,
            'status_new'     => $audit->status_new,
            // 'historiable_id' => $audit->historiable_id,
            // 'tabla'          => class_basename($audit->historiable_type),
            'name_model' =>
            $audit->historiable?->name
                ?? $audit->historiable?->description
                ?? $audit->historiable?->profile?->names . ' ' . $audit->historiable?->profile?->last_names
        ];
    }

    public function dashBoardSupervisor()
    {
        $user = auth()->user();

        if (!$user) {
            return [
                "error" => true,
                "code" => 401,
                "message" => "No autenticado"
            ];
        }

        // 🔹 1. Obtener seccional desde profile
        $sectionalId = $user->profile->organization->sectional->id ?? null;

        if (!$sectionalId) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Supervisor sin seccional asignada"
            ];
        }

        // 🔹 2. Contadores de planes
        $pending = FamilyPlan::where('sectional_id', $sectionalId)
            ->where('status_plan_id', 4)
            ->count();

        $approved = FamilyPlan::where('sectional_id', $sectionalId)
            ->where('status_plan_id', 7)
            ->count();

        $rejected = FamilyPlan::where('sectional_id', $sectionalId)
            ->whereIn('status_plan_id', [5, 6])
            ->count();

        // 🔹 3. Tiempo promedio de validación

        $audits = Audit::where('historiable_type', FamilyPlan::class)
            ->whereIn('status_new', [
                'Enviado',
                'Aprobado',
                'Rechazado Cambios',
                'Rechazado Definitivo',
            ])
            ->orderBy('date_time')
            ->get()
            ->groupBy('historiable_id');

        $totalMinutes = 0;
        $totalValidations = 0;

        foreach ($audits as $familyPlanId => $records) {

            $sent = null;

            foreach ($records as $audit) {

                // Cuando encuentra "Enviado"
                if ($audit->status_new === 'Enviado') {
                    $sent = Carbon::parse($audit->date_time);
                }

                // Si ya hubo enviado y luego viene resultado
                if ($sent && in_array($audit->status_new, [
                    'Aprobado',
                    'Rechazado Cambios',
                    'Rechazado Definitivo'
                ])) {

                    $validated = Carbon::parse($audit->date_time);

                    $minutes = $sent->diffInMinutes($validated);

                    $totalMinutes += $minutes;
                    $totalValidations++;

                    // Reinicia para siguientes ciclos
                    $sent = null;
                }
            }
        }

        $averageValidationTime = $totalValidations > 0
            ? round($totalMinutes / $totalValidations)
            : 0;

        return [
            "error" => false,
            "code" => 200,
            "message" => "Dashboard planes familiares obtenido correctamente",
            "data" => [
                "pending_plans" => $pending,
                "approved_plans" => $approved,
                "rejected_plans" => $rejected,
                "time_validation" => $averageValidationTime,
            ]
        ];
    }

    public function delete($id)
    {
        $Audit = Audit::find($id);

        if (!$Audit) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El registro de la actividad no fue encontrado.",
            ];
        }

        $Audit->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Se elimino exitosamente el registro de la actividad.",
        ];
    }
}
