<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationModels extends BaseModel
{
    protected $table = 'applications';

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
