<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Core\Booking\Ports\Outbound\BookingRepositoryInterface;
use App\Models\Booking;

class EloquentBookingRepository implements BookingRepositoryInterface
{
    /**
     * Lưu thông tin đặt vé
     */
    public function save(array $data)
    {
        return Booking::create($data);
    }

    /**
     * Tìm đơn hàng theo mã code
     */
    public function findByCode(string $code)
    {
        return Booking::where('order_code', $code)->first();
    }

    /**
     * Kiểm tra xem Idempotency Key đã tồn tại chưa (chống đặt trùng)
     */
    public function existsByIdempotencyKey(string $key)
    {
        return Booking::where('idempotency_key', $key)->exists();
    }
}
