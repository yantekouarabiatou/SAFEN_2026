<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total',
        'item_count',
        'shipping_address',
        'shipping_city',
        'shipping_neighborhood',
        'shipping_phone',
        'shipping_notes',
        'billing_address',
        'billing_city',
        'payment_method',
        'payment_status',
        'payment_reference',
        'shipping_fee',
        'tax_amount',
        'artisan_notes',
        'tracking_number',
        'estimated_delivery',
        'delivered_at',
        'paid_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'estimated_delivery' => 'date',
        'delivered_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', ' ') . ' FCFA';
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'processing' => 'En cours',
            'shipped' => 'Expédié',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'paid' => 'Payé',
            'failed' => 'Échoué',
            'refunded' => 'Remboursé',
        ];

        return $statuses[$this->payment_status] ?? $this->payment_status;
    }
}
