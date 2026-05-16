<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password_hash',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_hash' => 'hashed',
        ];
    }

    // Ghi đè phương thức mật khẩu vì bạn dùng tên cột là password_hash thay vì password
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
