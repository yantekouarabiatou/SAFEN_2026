<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'artisan_id',
        'product_id',
        'subject',
        'description',
        'budget',
        'desired_date',
        'status',
        'response',
        'response_date',
        'amount',
        'accepted_at'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'amount' => 'decimal:2',
        'desired_date' => 'date',
        'response_date' => 'datetime',
        'accepted_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'responded' => 'Répondu',
            'accepted' => 'Accepté',
            'rejected' => 'Refusé',
            'expired' => 'Expiré'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getFormattedBudgetAttribute()
    {
        return $this->budget ? number_format($this->budget, 0, ',', ' ') . ' FCFA' : 'Non spécifié';
    }

    public function getFormattedAmountAttribute()
    {
        return $this->amount ? number_format($this->amount, 0, ',', ' ') . ' FCFA' : '-';
    }
}
