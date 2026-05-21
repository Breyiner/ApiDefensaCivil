<?php

namespace App\Services\Auth;

use App\Enums\TokenAbility;
use App\Models\Profile\Profile;
use App\Models\User\User;
use App\Services\Profile\ProfileService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

use App\Services\Notification\NotificationService; //Notificación para petición

class AuthService
{

    public function register(array $data)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'state_user_id' => 3
            ]);

            if (!$user) {
                DB::rollBack();
                return [
                    "error" => true,
                    "code" => 400,
                    "message" => "Error al crear el usuario",
                ];
            }

            $user->assignRole('Voluntario');

            $profile = Profile::create([
                'user_id'  => $user->id,
                'names' => $data['names'],
                'last_names' => $data['last_names'],
                'birth_date' => $data['birth_date'],
                'document_type_id' => $data['document_type_id'],
                'document_number' => $data['document_number'],
                'gender_id' => $data['gender_id'],
                'organization_id' => $data['organization_id'],
                'phone' => $data['phone']
            ]);

            if (!$profile) {
                DB::rollBack();
                return [
                    "error" => true,
                    "code" => 400,
                    "message" => "Error al crear el perfil",
                ];
            }
            
            $audit = $user->audits()->create([
                'user_name'      => $data['names'] . " " . $data['last_names'],
                'rol_name'       => 'Solicitante',
                'date_time'      => now(),
                'action_execute' => 'Enviar Peticion',
                'status_old'     => null,
                'status_new'     => 'Peticion',
            ]);
                
            DB::commit();

            $user->fresh()->sendEmailVerificationNotification();

            $sectionalId = $profile->organization?->sectional_id;

            if ($sectionalId) {
                NotificationService::notifyAdminsBySectional($sectionalId, $audit->id);
                NotificationService::notifySupervisoresBySectional($sectionalId, $audit->id);
            }

            return [
                "error" => false,
                "code" => 201,
                "message" => "Usuario registrado con exito,verifique su correo electronico para continuar",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error en el registro: " . $e->getMessage(),
            ];
        }
    }

    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado.",
            ];
        }

        switch ($user->state_user_id) {
            case 2:
                return [
                    "error" => true,
                    "code" => 403,
                    "message" => "El usuario se encuentra inactivo, por favor contacte al supervisor de su seccional o administrador.",
                ];

            case 3:
                return [
                    "error" => true,
                    "code" => 403,
                    "message" => "Su solicitud de registro no se ha aprobado, por favor contacte al supervisor de su seccional o al administrador.",];
        }

        if (!$user->email_verified_at) {
            return [
                "error" => true,
                "code" => 403,
                "message" => "Debes verificar tu correo electrónico antes de iniciar sesión.",
            ];
        }

        if (!Auth::attempt($credentials)) {
            return [
                "error" => true,
                "code" => 403,
                'message' => 'Correo o contraseña incorrectos.'
            ];
        }

        $profile = $user->profile;

        $roleUser = $user->roles->first();

        $permissions = $roleUser->permissions;

        $accessToken = $this->generateAccessToken($user);

        $refreshToken = $this->generateRefreshToken($user);

        $cookieToken = cookie(
            'access_token',
            $accessToken,
            60 * 24 * 365 * 100,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        $cookieRefreshToken = cookie(
            'refresh_token',
            $refreshToken,
            60 * 24 * 365 * 100,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        return [
            "error" => false,
            "code" => 200,
            "message" => "Logueo exitoso",
            "data" => [
                'id' => $user->id,
                'full_name' => "$profile->names $profile->last_names",
                'gender' => "$profile->gender_id",
                'role_id' => $roleUser->id,
                'permissions' => $permissions->pluck('name'),
                'cookieToken' => $cookieToken,
                'cookieRefreshToken' => $cookieRefreshToken,
                'token' => $accessToken,
                'sectional_id' => $profile->organization?->sectional_id
            ],
        ];
    }

    private function generateAccessToken($user)
    {

        return $user->createToken(
            'accessToken',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.access_token_expiration'))
        )->plainTextToken;
    }

    private function generateRefreshToken($user)
    {

        return $user->createToken(
            'refreshToken',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'))
        )->plainTextToken;
    }

    public function refreshToken(string $currentRefreshToken, User $user)
    {

        $refreshToken = PersonalAccessToken::findToken($currentRefreshToken);

        $accessToken = $this->generateAccessToken($user);

        $refreshToken = $this->renewRefreshToken($refreshToken, $user) ?: $currentRefreshToken;

        $cookieToken = cookie(
            'access_token',
            $accessToken,
            60 * 24 * 365 * 100,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        $cookieRefreshToken = cookie(
            'refresh_token',
            $refreshToken,
            60 * 24 * 365 * 100,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        return [
            'cookieToken' => $cookieToken,
            'cookieRefreshToken' => $cookieRefreshToken,
        ];
    }

    private function renewRefreshToken(PersonalAccessToken $refreshToken, User $user)
    {

        $expiresToken = Carbon::parse($refreshToken->expires_at);

        $remainingTime = $expiresToken->diffInSeconds(Carbon::now(), false);

        if ($remainingTime < 60 * 60 * 24) {

            $refreshToken->delete();

            return $user->createToken(
                'refreshToken',
                [TokenAbility::ISSUE_ACCESS_TOKEN->value],
                Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'))
            )->plainTextToken;
        }

        return null;
    }

    public function createExpiredCookies()
    {
        $expiredAccessToken = cookie(
            'access_token',
            '',
            -1,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        $expiredRefreshToken = cookie(
            'refresh_token',
            '',
            -1,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        return [
            'expiredAccessToken' => $expiredAccessToken,
            'expiredRefreshToken' => $expiredRefreshToken,
        ];
    }

    public function logOut(User $user)
    {
        $user->tokens()->delete();
    }
}
