<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_item_id', 'ticket_category_id', 'user_id', 
        'ticket_code', 'qr_code_url', 'status', 'checked_in_at'
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
