<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'artisan_id',
        'name',
        'name_local',
        'audio_url',
        'category',
        'subcategory',
        'ethnic_origin',
        'materials',
        'price',
        'currency',
        'stock_status',
        'width',
        'height',
        'depth',
        'weight',
        'description',
        'description_cultural',
        'description_technical',
        'slug',
        'featured',
        'views',
        'order_count',
    ];

    protected $casts = [
        'materials' => 'array',
        'price' => 'decimal:2',
        'featured' => 'boolean',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'depth' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    public function getPriceInCurrencyAttribute()
    {
        $currencies = [
            'XOF' => 'FCFA',
            'EUR' => '€',
            'USD' => '$',
        ];

        $symbol = $currencies[$this->currency] ?? $this->currency;
        return number_format($this->price, $this->currency === 'XOF' ? 0 : 2, ',', ' ') . ' ' . $symbol;
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            'masque' => 'Masque traditionnel',
            'sculpture' => 'Sculpture',
            'tissu' => 'Tissu traditionnel',
            'bijou' => 'Bijou',
            'instrument' => 'Instrument de musique',
            'decoration' => 'Décoration',
            'peinture' => 'Peinture',
            'vannerie' => 'Vannerie',
            'poterie' => 'Poterie',
            'autre' => 'Autre',
        ];

        return $categories[$this->category] ?? $this->category;
    }
}
