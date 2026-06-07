<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property Carbon $assigned_at
 */
class TicketAssignmentModels extends BaseModel
{
    protected $table = 'ticket_assignments';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'assigned_by',
        'assigned_at',
        'started_at',
        'finished_at',
        'work_duration',
        'status',
        'note',
    ];
    protected $casts = [
        'started_at' => 'datetime',
        'finish_at'  => 'datetime',
        'assigned_at' => 'datetime',
    ];
    public function formattedWorkDuration(): string
    {
        $menit = $this->work_duration;

        if (!$menit) return '-';

        $jam  = intdiv($menit, 60);
        $sisa = $menit % 60;

        $result = '';
        if ($jam > 0)  $result .= $jam . ' jam ';
        if ($sisa > 0) $result .= $sisa . ' menit';

        return trim($result) ?: '-';
    }

    public function ticket()
    {
        return $this->belongsTo(TicketModels::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function Assignattachments()
    {
        return $this->hasOne(
            AssignmentAttachmentModel::class,
            'ticket_assignment_id',
            'id'
        );
    }
}
