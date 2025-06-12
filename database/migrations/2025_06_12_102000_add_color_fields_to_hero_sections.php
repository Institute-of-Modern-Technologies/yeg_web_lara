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
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->string('title_color')->nullable()->after('text_color');
            $table->string('subtitle_color')->nullable()->after('title_color');
            $table->string('brand_text')->nullable()->after('subtitle_color');
            $table->string('brand_text_color')->nullable()->after('brand_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn(['title_color', 'subtitle_color', 'brand_text', 'brand_text_color']);
        });
    }
};
