<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eventsbackup', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('acara');
            $table->unsignedBigInteger('id_rooms');
            $table->string('nama_rooms');
            $table->enum('asalbidang', ['Rendal', 'LITBANG', 'Ekonomi', 'PPM', 'Sekretariat']);
            $table->date('date');
            $table->time('start');
            $table->time('finish');
            $table->timestamps();        
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
