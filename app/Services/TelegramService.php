<?php

namespace App\Services;

use GuzzleHttp\Client;

class TelegramService
{
    public function send(string $message): void
    {
        $client = new Client(['base_uri' => 'https://api.telegram.org/']);
        $extraUrl = '/bot' . env('TELEGRAM_BOT_API_KEY') . '/sendMessage';
        $queryString = ['query' => ['chat_id' => env('CHAT_ID'),
        'text' => $message, 'parse_mode' => 'markdown']];

        $client->get($extraUrl, $queryString);
    }
}