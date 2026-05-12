<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        Integration::handles($exceptions);

        $exceptions->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }

            $statusCode = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException
                ? $e->getStatusCode()
                : 500;

            if ($statusCode === 429) {
                return;
            }

            if ($statusCode >= 500) {
                try {
                    $discord = app(\App\Services\DiscordNotificationService::class);
                    $request = request();
                    $discord->notifyError(
                        endpoint: $request?->path() ?? 'unknown',
                        method: $request?->method() ?? 'N/A',
                        message: $e->getMessage(),
                        ip: $request?->ip() ?? 'unknown',
                    );
                } catch (\Throwable) {
                }
            }
        });
    })->create();
