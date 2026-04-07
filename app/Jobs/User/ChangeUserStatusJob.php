<?php

namespace App\Jobs\User;

use App\Services\User\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job encargado de cambiar el estado de múltiples usuarios en segundo plano.
 *
 * Este job permite procesar cambios masivos de estado sin bloquear la petición HTTP.
 *
 * Responsabilidades:
 * - Recibir los IDs de usuarios a actualizar
 * - Recibir el nuevo state_user_id
 * - Conservar el usuario que ejecutó la acción
 * - Delegar la lógica al UserService
 */
class ChangeUserStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * IDs de usuarios a actualizar.
     *
     * @var array<int>
     */
    public array $userIds;

    /**
     * ID del nuevo estado a asignar.
     *
     * @var int
     */
    public int $stateUserId;

    /**
     * ID del usuario que ejecutó la acción.
     *
     * @var int|null
     */
    public ?int $performedByUserId;

    /**
     * Crea una nueva instancia del job.
     *
     * @param array<int> $userIds IDs de usuarios a actualizar
     * @param int $stateUserId ID del nuevo estado
     * @param int|null $performedByUserId ID del usuario autenticado que realiza la acción
     */
    public function __construct(array $userIds, int $stateUserId, ?int $performedByUserId = null)
    {
        $this->userIds = $userIds;
        $this->stateUserId = $stateUserId;
        $this->performedByUserId = $performedByUserId;
    }

    /**
     * Ejecuta el cambio de estado en segundo plano.
     *
     * @param UserService $service Servicio de usuarios
     * @return void
     *
     * @throws \Throwable
     */
    public function handle(UserService $service): void
    {
        $service->processChangeUserStatus(
            $this->userIds,
            $this->stateUserId,
            $this->performedByUserId
        );
    }
}