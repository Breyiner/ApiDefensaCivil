<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

/**
 * Notificación personalizada para verificación de email en cola.
 *
 * Implementa ShouldQueue: se ejecuta asíncronamente en cola 'default'
 *
 * Ventajas de cola:
 * - Registro instantáneo (email en background)
 * - Alta disponibilidad (no falla si SMTP offline)
 * - Escalable (múltiples workers procesan emails)
 * - Retry automático (3 intentos si SMTP falla)
 *
 * Configuración cola (.env):
 * QUEUE_CONNECTION=database/redis
 * MAIL_MAILER=smtp
 *
 * Flujo con cola:
 * 1. Usuario registra → $user->notify() → JOB en cola
 * 2. Worker: php artisan queue:work procesa email
 * 3. Usuario hace click → backend verifica directamente
 *
 * Registro en User model:
 * public function sendEmailVerificationNotification(): void {
 *     $this->notify(new CustomVerifyEmail());
 * }
 */
class CustomVerifyEmail extends VerifyEmailBase implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = [30, 60, 120];

    /**
     * Construye mensaje de email para verificación.
     *
     * Usa el template por defecto de Laravel (sin vista Blade personalizada).
     * La URL apunta directamente al backend (verification.verify route).
     *
     * @param mixed $notifiable Usuario receptor (User model)
     * @return MailMessage Email listo para enviar
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu correo - Sistema Defensa Civil Colombiana')
            ->view('emails.verify-email', [
                // 'url' => config('app.frontend_url') . "/verificar-email?verify_url=" . urlencode($verificationUrl),
                'url' => $verificationUrl,
                'user' => $notifiable,
            ]);
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Manejo de fallos en cola.
     *
     * Se ejecuta si supera $tries (3 intentos).
     */
    public function failed(\Exception $exception)
    {
        Log::error("Email verificación falló: " . $exception->getMessage());
    }
}