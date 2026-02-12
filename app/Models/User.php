<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'address',
        'city',
        'language',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function artisan()
    {
        return $this->hasOne(Artisan::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function isArtisan()
    {
        return $this->role === 'artisan';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.png');
    }


    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function hasRole($role)
    {
        if ($this->role === $role) {
            return true;
        }

        if ($role === 'artisan' && $this->artisan) {
            return true;
        }

        return false;
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
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

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Relation avec les commandes
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
