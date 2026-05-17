<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttachmentModels extends BaseModel
{
    protected $table = 'attachments';

    protected $fillable = [
        'ticket_id',
        'uploaded_by',
        'file_path',
        'file_name',
        'file_type'
    ];

    public function ticket()
    {
        return $this->belongsTo(TicketModels::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
