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
        Schema::create('student_challenge_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('xp_points')->default(0);
            $table->integer('challenges_won')->default(0);
            $table->integer('challenges_lost')->default(0);
            $table->integer('challenges_drawn')->default(0);
            $table->integer('challenges_initiated')->default(0);
            $table->integer('challenges_received')->default(0);
            $table->integer('questions_answered')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('total_time_spent')->default(0)->comment('Total time spent answering questions in seconds');
            $table->timestamps();
            
            // Each student can have only one stats record
            $table->unique(['student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_challenge_stats');
    }
};
