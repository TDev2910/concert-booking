<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

// Kế thừa từ Authenticatable để có thể dùng làm Guard đăng nhập riêng cho Admin
class Operator extends Authenticatable
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 'email', 'password_hash', 'role', 'is_active'
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
