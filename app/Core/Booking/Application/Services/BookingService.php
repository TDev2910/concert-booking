<?php

namespace App\Core\Booking\Application\Services;

use App\Core\Booking\Ports\Inbound\BookingUseCaseInterface;
use App\Core\Booking\Ports\Outbound\BookingRepositoryInterface;
use App\Core\Booking\Domain\DTOs\BookingData;

class BookingService implements BookingUseCaseInterface
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepository
    ) {}

    public function reserveTickets(BookingData $data)
    {
        // xử lý đặt vé
        // 1. Kiểm tra Idempotency
        // 2. Lock & Check Inventory
        // 3. Tính toán giá & Voucher
        // 4. Lưu Booking
        
        return [
            'status' => 'pending',
            'message' => 'Booking initialized'
        ];
    }

    public function getBookingStatus(string $orderCode)
    {
        return $this->bookingRepository->findByCode($orderCode);
    }
}
