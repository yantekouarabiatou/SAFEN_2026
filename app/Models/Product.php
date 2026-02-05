<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

     public function getFullUrlAttribute()
    {
        return Storage::url($this->image_url);
    }


    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Accesseurs
    public function getIsAvailableAttribute()
    {
        return $this->available_for_order && $this->is_active;
    }

    public function getRequiredDepositAmountAttribute()
    {
        if (!$this->requires_deposit) {
            return 0;
        }
        return round($this->price * ($this->deposit_percentage / 100), 0);
    }

    public function getFormattedDepositAttribute()
    {
        return number_format($this->required_deposit_amount, 0, ',', ' ') . ' FCFA';
    }

    public function getProductionTimeTextAttribute()
    {
        $days = $this->production_time_days;
        
        if ($days <= 7) {
            return "1 semaine";
        } elseif ($days <= 14) {
            return "2 semaines";
        } elseif ($days <= 21) {
            return "3 semaines";
        } elseif ($days <= 30) {
            return "1 mois";
        } else {
            $months = ceil($days / 30);
            return $months . " mois";
        }
    }

    public function getEstimatedDeliveryDateAttribute()
    {
        return now()->addDays($this->production_time_days)->format('d/m/Y');
    }

    // Méthodes
    public function canBeOrdered($quantity = 1)
    {
        if (!$this->is_available) {
            return [
                'can_order' => false,
                'message' => 'Ce produit n\'est pas disponible pour le moment.'
            ];
        }

        if ($quantity < $this->min_order_quantity) {
            return [
                'can_order' => false,
                'message' => "Quantité minimum : {$this->min_order_quantity}"
            ];
        }

        if ($this->max_order_quantity && $quantity > $this->max_order_quantity) {
            return [
                'can_order' => false,
                'message' => "Quantité maximum : {$this->max_order_quantity}"
            ];
        }

        return [
            'can_order' => true,
            'message' => 'Produit disponible sur commande'
        ];
    }
}

