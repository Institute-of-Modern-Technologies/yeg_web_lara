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
        Schema::create('challenge_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('winner_id')->nullable()->constrained('students')->onDelete('set null');
            $table->integer('challenger_score')->default(0);
            $table->integer('opponent_score')->default(0);
            $table->integer('challenger_time')->nullable()->comment('Total time taken in seconds');
            $table->integer('opponent_time')->nullable()->comment('Total time taken in seconds');
            $table->enum('result', ['challenger_won', 'opponent_won', 'draw', 'incomplete'])->default('incomplete');
            $table->timestamps();
            
            // Each challenge can have only one result
            $table->unique(['challenge_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_results');
    }
};
