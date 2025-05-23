<?php
// app/Models/Booking.php
// UPDATED: Fixed references dan tambah relationships

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking'; // nama tabel sesuai backend

    protected $fillable = [
        'place_id',      // sesuai backend
        'user_id',
        'start_time',    // TIME
        'end_time',      // TIME
        'date',          // DATE
        'is_confirmed',  // BOOLEAN bukan status
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'date' => 'date',
        'is_confirmed' => 'boolean',
    ];

    // Relationships - sesuai backend models
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationships untuk reviews dan payment (opsional)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Accessors untuk compatibility dengan Flutter
    public function getFormattedDateAttribute()
    {
        return $this->date->format('Y-m-d');
    }

    public function getFormattedStartTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i:s');
    }

    public function getFormattedEndTimeAttribute()
    {
        return Carbon::parse($this->end_time)->format('H:i:s');
    }

    // Status untuk display (mapping dari is_confirmed)
    public function getStatusAttribute()
    {
        return $this->is_confirmed ? 'confirmed' : 'pending';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_confirmed
            ? ['class' => 'bg-green-100 text-green-800', 'text' => 'Confirmed']
            : ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'];
    }

    // Accessor untuk mendapatkan durasi booking
    public function getDurationAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        return $end->diff($start);
    }

    public function getDurationInHoursAttribute()
    {
        return Carbon::parse($this->start_time)->diffInHours(Carbon::parse($this->end_time));
    }

    // Check if booking is in the future
    public function getIsFutureBookingAttribute()
    {
        $bookingDateTime = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->start_time);
        return $bookingDateTime->isFuture();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('is_confirmed', false);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('is_confirmed', true);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('date', '>=', Carbon::today());
    }

    // Method untuk compatibility dengan backend API
    public function toBackendFormat()
    {
        return [
            'id' => $this->id,
            'place_id' => $this->place_id,
            'user_id' => $this->user_id,
            'start_time' => $this->formatted_start_time,
            'end_time' => $this->formatted_end_time,
            'date' => $this->formatted_date,
            'is_confirmed' => $this->is_confirmed,
            'created_at' => $this->created_at?->toISOString(),
            // Include relationships if loaded
            'place' => $this->relationLoaded('place') ? $this->place?->toBackendFormat() : null,
            'user' => $this->relationLoaded('user') ? [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ] : null,
        ];
    }

    // Method untuk Flutter format
    public function toFlutterFormat()
    {
        return [
            'id' => $this->id,
            'placeId' => $this->place_id,
            'userId' => $this->user_id,
            'startTime' => $this->formatted_start_time,
            'endTime' => $this->formatted_end_time,
            'date' => $this->formatted_date,
            'isConfirmed' => $this->is_confirmed,
            'createdAt' => $this->created_at?->toISOString(),
        ];
    }

    // Static method untuk check konflik booking
    public static function hasConflict($placeId, $date, $startTime, $endTime, $excludeId = null)
    {
        $query = self::where('place_id', $placeId)
            ->where('date', $date)
            ->where(function($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function($subQuery) use ($startTime, $endTime) {
                      $subQuery->where('start_time', '<=', $startTime)
                               ->where('end_time', '>=', $endTime);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
