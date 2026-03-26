<?php

namespace App\Services\VulnerableQuestion;

use App\Models\VulnerableQuestion\VulnerableQuestion;
use App\Models\Audit\Audit;

class VulnerableQuestionService
{
    public static function getAll()
    {
        $questions = VulnerableQuestion::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Preguntas vulnerables obtenidas exitosamente",
            "data" => $questions,
        ];
    }

    public function getById($id)
    {
        $question = VulnerableQuestion::find($id);

        if (!$question) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Pregunta vulnerable no encontrada",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Pregunta vulnerable obtenida exitosamente",
            "data" => $question,
        ];
    }

    public function create(array $data)
    {
        $question = VulnerableQuestion::create($data);

        $question->audits()->create([
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
            "message" => "Pregunta vulnerable creada exitosamente",
            "data" => $question,
        ];
    }

    public function update(array $data, $id)
    {
        $question = VulnerableQuestion::find($id);

        if (!$question) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Pregunta vulnerable no encontrada",
            ];
        }

        $oldStatus = $question->is_active ? "Activo" : "Inactivo";

        $question->update($data);

        $question->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado',
            'status_old'     => $oldStatus,
            'status_new'     => $question->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Pregunta vulnerable actualizada exitosamente",
            "data" => $question,
        ];
    }

    public function partialUpdate(array $data, $id)
    {
        $question = VulnerableQuestion::find($id);

        if (!$question) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Pregunta vulnerable no encontrada",
            ];
        }

        $oldStatus = $question->is_active ? "Activo" : "Inactivo";

        $question->update($data);

        $question->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Actualizado parcialmente',
            'status_old'     => $oldStatus,
            'status_new'     => $question->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Pregunta vulnerable actualizada parcialmente exitosamente",
            "data" => $question,
        ];
    }

    public function changeStatus(array $data, $id)
    {
        $question = VulnerableQuestion::find($id);

        if (!$question) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Pregunta vulnerable no encontrada",
            ];
        }

        if ($data['is_active'] == 0) {
            $activeCount = VulnerableQuestion::where('is_active', 1)->count();

            // Si solo queda 1 activa y es esta, no se puede desactivar
            if ($activeCount <= 1 && $question->is_active == 1) {
                return [
                    "error" => true,
                    "code" => 422,
                    "message" => "No se puede desactivar este pregunta de vulnerabilidad, minimo un registro activo",
                ];
            }
        }    

        $oldStatus = $question->is_active ? "Activo" : "Inactivo";

        $question->update([
            'is_active' => $data['is_active']
        ]);

        $question->audits()->create([
            'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
            'rol_name'       => auth()->user()->getRoleNames()->first(),
            'date_time'      => now(),
            'action_execute' => 'Cambio de estado',
            'status_old'     => $oldStatus,
            'status_new'     => $question->is_active ? "Activo" : "Inactivo",
        ]);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Estado de la pregunta vulnerable actualizado correctamente",
            "data" => $question,
        ];
    }

    public function delete($id)
    {
        $question = VulnerableQuestion::find($id);

        if (!$question) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Pregunta vulnerable no encontrada",
            ];
        }

        $oldStatus = $question->is_active ? "Activo" : "Inactivo";

        $question->delete();

        $question->audits()->create([
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
            "message" => "Pregunta vulnerable eliminada exitosamente",
        ];
    }

    public function history($id, $perPage = 10)
    {
        $question = VulnerableQuestion::find($id);

        if (!$question) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Pregunta vulnerable no encontrada",
            ];
        }

        $data = $question->audits()
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

    public function paginate()
    {
        $paginator = VulnerableQuestion::where('is_active', true)->paginate(3);

        $items = $paginator->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'description' => $item->description,
                'question_caution' => $item->question_caution,
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $items->isEmpty()
                ? "No hay preguntas vulnerables activas disponibles"
                : "Preguntas vulnerables obtenidas exitosamente",
            "data"    => $items,
            'paginate' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ];
    }
}
