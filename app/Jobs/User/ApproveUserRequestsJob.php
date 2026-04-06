<?php

namespace App\Jobs\User;

use App\Services\User\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job encargado de aprobar peticiones de usuarios en segundo plano.
 *
 * Este proceso se utiliza para evitar bloquear la respuesta HTTP cuando se
 * aprueban múltiples usuarios al mismo tiempo.
 *
 * Responsabilidades:
 * - Recibir los IDs de usuarios a aprobar
 * - Conservar el usuario que ejecutó la acción para auditoría
 * - Delegar la lógica de negocio al UserService
 */
class ApproveUserRequestsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * IDs de usuarios que serán aprobados.
     *
     * @var array<int>
     */
    public array $userIds;

    /**
     * ID del usuario que ejecutó la acción.
     *
     * Se guarda para que el proceso en cola pueda registrar correctamente
     * la auditoría, ya que auth() no es confiable dentro del worker.
     *
     * @var int|null
     */
    public ?int $performedByUserId;

    /**
     * Crea una nueva instancia del job.
     *
     * @param array<int> $userIds IDs de usuarios a aprobar
     * @param int|null $performedByUserId ID del usuario autenticado que dispara el proceso
     */
    public function __construct(array $userIds, ?int $performedByUserId = null)
    {
        $this->userIds = $userIds;
        $this->performedByUserId = $performedByUserId;
    }

    /**
     * Ejecuta el job.
     *
     * Laravel resuelve automáticamente el UserService desde el contenedor
     * de servicios cuando el worker procesa este job.
     *
     * @param UserService $service Servicio de usuarios
     * @return void
     */
    public function handle(UserService $service): void
    {
        $service->processApproveRequests($this->userIds, $this->performedByUserId);
    }
}