<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TicketCategory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'concert_id', 'name', 'description', 'price', 
        'total_quantity', 'available_quantity', 'max_per_order', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
}
