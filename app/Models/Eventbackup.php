<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eventbackup extends Model
{
    use HasFactory;

    protected $table='eventsbackup';

    protected $fillable = [
        'nama',
        'acara',
        'id_rooms',
        'nama_rooms',
        'asalbidang',
        'date',
        'start',
        'finish',
        'peserta',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_rooms');
    }

    public static function isRoomAvailable($roomId, $startTime, $endTime, $date)
    {
        return !self::where('id_rooms', $roomId)
                    ->whereDate('date', $date)
                    ->where(function($query) use ($startTime, $endTime) {
                        $query->whereBetween('start', [$startTime, $endTime])
                              ->orWhereBetween('finish', [$startTime, $endTime])
                              ->orWhere(function($query) use ($startTime, $endTime) {
                                  $query->where('start', '<', $startTime)
                                        ->where('finish', '>', $endTime);
                              });
                    })->exists();
    }
    
}
