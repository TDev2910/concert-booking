<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bảng Lịch sử dùng Voucher
        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('voucher_id')->constrained();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('order_id')->constrained()->unique();
            $table->timestamp('used_at')->useCurrent();
            
            $table->unique(['user_id', 'voucher_id']);
        });

        // Bảng Vé điện tử
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('ticket_category_id')->constrained();
            $table->foreignUuid('user_id')->constrained();
            $table->string('ticket_code', 50)->unique();
            $table->string('qr_code_url', 500)->nullable();
            $table->enum('status', ['valid', 'used', 'cancelled'])->default('valid');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->index('ticket_code');
        });

        // Bảng Logs hệ thống
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained();
            $table->foreignUuid('operator_id')->nullable()->constrained();
            $table->string('action');
            $table->json('payload')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_logs');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('voucher_usages');
    }
};
