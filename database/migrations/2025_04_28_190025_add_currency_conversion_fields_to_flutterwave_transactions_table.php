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
        Schema::table('flutterwave_transactions', function (Blueprint $table) {
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('display_currency');
            $table->decimal('display_amount', 10, 2)->nullable()->after('exchange_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flutterwave_transactions', function (Blueprint $table) {
            $table->dropColumn(['exchange_rate', 'display_amount']);
        });
    }
};
