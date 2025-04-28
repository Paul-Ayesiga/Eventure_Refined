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
        Schema::create('flutterwave_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3); // API currency (e.g., RWF)
            $table->string('display_currency', 3)->nullable(); // Display currency (e.g., UGX)
            $table->string('tx_ref')->unique(); // Transaction reference
            $table->string('flw_ref')->nullable(); // Flutterwave reference
            $table->string('status');
            $table->string('payment_type')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flutterwave_transactions');
    }
};
