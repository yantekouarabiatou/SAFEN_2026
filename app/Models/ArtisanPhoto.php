<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ArtisanPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'artisan_id',
        'photo_url',
        'caption',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    public function getFullUrlAttribute()
    {
        return Storage::url($this->photo_url);
    }
}
