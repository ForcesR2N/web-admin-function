<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $table = 'picture';

    protected $fillable = [
        'filename',
        'place_id',
    ];

    protected $casts = [
        'place_id' => 'integer',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->filename ? asset("img/{$this->filename}") : null;
    }
}
