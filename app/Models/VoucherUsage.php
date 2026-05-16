<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class VoucherUsage extends Model
{
    use HasFactory, HasUuids;

    public const UPDATED_AT = null; // Bảng lịch sử thường không cập nhật

    protected $fillable = [
        'voucher_id', 'user_id', 'order_id', 'used_at'
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
