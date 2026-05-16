<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'orders'; 

    protected $fillable = [
        'order_code',
        'user_id',
        'voucher_id',
        'subtotal',
        'discount_amount',
        'total_amount',
        'status',
        'idempotency_key',
        'notes',
        'expires_at',
        'paid_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];
}
