<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'user_id',
        'venue_name',
        'user_name',
        'start_date',
        'end_date',
        'capacity',
        'contact_info',
        'status',
        'processed_at',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
