<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary',
        'order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order'      => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accesseur utile pour l'URL complète
    public function getFullUrlAttribute()
    {
        return Storage::url($this->image_url);
        // ou si tu préfères asset() pour les anciennes images :
        // return asset('images/' . $this->image_url);
    }
}
