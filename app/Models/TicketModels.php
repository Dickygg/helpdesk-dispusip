<?php

namespace App\Models;

use App\Traits\HasActivityLog;
use Carbon\Carbon;
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
        'user_confirmed_at',
        'admin_verified_at',
        'verification_status',
        'note',
        'due_date',
        'closed_at',
        'closed_by',
        'reason_rejected',
        'rejected_at',
        'ticket_type_id'
    ];


    protected $casts = [
        'due_date' => 'datetime',
        'rejected_at'  => 'datetime',
        'closed_at' => 'datetime',
        'user_confirmed_at' => 'datetime'
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
        return \DB::transaction(function () {
            $today = now()->format('Ymd');

            $lastNumber = self::lockForUpdate()
                ->selectRaw('MAX(CAST(RIGHT(ticket_code, 4) AS UNSIGNED)) as max_number')
                ->value('max_number'); // ← tidak filter by tanggal

            $number = $lastNumber ? $lastNumber + 1 : 1;

            return 'APK-' . $today . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    // App\Models\Ticket.php

    public function getKinerjaAttribute(): array
    {
        $finishedAt = $this->assignment?->finished_at
            ? Carbon::parse($this->assignment->finished_at)
            : null;

        $dueDate = $this->due_date
            ? Carbon::parse($this->due_date)
            : null;

        if (!$dueDate) {
            return ['label' => 'Belum Ditentukan', 'style' => 'text-secondary', 'icon' => 'bi-question-circle'];
        } elseif ($finishedAt) {
            if ($finishedAt->lte($dueDate)) {
                return ['label' => 'Tepat Waktu', 'style' => 'text-success', 'icon' => 'bi-check-circle-fill'];
            } else {
                $diff = $dueDate->diff($finishedAt);
                $label = 'Melewati Deadline (' . ($diff->days > 0 ? $diff->days . 'h ' : '') . $diff->h . 'j ' . $diff->i . 'm)';
                return ['label' => $label, 'style' => 'text-danger', 'icon' => 'bi-x-circle-fill'];
            }
        } else {
            if (now()->gt($dueDate)) {
                $diff = $dueDate->diff(now());
                $label = 'Melewati Deadline (' . ($diff->days > 0 ? $diff->days . 'h ' : '') . $diff->h . 'j ' . $diff->i . 'm)';
                return ['label' => $label, 'style' => 'text-danger', 'icon' => 'bi-exclamation-circle-fill'];
            }
            return ['label' => 'Dalam Pengerjaan', 'style' => 'text-warning', 'icon' => 'bi-hourglass-split'];
        }
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
    public function tickettype()
    {
        return $this->belongsTo(
            TicketsTypeModels::class,
            'ticket_type_id',
            'id'
        );
    }

    public function serviceUnit()
    {
        return $this->belongsTo(ServiceUnitsModels::class, 'service_unit_id');
    }
}
