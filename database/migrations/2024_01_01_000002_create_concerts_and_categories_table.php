<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bảng Buổi hòa nhạc
        Schema::create('concerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('venue');
            $table->string('city');
            $table->timestamp('event_at');
            $table->string('poster_url', 500)->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->foreignUuid('created_by')->constrained('operators');
            $table->timestamps();

            $table->index(['status', 'event_at']);
        });

        // Bảng Hạng vé
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('concert_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('total_quantity');
            $table->integer('available_quantity');
            $table->integer('max_per_order')->default(4);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['concert_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
        Schema::dropIfExists('concerts');
    }
};
