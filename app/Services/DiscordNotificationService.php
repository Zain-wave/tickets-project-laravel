<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DiscordNotificationService
{
    private string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = env('DISCORD_WEBHOOK', '');
    }

    public function notifyError(string $endpoint, string $method, string $message, string $ip): void
    {
        $this->send([
            'embeds' => [
                [
                    'title' => 'Server Error',
                    'description' => "**Endpoint:** `{$method} {$endpoint}`\n**Error:** {$message}\n**IP:** {$ip}\n**Date:** " . now()->toIso8601String(),
                    'color' => 16711680,
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
        ]);
    }

    public function notifyRateLimit(string $endpoint, string $ip, int $attempts): void
    {
        $this->send([
            'embeds' => [
                [
                    'title' => 'Rate Limit Exceeded',
                    'description' => "**Endpoint:** `{$endpoint}`\n**IP:** {$ip}\n**Attempts:** {$attempts}\n**Timestamp:** " . now()->toIso8601String(),
                    'color' => 16776960,
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
        ]);
    }

    private function send(array $data): void
    {
        if (empty($this->webhookUrl)) {
            return;
        }

        Http::timeout(5)->post($this->webhookUrl, $data);
    }
}
