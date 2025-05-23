<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $table = 'place'; // sesuai backend table name

    protected $fillable = [
        'name',
        'address',
        'description',
        'maps_url',
        'category_id',
        'facility_id',
        'activity_id',
        'city_id',
        'host_id',
        'rules',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'facility_id' => 'integer',
        'activity_id' => 'integer',
        'city_id' => 'integer',
        'host_id' => 'integer',
    ];

    // Relationships - sesuai dengan backend models.py
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Accessor untuk get minimum price from rooms
    public function getPriceAttribute()
    {
        return $this->rooms()->min('price') ?? 0;
    }

    // Accessor untuk get max capacity from rooms
    public function getMaxCapacityAttribute()
    {
        return $this->rooms()->max('max_capacity') ?? 0;
    }

    // Accessor untuk first picture URL
    public function getFirstPictureUrlAttribute()
    {
        $firstPicture = $this->pictures()->first();
        return $firstPicture ? asset("img/{$firstPicture->filename}") : null;
    }

    // Scope untuk searching
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
    }

    // Format untuk API response (compatible dengan backend)
    public function toBackendFormat()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'description' => $this->description,
            'maps_url' => $this->maps_url,
            'category_id' => $this->category_id,
            'facility_id' => $this->facility_id,
            'activity_id' => $this->activity_id,
            'city_id' => $this->city_id,
            'host_id' => $this->host_id,
            'rules' => $this->rules,
            'price' => $this->price,
            'max_capacity' => $this->max_capacity,
            'first_picture' => $this->pictures()->first()?->filename,
        ];
    }
}
