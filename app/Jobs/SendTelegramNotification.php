<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected string $message;
    protected string $chat_id;

    /**
     * Create a new job instance.
     */
    public function __construct(string $message, string $chat_id)
    {
        $this->message = $message;
        $this->chat_id = $chat_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $token = env('TELEGRAM_BOT_TOKEN');

            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $this->chat_id,
                'text' => $this->message,
                'parse_mode' => 'Markdown'
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
        }
    }
}
