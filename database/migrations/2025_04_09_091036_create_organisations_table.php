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
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("phone_number");
            $table->text("description")->nullable();
            $table->string("website")->nullable();
            $table->string("logo_url")->nullable();
            $table->string("country");
            $table->string("currency", 10)->default("USD");
            $table->json("socials")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
