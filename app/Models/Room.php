<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ruang',
        'image',
        'deskripsi',
        'status'
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'id_rooms');
    }
}
