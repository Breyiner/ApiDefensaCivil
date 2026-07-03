<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Audit\Audit;
use App\Models\Audit\AuditUser;

/**
 * Importación de modelos para relaciones y Traits de paquetes externos.
 */
use App\Models\StateUser\StateUser;
use App\Models\Profile\Profile;
use App\Notifications\CustomVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Clase User
 * * Esta es la clase auténtica para el manejo de sesiones y seguridad.
 * Hereda de Authenticatable para integrarse con el sistema de Guards de Laravel.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** * HasApiTokens: Permite emitir tokens para APIs (Sanctum).
     * HasRoles: Habilita el manejo de Roles y Permisos (Spatie).
     * Notifiable: Permite enviar correos o alertas al usuario.
     */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * Atributos asignables de forma masiva.
     * Mantenemos solo lo esencial para el acceso y el estado de la cuenta.
     */
    protected $fillable = [
        'id',
        'email',         // Correo electrónico (identificador de acceso)
        'password',      // Contraseña (siempre se almacena hasheada)
        'state_user_id'  // Referencia al estado de la cuenta (Activo/Inactivo)
    ];

    /**
     * Configuración de conversión de tipos (Casting).
     * Asegura que la contraseña siempre sea tratada como un hash seguro.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    
    /** --- RELACIONES --- **/

    /**
     * Relación con el Estado del Usuario (BelongsTo).
     * Determina si el usuario tiene permiso para entrar al sistema.
     */
    public function stateUser()
    {
        return $this->belongsTo(StateUser::class, 'state_user_id');
    }

    /**
     * Relación Uno a Uno (HasOne) con Perfil.
     * Conecta la cuenta de acceso con los datos personales del funcionario.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    /**
     * Relación de Uno a Muchos (HasMany) con Historial.
     * Permite auditar todas las acciones realizadas por este usuario en el sistema.
     */

    public function audits()
    {
        return $this->morphMany(Audit::class, 'historiable');
    }

    public function auditUsers()
    {
        return $this->morphMany(AuditUser::class, 'historiable');
    }

    /**
     * **SOBREESCRIBE** notificación verificación email personalizada.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    /**
     * --- SCOPES PARA FILTRADO POR ROL ---
     */

    /**
     * Scope para Administradores: pueden ver todos los usuarios excepto a sí mismos
     */
    public function scopeForAdministrador(Builder $query): Builder
    {
        return $query->where('id', '!=', Auth::id());
    }

    /**
     * Scope para Supervisores: solo ven usuarios de su seccional
     */
    public function scopeForSupervisor(Builder $query): Builder
    {
        $user = Auth::user();

        // Si el supervisor tiene seccional asignado, filtrar por esa seccional
        if ($user->profile && $user->profile->organization && $user->profile->organization->sectional_id) {
            return $query->whereHas('profile.organization', function ($q) use ($user) {
                $q->where('sectional_id', $user->profile->organization->sectional_id);
            });
        }

        // Si no tiene seccional, no ver ningún usuario
        return $query->whereRaw('1 = 0');
    }

    /**
     * Scope principal: aplica el filtro automático según el rol del usuario autenticado
     */
    public function scopeForAuthUser(Builder $query): Builder
    {
        $user = Auth::user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        return match (true) {
            $user->hasRole('Administrador') => $query->forAdministrador(),
            $user->hasRole('Supervisor') => $query->forSupervisor(),
            default => $query->whereRaw('1 = 0')
        };
    }
}