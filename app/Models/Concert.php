<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Concert extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title', 'slug', 'description', 'venue', 'city', 
        'event_at', 'poster_url', 'status', 'created_by'
    ];

    protected $casts = [
        'event_at' => 'datetime',
    ];

    // Quan hệ: 1 Concert có nhiều TicketCategories (Hạng vé)
    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }

    // Quan hệ: 1 Concert được tạo bởi 1 Operator
    public function creator()
    {
        return $this->belongsTo(Operator::class, 'created_by');
    }
}
