<?php
// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking'; // name should match the FastAPI table name

    protected $fillable = [
        'place_id',
        'user_id',
        'start_time',
        'end_time',
        'date',
        'is_confirmed',
    ];

    protected $casts = [
        'date' => 'date',
        'is_confirmed' => 'boolean',
    ];

    // Relationships that mirror FastAPI models
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Format accessors for display
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d M Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }

    public function getFormattedEndTimeAttribute()
    {
        return Carbon::parse($this->end_time)->format('H:i');
    }

    // Status badge for display
    public function getStatusBadgeAttribute()
    {
        return $this->is_confirmed
            ? ['class' => 'bg-green-100 text-green-800', 'text' => 'Confirmed']
            : ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'];
    }
}
