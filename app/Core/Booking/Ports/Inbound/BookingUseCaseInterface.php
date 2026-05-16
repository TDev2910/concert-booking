<?php

namespace App\Core\Booking\Ports\Inbound;

use App\Core\Booking\Domain\DTOs\BookingData;

interface BookingUseCaseInterface
{
    /**
     * Xử lý đặt vé mới
     */
    public function reserveTickets(BookingData $data);

    /**
     * Kiểm tra trạng thái đơn hàng
     */
    public function getBookingStatus(string $orderCode);
}
