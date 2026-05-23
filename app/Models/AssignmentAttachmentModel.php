<?php

namespace App\Models;

class AssignmentAttachmentModel extends BaseModel
{
    protected $table = 'assignment_attachments';

    protected $fillable = [
        'ticket_assignment_id',
        'uploaded_by',
        'file_path',
        'file_name',
        'file_type',
    ];

    public function assignment()
    {
        return $this->belongsTo(
            TicketAssignmentModels::class,
            'ticket_assignment_id',
            'id'
        );
    }

    public function uploader()
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by',
            'id'
        );
    }
}
