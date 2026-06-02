<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketsTypeModels extends BaseModel
{
    use HasFactory;

    protected $table = 'ticket_types';

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    /**
     * Relasi ke tiket
     * 1 Tipe Tiket memiliki banyak tiket
     */
    public function tickets()
    {
        return $this->hasMany(
            TicketModels::class,
            'ticket_type_id',
            'id'
        );
    }

    /**
     * User yang membuat tipe tiket
     */
    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }
}
