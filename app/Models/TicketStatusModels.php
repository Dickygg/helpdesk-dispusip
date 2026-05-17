<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatusModels extends BaseModel
{
    protected $table = 'ticket_statuses';

    protected $fillable = [
        'name',
        'description'
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
