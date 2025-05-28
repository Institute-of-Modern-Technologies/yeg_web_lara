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
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('media_type')->default('image'); // 'image' or 'video'
            $table->string('media_path');
            $table->string('duration')->nullable();
            $table->string('level')->nullable(); // 'All Levels', 'Beginner', 'Intermediate', 'Advanced'
            $table->string('level_color')->default('#ff00ff'); // Color for the level badge
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
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
