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
        Schema::table('levels', function (Blueprint $table) {
            $table->unsignedBigInteger('stage_id')->nullable()->after('id');
            $table->foreign('stage_id')
                  ->references('id')
                  ->on('stages')
                  ->onDelete('set null');
            $table->integer('level_number')->default(1)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
            $table->dropColumn(['stage_id', 'level_number']);
        });
    }
};
