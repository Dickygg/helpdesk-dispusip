<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('sendgroupTelegram')) {

    function sendgroupTelegram(string $message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chat_id = env('TELEGRAM_GROUP_ID');

        return Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);
    }
}
