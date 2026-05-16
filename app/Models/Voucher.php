<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\VoucherUsage;

class Voucher extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_value',
        'total_uses',
        'used_count',
        'max_uses_per_user',
        'starts_at',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }
}
