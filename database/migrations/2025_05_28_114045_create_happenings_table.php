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
        Schema::create('happenings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('author_name')->nullable();
            $table->date('published_date');
            $table->string('category')->nullable();
            $table->string('media_type')->default('image'); // 'image' or 'video'
            $table->string('media_path');
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
        Schema::dropIfExists('happenings');
    }
};
