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
        Schema::create('event_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('place_id')->nullable();
            $table->string('osm_id')->nullable();
            $table->string('osm_type')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('display_name');
            $table->string('display_place');
            $table->string('display_address');
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('type')->nullable();
            $table->string('class')->nullable();
            $table->json('bounds')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('place_id');
            $table->index('osm_id');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_locations');
    }
};
