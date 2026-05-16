<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BookingLog extends Model
{
    use HasFactory, HasUuids;

    public const UPDATED_AT = null; 

    protected $fillable = [
        'order_id', 'operator_id', 'action', 'payload', 'ip_address'
    ];

    protected $casts = [
        'payload' => 'array', 
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
