<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room';

    protected $fillable = [
        'name',
        'price',
        'description',
        'max_capacity',
        'place_id',
    ];

    protected $casts = [
        'price' => 'integer',
        'max_capacity' => 'integer',
        'place_id' => 'integer',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
