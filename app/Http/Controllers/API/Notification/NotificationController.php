<?php

namespace App\Http\Controllers\API\Notification;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\StoreNotificationRequest;
use App\Http\Requests\Notification\UpdateNotificationRequest;
use App\Http\Requests\Notification\PartialUpdateNotificationRequest;
use App\Http\Requests\Notification\ChangeStatusNotificationRequest;
use App\Models\Notification\Notification;
use App\Services\Notification\NotificationService;
use App\Policies\AccessNotificationPolicy;

class NotificationController extends Controller
{
    protected $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $response = $this->service->getAll();

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function show(string $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return ResponseFormatter::error(
                'Registro no encontrado',
                404
            );
        }

        $response = $this->service->getById($id);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function getByUser(string $user_id)
    {
        $response = $this->service->getByUserId($user_id);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? [],
            $response['paginate'] ?? null
        );
    }

    public function store(StoreNotificationRequest $request)
    {
        $data = $request->validated();

        $response = $this->service->create($data);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    // public function update(UpdateNotificationRequest $request, string $id)
    // {
    //     $notification = Notification::find($id);

    //     if (!$notification) {
    //         return ResponseFormatter::error(
    //             'Registro no encontrado',
    //             404
    //         );
    //     }

    //     $response = $this->service->update(
    //         $request->validated(),
    //         $id
    //     );

    //     if ($response['error']) {
    //         return ResponseFormatter::error(
    //             $response['message'],
    //             $response['code']
    //         );
    //     }

    //     return ResponseFormatter::success(
    //         $response['message'],
    //         $response['code'],
    //         $response['data'] ?? []
    //     );
    // }

        public function changeStatus(ChangeStatusNotificationRequest $request, string $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return ResponseFormatter::error(
                'Registro no encontrado',
                404
            );
        }

        $response = $this->service->partialUpdate($request->validated(),$id);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function countUnreadByUser(string $user_id)
    {
        $response = $this->service->countUnreadByUser($user_id);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function getUnreadByUser(string $user_id)
    {
        $response = $this->service->getUnreadByUser($user_id);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function partialUpdate(PartialUpdateNotificationRequest $request, string $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return ResponseFormatter::error(
                'Registro no encontrado',
                404
            );
        }

        $response = $this->service->partialUpdate(
            $request->validated(),
            $id
        );

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function destroy(string $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return ResponseFormatter::error(
                'Registro no encontrado',
                404
            );
        }

        $response = $this->service->delete($id);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }
}
