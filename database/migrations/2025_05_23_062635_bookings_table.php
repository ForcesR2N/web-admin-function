<?php
// database/migrations/xxxx_create_bookings_table.php
// Enhanced booking table dengan mobile data

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();

            // Core booking data (sesuai backend FastAPI)
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('user_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('date');
            $table->boolean('is_confirmed')->default(false);

            // Additional mobile app data (optional)
            $table->json('mobile_data')->nullable(); // Store additional mobile app data
            $table->string('source')->default('mobile'); // mobile, web, api
            $table->string('status')->default('pending'); // pending, confirmed, cancelled

            $table->timestamps();

            // Indexes for performance
            $table->index(['place_id', 'date']);
            $table->index(['user_id', 'date']);
            $table->index('is_confirmed');
            $table->index('status');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
