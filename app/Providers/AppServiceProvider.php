<?php

namespace App\Providers;

use App\Services\DiscordNotificationService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $discord = app(DiscordNotificationService::class);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) use ($discord) {
                    $discord->notifyRateLimit(
                        $request->path(),
                        $request->ip(),
                        (int) ($headers['X-RateLimit-Remaining'][0] ?? 0)
                    );

                    return response()->json([
                        'message' => 'Too many attempts. Please try again later.',
                    ], 429);
                });
        });
    }
}
