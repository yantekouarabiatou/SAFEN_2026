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
        // S'assurer que les items sont chargés
        if (!$this->relationLoaded('items')) {
            $this->load('items');
        }

        $this->item_count = $this->items->sum('quantity');
        $this->total = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        $this->save();
    }

    /**
     * Nettoyer les paniers de session expirés
     */
    public static function cleanExpiredSessionCarts()
    {
        // Supprimer les paniers de session plus vieux que 30 jours
        self::whereNotNull('session_id')
            ->where('updated_at', '<', now()->subDays(30))
            ->delete();
    }

    /**
     * Fusionner le panier de session avec le panier utilisateur lors de la connexion
     */
    public static function mergeSessionCartToUser($userId, $sessionId)
    {
        $sessionCart = self::where('session_id', $sessionId)
            ->where('status', 'active')
            ->first();

        if (!$sessionCart) {
            return;
        }

        $userCart = self::where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$userCart) {
            // Convertir le panier de session en panier utilisateur
            $sessionCart->update([
                'user_id' => $userId,
                'session_id' => null
            ]);
            return;
        }

        // Fusionner les items
        foreach ($sessionCart->items as $sessionItem) {
            $existingItem = $userCart->items()
                ->where('product_id', $sessionItem->product_id)
                ->where('options', $sessionItem->options)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $sessionItem->quantity
                ]);
            } else {
                $sessionItem->update([
                    'cart_id' => $userCart->id
                ]);
            }
        }

        // Supprimer l'ancien panier de session
        $sessionCart->delete();

        // Mettre à jour les totaux
        $userCart->updateTotals();
    }

    // Dans le modèle Cart
    public static function getOrCreateCart()
    {
        $user = auth()->user();

        if ($user) {
            // Pour un utilisateur connecté, on récupère ou crée un panier lié à son compte
            $cart = static::firstOrCreate(
                ['user_id' => $user->id, 'status' => 'active'],
                ['session_id' => session()->getId()]
            );
        } else {
            // Pour un utilisateur non connecté, on utilise le session_id
            $sessionId = session()->getId();
            $cart = static::firstOrCreate(
                ['session_id' => $sessionId, 'user_id' => null, 'status' => 'active'],
                ['session_id' => $sessionId]
            );
        }

        return $cart;
    }
}
