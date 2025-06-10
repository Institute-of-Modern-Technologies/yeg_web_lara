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
        Schema::create('school_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('School name');
            $table->string('logo_path')->comment('Path to the logo image');
            $table->integer('display_order')->default(0)->comment('Order for display in the marquee');
            $table->boolean('is_active')->default(true)->comment('Whether to show the logo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_logos');
    }
};
