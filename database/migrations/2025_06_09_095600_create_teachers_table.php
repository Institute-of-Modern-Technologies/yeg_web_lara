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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('location');
            $table->string('surrounding_areas')->nullable();
            $table->text('educational_background');
            $table->text('relevant_experience');
            $table->json('expertise_areas'); // Stores array of selected skills
            $table->string('other_expertise')->nullable(); // Only if 'Other' is selected
            $table->string('program_applied'); // Which program they're applying for
            $table->json('preferred_locations'); // Stores array of selected locations
            $table->string('other_location')->nullable(); // Only if 'Other' is selected
            $table->boolean('experience_teaching_kids');
            $table->enum('cv_status', ['yes', 'no', 'will_send']);
            $table->text('why_instructor');
            $table->string('video_introduction')->nullable(); // Path to uploaded video
            $table->boolean('confirmation_agreement');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
