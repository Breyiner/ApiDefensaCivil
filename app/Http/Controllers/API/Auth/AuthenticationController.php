<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\User;

/**
 * Controlador de Autenticación.
 * Gestiona el ciclo de vida de la sesión del usuario mediante tokens y cookies.
 */
class AuthenticationController extends Controller
{
    protected $authService;

    /**
     * Inyección del servicio de autenticación encargado de la lógica de tokens.
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Registra un nuevo usuario en el sistema.
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $response = $this->authService->register($data);

        if($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Autentica al usuario y establece las cookies de seguridad.
     * Utiliza un enfoque de cookies para el Access Token y el Refresh Token.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials);

        if ($result['error']) {
            return ResponseFormatter::error($result['message'], $result['code']);
        }

        // Extraemos las cookies del resultado del servicio
        $cookieToken = $result['data']['cookieToken'];
        $cookieRefresh = $result['data']['cookieRefreshToken'];

        /**
         * Retornamos éxito, pero limpiamos el array de datos para no enviar 
         * los tokens en el cuerpo del JSON (ya que irán en las cookies).
         */
        return ResponseFormatter::success(
            $result['message'], 
            $result['code'],
            array_diff_key($result['data'], array_flip(['cookieToken', 'cookieRefreshToken']))
        )
        ->cookie($cookieToken)
        ->cookie($cookieRefresh);
    }

    /**
     * Renueva la validez de la sesión usando el Refresh Token.
     */

    public function refreshToken(Request $request) 
    {
        $currentRefreshToken = $request->bearerToken() ?? $request->cookie('refresh_token');

        if (!$currentRefreshToken) {
            return ResponseFormatter::error('No se proporcionó token de refresco', 401);
        }

        $result = $this->authService->refreshToken($currentRefreshToken);

        if (isset($result['error']) && $result['error']) {
            return ResponseFormatter::error($result['message'], $result['code']);
        }

        return ResponseFormatter::success(
            'Token refrescado exitosamente',
            200,
            [
                'token'         => $result['token'] ?? null,
                'refresh_token' => $result['refresh_token'] ?? null,
            ]
        );
    }

    // public function refreshToken(Request $request) 
    // {
    //     // $user = Auth::user();

    //     // 1. Obtener el token del Header Bearer o de la Cookie 'refresh_token'
    //     $currentRefreshToken = $request->bearerToken() ?? $request->cookie('refresh_token');

    //     // $result = $this->authService->refreshToken($currentRefreshToken, $user);

    //     if (!$currentRefreshToken) {
    //         return ResponseFormatter::error('No se proporcionó token de refresco', 401);
    //     }

    //     //---
    //     // if (isset($result['error']) && $result['error']) {
            
    //     //     return ResponseFormatter::error($result['message'], $result['code']);
    //     // }
    //     //---

    //     // 2. No pasamos $user al servicio (el servicio lo obtendrá del token en BD)
    //     $result = $this->authService->refreshToken($currentRefreshToken);

    //     if (isset($result['error']) && $result['error']) {
    //         return ResponseFormatter::error($result['message'], $result['code']);
    //     }

    //     // Se envían los nuevos tokens mediante cookies actualizadas
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Token refrescado exitosamente',
    //         'data' => []
    //     ])
    //     ->withCookie($result['cookieToken'])
    //     ->withCookie($result['cookieRefreshToken']);
    // }

    /**
     * Cierra la sesión del usuario.
     * Invalida los tokens en el servidor y expira las cookies en el cliente.
     */
    public function logOut(Request $request)
    {
        $user = Auth::user();
        
        // Lógica de revocación de tokens en la BD
        $this->authService->logOut($user);
        
        // Generación de cookies con tiempo de vida negativo para eliminarlas
        $expiredCookies = $this->authService->createExpiredCookies();
        
        return response()->json([
            'success' => true, 
            'message' => 'Sesión cerrada con éxito'
        ])
        ->cookie($expiredCookies['expiredAccessToken'])
        ->cookie($expiredCookies['expiredRefreshToken']);
    }
}