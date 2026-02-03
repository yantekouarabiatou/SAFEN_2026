<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'address',
        'city',
        'latitude',
        'longitude',
        'phone',
        'whatsapp',
        'specialties',
        'description',
        'opening_hours',
        'rating_avg',
        'rating_count',
        'verified',
    ];

    protected $casts = [
        'specialties' => 'array',
        'verified' => 'boolean',
        'rating_avg' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_vendor')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'restaurant' => 'Restaurant',
            'maquis' => 'Maquis',
            'street_vendor' => 'Vendeur de rue',
            'market_stand' => 'Étal de marché',
            'home_cook' => 'Cuisinière à domicile',
        ];

        return $types[$this->type] ?? $this->type;
    }
}
