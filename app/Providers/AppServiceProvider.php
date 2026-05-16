<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Module Booking
        $this->app->bind(
            \App\Core\Booking\Ports\Inbound\BookingUseCaseInterface::class,
            \App\Core\Booking\Application\Services\BookingService::class
        );
        $this->app->bind(
            \App\Core\Booking\Ports\Outbound\BookingRepositoryInterface::class,
            \App\Infrastructure\Persistence\Eloquent\EloquentBookingRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
