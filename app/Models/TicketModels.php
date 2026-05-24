<?php

namespace App\Models;

use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TicketModels extends BaseModel
{

    use LogsActivity, HasActivityLog;
    /** @var array<string> */
    protected static $recordEvents = [];

    protected $table = "tickets";

    protected $fillable = [
        'ticket_code',
        'user_id',
        'application_id',
        'priority_id',
        'status',
        'title',
        'description',
        'user_confirmade_at',
        'admin_verified_at',
        'verification_status',
        'note',
        'due_date',
        'closed_at',
        'closed_by',
        'reason_rejected'
    ];


    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->dontSubmitEmptyLogs();
    }


    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->ticket_code = self::generateCode();
        });
    }

    public static function generateCode()
    {
        $today = now()->format('Ymd');

        do {
            $lastNumber = self::where('ticket_code', 'like', 'APK-' . $today . '-%')
                ->selectRaw('MAX(CAST(RIGHT(ticket_code, 4) AS UNSIGNED)) as max_number')
                ->value('max_number');

            $number = $lastNumber ? $lastNumber + 1 : 1;

            $code = 'APK-' . $today . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            // cek apakah sudah ada
            $exists = self::where('ticket_code', $code)->exists();
        } while ($exists);

        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function application()
    {
        return $this->belongsTo(ApplicationModels::class, 'application_id');
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriorityModels::class, 'priority_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatusModels::class, 'status_id');
    }

    public function assignment()
    {
        return $this->hasOne(TicketAssignmentModels::class, 'ticket_id');
    }

    public function logs()
    {
        return $this->hasMany(TicketLogModels::class, 'ticket_id');
    }

    public function attachments()
    {
        return $this->hasMany(AttachmentModels::class, 'ticket_id');
    }

    public function serviceUnit()
    {
        return $this->belongsTo(ServiceUnitsModels::class, 'service_unit_id');
    }
}
