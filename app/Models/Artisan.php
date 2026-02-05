<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Artisan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_name',
        'craft',
        'bio',
        'years_experience',
        'city',
        'neighborhood',
        'latitude',
        'longitude',
        'whatsapp',
        'phone',
        'languages_spoken',
        'pricing_info',
        'rating_avg',
        'rating_count',
        'verified',
        'featured',
        'visible',
        'views',
    ];

    protected $casts = [
        'languages_spoken' => 'array',
        'verified' => 'boolean',
        'featured' => 'boolean',
        'visible' => 'boolean',
        'rating_avg' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(ArtisanPhoto::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function orders()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            Product::class,
            'artisan_id',
            'product_id',
            'id',
            'id'
        );
    }

    public function getCraftLabelAttribute()
    {
        $crafts = [
            'tisserand' => 'Tisserand',
            'sculpteur' => 'Sculpteur',
            'potier' => 'Potier',
            'forgeron' => 'Forgeron',
            'couturier' => 'Couturier traditionnel',
            'mecanicien' => 'Mécanicien',
            'vulcanisateur' => 'Vulcanisateur',
            'coiffeur' => 'Coiffeur',
            'menuisier' => 'Menuisier',
            'bijoutier' => 'Bijoutier',
            'tanneur' => 'Tanneur',
            'corroyeur' => 'Corroyeur',
            'musicien' => 'Musicien traditionnel',
            'autre' => 'Autre artisan',
        ];

        return $crafts[$this->craft] ?? $this->craft;
    }

    public function getLocationAttribute()
    {
        if ($this->neighborhood) {
            return $this->neighborhood . ', ' . $this->city;
        }
        return $this->city;
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        return $query->selectRaw(
            "id, user_id, business_name, craft, city, neighborhood, latitude, longitude,
        ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance",
            [$latitude, $longitude, $latitude]
        )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->having('distance', '<', $radius)
            ->orderBy('distance');
    }
}
