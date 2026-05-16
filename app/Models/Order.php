<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_code', 'user_id', 'voucher_id', 'subtotal', 
        'discount_amount', 'total_amount', 'status', 
        'idempotency_key', 'notes', 'expires_at', 'paid_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, OrderItem::class);
    }
}
