<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Inertia\ExceptionResponse;
use Inertia\Inertia;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'password.confirm' => RequirePassword::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Inertia::handleExceptionsUsing(function (ExceptionResponse $response) {
            if (! in_array($response->statusCode(), [403, 404, 419, 500, 503], true)) {
                return null;
            }

            return $response
                ->render('ErrorPage', [
                    'status' => $response->statusCode(),
                ])
                ->withSharedData();
        });
    })->create();
