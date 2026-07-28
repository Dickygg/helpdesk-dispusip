<?php

use App\Jobs\SendTelegramNotification;
use Illuminate\Support\Facades\Http;

if (!function_exists('sendgroupTelegram')) {

    function sendgroupTelegram(string $message)
    {

        $chat_id = env('TELEGRAM_GROUP_ID');

        SendTelegramNotification::dispatch($message, $chat_id);
    }
}
