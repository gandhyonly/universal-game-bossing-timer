<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DiscordNotifier
{
    public function send(string $content): bool
    {
        $webhookUrl = config('services.discord.webhook_url');
        if (empty($webhookUrl)) {
            return false;
        }

        $response = Http::asJson()->post($webhookUrl, [
            'content' => $content,
        ]);

        return $response->successful();
    }
}


