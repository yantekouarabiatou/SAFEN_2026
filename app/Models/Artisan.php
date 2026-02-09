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
        'status', // Ajouté
        'verified',
        'featured',
        'visible',
        'views',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        'approved_by',
    ];

    protected $casts = [
        'languages_spoken' => 'array',
        'verified' => 'boolean',
        'featured' => 'boolean',
        'visible' => 'boolean',
        'rating_avg' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Scopes pour filtrer par statut
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeVisibleToPublic($query)
    {
        return $query->where('status', 'approved')
            ->where('visible', true);
    }

    // Relations
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Méthodes pour changer le statut
    public function approve(User $approver = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approver?->id,
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
    }

    public function reject(string $reason = null, User $rejector = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'approved_at' => null,
        ]);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }



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
            'commercante' => 'Vendeuses ou vendeurs traditionnel',
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

    public function getRatingAvgAttribute($value)
    {
        return $value ?? 0;
    }

    public function getRatingCountAttribute($value)
    {
        return $value ?? 0;
    }

    public function calculateRating()
    {
        $reviews = $this->reviews();
        $this->rating_avg = $reviews->avg('rating') ?? 0;
        $this->rating_count = $reviews->count();
        $this->save();
    }
}
