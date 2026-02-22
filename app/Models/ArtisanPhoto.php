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
        // Option 1 : utiliser asset() si vos fichiers sont dans public/storage
        return asset('storage/' . $this->photo_url);
    }
}
