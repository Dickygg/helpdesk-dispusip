<?php

namespace App\Console\Commands;

use App\Helpres\ActivityHelper;
use App\Models\TicketModels;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoConfirmTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:auto-confirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto confirm resolved tickets after 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tickets = TicketModels::where('status', 'Resolved')
            ->whereNull('user_confirmed_at')
            ->whereHas('assignment', function ($query) {
                $query->whereNotNull('finished_at')
                    ->where('finished_at', '<=', now()->subDays(3));
            })
            ->get();
        $this->info('Jumlah tiket: ' . $tickets->count());

        foreach ($tickets as $t) {
            DB::beginTransaction();
            try {
                $t->update([
                    'user_confirmed_at' => now()
                ]);
                DB::commit();
                ActivityHelper::logUpdate(
                    $t,
                    before: ['Tiket' => 'Pengguna Belum Konfirmasi'],
                    after: ['Tiket' => 'Otomatis Konfirmasi'],
                );
                sendTelegram(
                    "📢 *Pemberitahuan Tiket*\n" .
                        "⚡ Code Tiket: {$t->ticket_code}\n" .
                        "📢 Pemberitahuan: Otomatis Mengkonfirmasi Tiket!.\n"
                );
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
            }
        }
    }
}
