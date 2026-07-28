<?php

use App\Jobs\SendTelegramNotification;
use Illuminate\Support\Facades\Http;


if (!function_exists('sendTelegram')) {

    function sendTelegram(string $message)
    {
        $chat_id = env('TELEGRAM_CHAT_ID');

        SendTelegramNotification::dispatch($message, $chat_id);
    }
}
