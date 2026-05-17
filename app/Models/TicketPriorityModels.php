<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPriorityModels extends BaseModel
{
    protected $table = 'ticket_priorities';

    protected $fillable = [
        'name',
        'estimated_hours',
        'created_by'
    ];

    public function tickets()
    {
        return $this->hasMany(TicketModels::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
