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
        'recipe',
        'nutritional_info',
        'cultural_description',
        'occasions',
        'season',
        'slug',
        'views',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'nutritional_info' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(DishImage::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'dish_vendor')
            ->withTimestamps();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            'plat principal' => 'Plat principal',
            'entree'         => 'Entrée',
            'accompagnement' => 'Accompagnement',
            'dessert'        => 'Dessert',
            'boisson'        => 'Boisson',
            'sauce'          => 'Sauce',
            'snack'          => 'Snack',
        ];

        return $categories[$this->category] ?? $this->category;
    }

    public function getPreviewIngredientsAttribute()
    {
        return collect($this->ingredients)->take(3);
    }

    public function getMoreIngredientsCountAttribute()
    {
        return max(0, count($this->ingredients) - 3);
    }
}
