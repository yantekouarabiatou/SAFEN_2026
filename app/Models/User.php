<?php

namespace App\Models;

use App\Models\Artisan as ModelsArtisan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // ✅ HasRoles gère hasRole() nativement

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'address',
        'city',
        'language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─────────────────────────────────────────────
    // RELATIONS
    // ─────────────────────────────────────────────

    public function artisan()
    {
        return $this->hasOne(ModelsArtisan::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(GuestOrder::class, 'user_id');
    }

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')
            ->whereNull('read_at');
    }

    public function recentMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')
            ->latest()
            ->with('sender');
    }

    public function conversations()
    {
        return Conversation::where('user1_id', $this->id)
            ->orWhere('user2_id', $this->id)
            ->with('messages');
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    // ─────────────────────────────────────────────
    // HELPERS — utilisent Spatie nativement
    // ─────────────────────────────────────────────

    /**
     * ✅ Utilise Spatie (HasRoles) — NE PAS réécrire hasRole()
     * Exemples d'utilisation :
     *   $user->hasRole('admin')
     *   $user->hasAnyRole(['admin', 'super-admin'])
     *   $user->can('voir produits')
     */

    public function isAdmin(): bool
    {
        // ✅ Utilise la méthode Spatie, pas une colonne 'role'
        return $this->hasRole(['admin', 'super-admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function isArtisan(): bool
    {
        return $this->hasRole('artisan');
    }

    public function isVendor(): bool
    {
        return $this->hasRole('vendor');
    }

    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

    public function isOnline(): bool
    {
        return $this->last_seen && $this->last_seen->diffInMinutes(now()) < 5;
    }

    public function cartTotal(): float|int
    {
        return $this->cartItems()->sum('total');
    }
}