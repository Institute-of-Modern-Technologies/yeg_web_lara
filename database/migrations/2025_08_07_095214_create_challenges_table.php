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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenger_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('opponent_id')->nullable()->constrained('students')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('challenge_categories');
            $table->enum('status', ['pending', 'active', 'completed', 'expired'])->default('pending');
            $table->boolean('is_random_opponent')->default(false);
            $table->timestamp('expires_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
