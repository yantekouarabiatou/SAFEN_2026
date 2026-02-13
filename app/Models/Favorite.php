<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favoritable_type',
        'favoritable_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritable()
    {
        return $this->morphTo();
    }

    public function getIsFavoritedAttribute()
    {
        if (!auth()->check()) return false;
        return auth()->user()->favorites()
            ->where('favoritable_id', $this->id)
            ->where('favoritable_type', self::class)
            ->exists();
    }
}
