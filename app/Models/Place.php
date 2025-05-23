<?php
// app/Models/Place.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $table = 'place'; 

    protected $fillable = [
        'name',
        'address',
        'description',
        'maps_url',
        'category_id',
        'city_id',
        'host_id',
        'rules',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
