<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DishImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'dish_id',
        'image_url',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    // Accesseur pour l'URL
    public function getFullUrlAttribute()
    {
        // Utilise asset() pour générer l'URL directement depuis le dossier public
        $path = str_replace('dishes/', '', $this->image_url);
        return asset('dishes/' . $path);
    }
}
