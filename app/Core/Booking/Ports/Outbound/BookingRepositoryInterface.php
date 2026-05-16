<?php

namespace App\Core\Booking\Ports\Outbound;

interface BookingRepositoryInterface
{
    public function save(array $data);
    public function findByCode(string $code);
    public function existsByIdempotencyKey(string $key);
}
