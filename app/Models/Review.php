<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'comment',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'rating' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Scopes
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

    // Accesseurs
    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-warning"></i>';
            }
        }
        return $stars;
    }

    public function getReviewableNameAttribute()
    {
        if (!$this->reviewable) {
            return 'Élément supprimé';
        }

        switch (class_basename($this->reviewable_type)) {
            case 'Product':
                return $this->reviewable->name;
            case 'Artisan':
                return $this->reviewable->user->name ?? 'Artisan';
            case 'Vendor':
                return $this->reviewable->name;
            default:
                return 'N/A';
        }
    }

    public function getReviewableTypeLabelAttribute()
    {
        switch (class_basename($this->reviewable_type)) {
            case 'Product':
                return '<span class="badge badge-info">Produit</span>';
            case 'Artisan':
                return '<span class="badge badge-warning">Artisan</span>';
            case 'Vendor':
                return '<span class="badge badge-success">Vendeur</span>';
            default:
                return '<span class="badge badge-secondary">Inconnu</span>';
        }
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'approved' => '<span class="badge badge-success">Approuvé</span>',
            'pending' => '<span class="badge badge-warning">En attente</span>',
            'rejected' => '<span class="badge badge-danger">Rejeté</span>',
            default => '<span class="badge badge-secondary">' . $this->status . '</span>'
        };
    }
}