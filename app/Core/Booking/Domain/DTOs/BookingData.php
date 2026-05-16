<?php

namespace App\Core\Booking\Domain\DTOs;

class BookingData
{
    public function __construct(
        public readonly string $userId,
        public readonly string $concertId,
        public readonly array $items, // array of ['category_id' => ..., 'quantity' => ...]
        public readonly ?string $voucherCode = null,
        public readonly ?string $idempotencyKey = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            concertId: $data['concert_id'],
            items: $data['items'],
            voucherCode: $data['voucher_code'] ?? null,
            idempotencyKey: $data['idempotency_key'] ?? null
        );
    }
}
