<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'onboarding' => \App\Http\Middleware\EnsureOnboardingComplete::class,
            'admin' => \App\Http\Middleware\CheckIfAdmin::class,
            'superadmin' => \App\Http\Middleware\CheckIfSuperAdmin::class,
            'content' => \App\Http\Middleware\CheckIfContentEditor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $e): void {
            $context = [
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ];

            if (!app()->runningInConsole()) {
                $context = array_merge($context, [
                    'method' => request()->method(),
                    'path' => request()->path(),
                    'route' => optional(request()->route())->getName(),
                    'user_id' => request()->user()?->id,
                    'ip' => request()->ip(),
                ]);
            }

            Log::channel('api')->error('Application exception reported', $context);
        });
    })->create();
