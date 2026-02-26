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
        // Utilise asset() pour générer l'URL directement depuis le dossier public
        // Enlève le préfixe "products/" si l'URL contient déjà ce chemin
        $path = str_replace('products/', '', $this->image_url);
        return asset('products/' . $path);
    }
}
