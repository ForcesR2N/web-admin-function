<?php
// database/migrations/xxxx_create_bookings_table.php
// PERBAIKAN: Gunakan schema yang sesuai dengan backend

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('user_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('date');
            $table->boolean('is_confirmed')->default(false); 
            $table->timestamps();

            // Indexes
            $table->index(['place_id', 'date']);
            $table->index(['user_id', 'date']);
            $table->index('is_confirmed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
