<?php

namespace App\Jobs\User;

use App\Services\User\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job encargado de rechazar y eliminar peticiones de usuarios en segundo plano.
 *
 * Se usa para procesar eliminaciones masivas sin bloquear la petición HTTP.
 *
 * Responsabilidades:
 * - Recibir los IDs de usuarios a rechazar
 * - Conservar el usuario que ejecutó la acción para auditoría
 * - Delegar la lógica al UserService
 */
class RejectAndDeleteRequestsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * IDs de usuarios a eliminar.
     *
     * @var array<int>
     */
    public array $userIds;

    /**
     * ID del usuario que ejecutó la acción.
     *
     * @var int|null
     */
    public ?int $performedByUserId;

    /**
     * Crea una nueva instancia del job.
     *
     * @param array<int> $userIds IDs de usuarios a rechazar y eliminar
     * @param int|null $performedByUserId ID del usuario autenticado que ejecuta la acción
     */
    public function __construct(array $userIds, ?int $performedByUserId = null)
    {
        $this->userIds = $userIds;
        $this->performedByUserId = $performedByUserId;
    }

    /**
     * Ejecuta el proceso de rechazo y eliminación.
     *
     * @param UserService $service Servicio de usuarios
     * @return void
     */
    public function handle(UserService $service): void
    {
        $service->processRejectAndDeleteRequests($this->userIds, $this->performedByUserId);
    }
}