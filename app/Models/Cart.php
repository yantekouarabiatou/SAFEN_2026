<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'total',
        'item_count',
        'checkout_data'
    ];

    protected $casts = [
        'checkout_data' => 'array',
        'total' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', ' ') . ' FCFA';
    }

    public function updateTotals()
    {
        $this->item_count = $this->items->sum('quantity');
        $this->total = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        $this->save();
    }

    public static function getOrCreateCart()
    {
        if (auth()->check()) {
            // Récupérer le panier de l'utilisateur
            $cart = self::firstOrCreate([
                'user_id' => auth()->id(),
                'status' => 'active'
            ]);
        } else {
            // Panier basé sur session
            $sessionId = session()->getId();
            $cart = self::firstOrCreate([
                'session_id' => $sessionId,
                'status' => 'active'
            ]);
        }

        return $cart;
    }
}
