<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_address',
        'guest_city',
        'guest_country',
        'subtotal',
        'delivery_fee',
        'total_amount',
        'deposit_amount',
        'remaining_amount',
        'payment_status',
        'order_status',
        'payment_method',
        'payment_reference',
        'deposit_paid_at',
        'fully_paid_at',
        'order_items',
        'customer_notes',
        'admin_notes'
    ];

    protected $casts = [
        'order_items' => 'array',
        'deposit_paid_at' => 'datetime',
        'fully_paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Générer un numéro de commande unique
    public static function generateOrderNumber()
    {
        do {
            $number = 'CMD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $number)->exists());
        
        return $number;
    }

    // Calculer l'acompte (30% par défaut)
    public static function calculateDeposit($total, $percentage = 30)
    {
        return round($total * ($percentage / 100), 2);
    }

    // Accesseurs
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedDepositAttribute()
    {
        return number_format($this->deposit_amount, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedRemainingAttribute()
    {
        return number_format($this->remaining_amount, 0, ',', ' ') . ' FCFA';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge" style="background: var(--benin-yellow); color: #333;">En attente</span>',
            'confirmed' => '<span class="badge" style="background: var(--benin-green); color: white;">Confirmée</span>',
            'processing' => '<span class="badge" style="background: #3498db; color: white;">En préparation</span>',
            'shipped' => '<span class="badge" style="background: #9b59b6; color: white;">Expédiée</span>',
            'delivered' => '<span class="badge" style="background: var(--benin-green); color: white;">Livrée</span>',
            'cancelled' => '<span class="badge" style="background: var(--benin-red); color: white;">Annulée</span>',
        ];

        return $badges[$this->order_status] ?? '';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">En attente</span>',
            'partial' => '<span class="badge bg-info">Acompte payé</span>',
            'paid' => '<span class="badge bg-success">Payée</span>',
            'failed' => '<span class="badge bg-danger">Échouée</span>',
        ];

        return $badges[$this->payment_status] ?? '';
    }
}