<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

use App\Http\Middlewares\ForceJsonRequestHeader;
use App\Exceptions\ApiExceptionHandler;
use App\Http\Middlewares\EnsureEmailIsVerified;
use App\Http\Middlewares\RequirePasswordVerification;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            /**
             * RUTAS PÚBLICAS
             * No requieren autenticación
             */
            $publicRoutes = [
                'auth',           // login, register
                'public',         // catálogos públicos
                'password-reset', // recuperación de contraseña
            ];

            foreach ($publicRoutes as $routeName) {
                $routePath = base_path("routes/api/{$routeName}.php");
                
                if (file_exists($routePath)) {
                    Route::middleware('api')
                        ->prefix('api')
                        ->name("{$routeName}.")
                        ->group($routePath);
                }
            }

            /**
             * RUTAS PROTEGIDAS
             * Requieren: auth:sanctum + email verificado
             */
            $protectedRoutes = glob(base_path('routes/api/*.php'));
            
            foreach ($protectedRoutes as $routeFile) {
                $fileName = basename($routeFile, '.php');
                
                // Saltar si es una ruta pública
                if (in_array($fileName, $publicRoutes)) {
                    continue;
                }
                
                Route::middleware(['api', 'auth:sanctum', 'verified'])
                    ->prefix('api')
                    ->name("{$fileName}.")
                    ->group($routeFile);
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'force.json' => ForceJsonRequestHeader::class,
            'ability' => CheckForAnyAbility::class,
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'password.verify' => RequirePasswordVerification::class,
            'verified' => EnsureEmailIsVerified::class,
        ]);

        $middleware->api(prepend: [
            'force.json'
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(fn (Throwable $e, $request) => ApiExceptionHandler::handle($e) );
        
    })->create();