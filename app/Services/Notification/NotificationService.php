<?php

namespace App\Services\Notification;

use App\Models\Notification\Notification;
use \App\Models\User\User;

class NotificationService
{
    public function __construct()
    {
        //
    }

    public static function getAll()
    {
        $notifications = Notification::with('user', 'audit')
            ->latest('created_at')
            ->get();

        if ($notifications->isEmpty()) {
            return [
                "error"   => false,
                "code"    => 200,
                "message" => "No hay registros de notificaciones",
                "data"    => $notifications,
            ];
        }

        return [
            "error"   => false,
            "code"    => 200,
            "message" => "Registros de notificaciones obtenidos exitosamente",
            "data"    => $notifications,
        ];
    }

    public function countUnreadByUser($user_id)
    {
        $count = Notification::where('user_id', $user_id)
            ->where('is_read', false)
            ->count();

        if ($count === 0) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay notificaciones no leídas",
                "data" => [
                    "unread_notifications" => 0
                ]
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Cantidad de notificaciones no leídas obtenida exitosamente",
            "data" => [
                "unread_notifications" => $count
            ]
        ];
    }

    public function getUnreadByUser($user_id)
    {
        $notifications = Notification::where('user_id', $user_id)
            ->where('is_read', false)
            ->with('audit')
            ->latest('created_at')
            ->limit(3)
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'is_read' => $item->is_read,
                    'created_at' => $item->created_at,
                    'audit' => $item->audit ? [
                        'action' => $item->audit->action_execute,
                        'user' => $item->audit->user_name,
                        'role' => $item->audit->rol_name,
                        'status_change' => $item->audit->status_old . ' → ' . $item->audit->status_new,
                        'timestamp' => $item->audit->date_time,
                    ] : null,
                ];
            });

        if ($notifications->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay notificaciones no leídas",
                "data" => []
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Notificaciones no leídas obtenidas exitosamente",
            "data" => $notifications
        ];
    }

    public function getByUserId($user_id)
    {
        $paginator = Notification::where('user_id', $user_id)
            ->with('audit')
            ->latest('created_at')
            ->paginate(10);

        $items = $paginator->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'is_read' => $item->is_read,
                'created_at' => $item->created_at,
                'audit' => $item->audit ? [
                    'id' => $item->audit->id,
                    'action' => $item->audit->action_execute,
                    'user' => $item->audit->user_name,
                    'role' => $item->audit->rol_name,
                    'status_change' => $item->audit->status_old . ' → ' . $item->audit->status_new,
                    'timestamp' => $item->audit->date_time,
                ] : null,
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $items->isEmpty()
                ? "No hay notificaciones para este usuario"
                : "Notificaciones obtenidas exitosamente",
            "data"    => $items,
            "paginate" => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    public function getById($id)
    {
        $notification = Notification::with('audit')->find($id);

        if (!$notification) {
            return [
                "error"   => true,
                "code"    => 404,
                "message" => "Notificación no encontrada",
            ];
        }

        $data = [
            'id' => $notification->id,
            'user_id' => $notification->user_id,
            'is_read' => $notification->is_read,
            'created_at' => $notification->created_at,
            'audit' => $notification->audit ? [
                'id' => $notification->audit->id,
                'action' => $notification->audit->action_execute,
                'user' => $notification->audit->user_name,
                'role' => $notification->audit->rol_name,
                'status_change' => $notification->audit->status_old . ' → ' . $notification->audit->status_new,
                'timestamp' => $notification->audit->date_time,
            ] : null,
        ];

        return [
            "error"   => false,
            "code"    => 200,
            "message" => "Notificación obtenida exitosamente",
            "data"    => $data,
        ];
    }

    public function create(array $data)
    {
        $notification = Notification::create($data);
        $notification->load('audit');

        $responseData = [
            'id' => $notification->id,
            'user_id' => $notification->user_id,
            'is_read' => $notification->is_read,
            'created_at' => $notification->created_at,
            'audit' => $notification->audit ? [
                'id' => $notification->audit->id,
                'action' => $notification->audit->action_execute,
                'user' => $notification->audit->user_name,
                'role' => $notification->audit->rol_name,
                'status_change' => $notification->audit->status_old . ' → ' . $notification->audit->status_new,
                'timestamp' => $notification->audit->date_time,
            ] : null,
        ];

        return [
            "error"   => false,
            "code"    => 201,
            "message" => "Notificación creada exitosamente",
            "data"    => $responseData,
        ];
    }

    // public function update(array $data, $id)
    // {
    //     $notification = Notification::find($id);

    //     if (!$notification) {
    //         return [
    //             "error"   => true,
    //             "code"    => 404,
    //             "message" => "Notificación no encontrada",
    //         ];
    //     }

    //     $notification->update($data);
    //     $notification->load('audit');

    //     $responseData = [
    //         'id' => $notification->id,
    //         'user_id' => $notification->user_id,
    //         'is_read' => $notification->is_read,
    //         'created_at' => $notification->created_at,
    //         'audit' => $notification->audit ? [
    //             'id' => $notification->audit->id,
    //             'action' => $notification->audit->action_execute,
    //             'user' => $notification->audit->user_name,
    //             'role' => $notification->audit->rol_name,
    //             'status_change' => $notification->audit->status_old . ' → ' . $notification->audit->status_new,
    //             'timestamp' => $notification->audit->date_time,
    //         ] : null,
    //     ];

    //     return [
    //         "error"   => false,
    //         "code"    => 200,
    //         "message" => "Notificación actualizada exitosamente",
    //         "data"    => $responseData,
    //     ];
    // }

    public function partialUpdate(array $data, $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return [
                "error"   => true,
                "code"    => 404,
                "message" => "Notificación no encontrada",
            ];
        }

        $notification->update($data);
        $notification->load('audit');

        $responseData = [
            'id' => $notification->id,
            'user_id' => $notification->user_id,
            'is_read' => $notification->is_read,
            'created_at' => $notification->created_at,
            'audit' => $notification->audit ? [
                'id' => $notification->audit->id,
                'action' => $notification->audit->action_execute,
                'user' => $notification->audit->user_name,
                'role' => $notification->audit->rol_name,
                'status_change' => $notification->audit->status_old . ' → ' . $notification->audit->status_new,
                'timestamp' => $notification->audit->date_time,
            ] : null,
        ];

        return [
            "error"   => false,
            "code"    => 200,
            "message" => "Notificación actualizada parcialmente exitosamente",
            "data"    => $responseData,
        ];
    }

    public function delete($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return [
                "error"   => true,
                "code"    => 404,
                "message" => "Notificación no encontrada",
            ];
        }

        $notification->delete();

        return [
            "error"   => false,
            "code"    => 200,
            "message" => "Notificación eliminada exitosamente",
        ];
    }

    public static function notify(int $userId, int $auditId): void
    {
        Notification::create([
            'user_id'  => $userId,
            'audit_id' => $auditId,
            'is_read'  => false,
        ]);
    }

    public static function notifySupervisoresBySectional(int $sectionalId, int $auditId): void
    {
        $supervisores = User::whereHas('roles', fn($rol) => $rol->where('name', 'Supervisor'))
        ->whereHas('profile.organization', fn($po) => $po->where('sectional_id', $sectionalId))
        ->pluck('id');

        foreach ($supervisores as $userId) {
            self::notify($userId, $auditId);
        }
    }
}
