<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceUnitsModels extends BaseModel
{
    protected $table = 'service_units';

    protected $fillable = [
        'name',
        'description'
    ];

    // RELASI
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(TicketModels::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
