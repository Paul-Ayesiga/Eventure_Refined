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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // Type of event: Venue Event, Online Event, Undecided
            $table->string('event_type');
            // Event name/title
            $table->string('name');
            // Venue details (nullable if online)
            $table->string('venue')->nullable();
            // Repeat option (for example "Does not repeat", "Daily", etc.)
            $table->string('event_repeat')->default('Does not repeat');
            // Store start and end datetimes together for clarity
            $table->string('start_date');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            // Timezone in which the event takes place
            $table->string('timezone');
            // Currency code for any pricing (e.g. AUD)
            $table->string('currency', 10);
            // Status: Draft or Published
            $table->enum('status', ['Draft', 'Published'])->default('Draft');
            // Event category, if any
            $table->string('category')->nullable();
            // Flag to decide if we convert to customer timezone or not
            $table->boolean('auto_convert_timezone')->default(true);

            $table->json('banners')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();

            $table->timestamps();

            $table->foreignId('organisation_id')->references('id')->on('organisations')->onDelete('cascade');

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
