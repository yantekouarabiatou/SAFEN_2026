<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_local',
        'audio_url',
        'ethnic_origin',
        'region',
        'category',
        'ingredients',
        'preparation',
        'description',
        'history',
        'nutritional_info',
        'occasions',
        'restaurants',
        'season',
        'slug',
        'views',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'nutritional_info' => 'array',
        'restaurants' => 'array',
    ];

    public static $categoryLabels = [
        'plat_principal' => 'Plat principal',
        'entree'         => 'Entrée',
        'accompagnement' => 'Accompagnement',
        'dessert'        => 'Dessert',
        'boisson'        => 'Boisson',
        'sauce'          => 'Sauce',
        'snack'          => 'Snack',
    ];

    // Relations
    public function images()
    {
        return $this->hasMany(DishImage::class)->orderBy('order');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'dish_vendor')
            ->withPivot(['price', 'available', 'notes'])
            ->withTimestamps()
            ->where('verified', true); // Seulement les vendeurs vérifiés
    }

    public function allVendors()
    {
        return $this->belongsToMany(Vendor::class, 'dish_vendor')
            ->withPivot(['price', 'available', 'notes'])
            ->withTimestamps();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Les avis laissés sur ce plat
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Note moyenne du plat (accesseur pratique)
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Nombre d'avis
     */
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    // Accesseurs
    public function getCategoryLabelAttribute()
    {
        return self::$categoryLabels[$this->category] ?? ucfirst($this->category);
    }

    public function getPreviewIngredientsAttribute()
    {
        return collect($this->ingredients)->take(3);
    }

    public function getMoreIngredientsCountAttribute()
    {
        return max(0, count($this->ingredients ?? []) - 3);
    }

    // Scopes
    public function scopeWithAvailableVendors($query)
    {
        return $query->with(['vendors' => function($q) {
            $q->wherePivot('available', true);
        }]);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    // Méthodes utilitaires
    public function hasVendors()
    {
        return $this->vendors()->count() > 0;
    }

    public function getMinPrice()
    {
        return $this->vendors()
            ->wherePivot('available', true)
            ->min('dish_vendor.price');
    }

    public function getMaxPrice()
    {
        return $this->vendors()
            ->wherePivot('available', true)
            ->max('dish_vendor.price');
    }

    public function getPriceRange()
    {
        $min = $this->getMinPrice();
        $max = $this->getMaxPrice();

        if (!$min) return null;

        if ($min == $max) {
            return number_format($min, 0, ',', ' ') . ' FCFA';
        }

        return number_format($min, 0, ',', ' ') . ' - ' . number_format($max, 0, ',', ' ') . ' FCFA';
    }

    public function getNearbyVendors($lat = null, $lng = null, $radius = 10)
    {
        if (!$lat || !$lng) {
            return $this->vendors()->wherePivot('available', true)->get();
        }

        return $this->vendors()
            ->wherePivot('available', true)
            ->selectRaw(
                "vendors.*, dish_vendor.price, dish_vendor.available, dish_vendor.notes,
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                sin(radians(latitude)))) AS distance",
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
    }
}
