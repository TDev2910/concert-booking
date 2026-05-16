<?php

namespace Database\Seeders;

use App\Models\Concert;
use App\Models\TicketCategory;
use App\Models\Operator;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ConcertSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Operator::first();

        $concert = Concert::create([
            'title' => 'The Eras Tour - Ho Chi Minh City',
            'slug' => 'the-eras-tour-hcmc-2025',
            'description' => 'Trải nghiệm đêm nhạc hoành tráng nhất năm với sự góp mặt của các siêu sao quốc tế.',
            'venue' => 'Sân vận động Quân khu 7',
            'city' => 'Hồ Chí Minh',
            'event_at' => Carbon::now()->addMonths(3),
            'poster_url' => 'https://example.com/poster.jpg',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        // Tạo các hạng vé cho Concert này
        TicketCategory::create([
            'concert_id' => $concert->id,
            'name' => 'VVIP - Front Row',
            'price' => 5000000,
            'total_quantity' => 50,
            'available_quantity' => 50,
            'max_per_order' => 2,
        ]);

        TicketCategory::create([
            'concert_id' => $concert->id,
            'name' => 'VIP - Seated',
            'price' => 3000000,
            'total_quantity' => 200,
            'available_quantity' => 200,
            'max_per_order' => 4,
        ]);

        TicketCategory::create([
            'concert_id' => $concert->id,
            'name' => 'Standard - Standing',
            'price' => 1500000,
            'total_quantity' => 1000,
            'available_quantity' => 1000,
            'max_per_order' => 4,
        ]);
    }
}
