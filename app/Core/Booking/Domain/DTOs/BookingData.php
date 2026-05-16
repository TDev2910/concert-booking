<?php

namespace App\Core\Booking\Domain\DTOs;

readonly class BookingData
{
    public function __construct(
        public string $userId,
        public string $concertId,
        public array $items,
        public ?string $voucherCode = null,
        public ?string $idempotencyKey = null
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
