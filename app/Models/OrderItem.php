<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderItem extends Model
{
    use HasFactory, HasUuids;

    // Tắt updated_at vì OrderItem thường không cập nhật sau khi tạo
    public const UPDATED_AT = null;

    protected $fillable = [
        'order_id', 'ticket_category_id', 'quantity', 
        'unit_price', 'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
