<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'status',
        'notes',
    ];

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
}
