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
            if (!Schema::hasColumn('hero_sections', 'text_position')) {
                $table->string('text_position')->default('bottom');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            if (Schema::hasColumn('hero_sections', 'text_position')) {
                $table->dropColumn('text_position');
            }
        });
    }
};
