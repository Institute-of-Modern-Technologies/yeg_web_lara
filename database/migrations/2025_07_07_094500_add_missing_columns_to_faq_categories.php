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
        Schema::table('faq_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('faq_categories', 'name')) {
                $table->string('name')->after('id');
            }
            
            if (!Schema::hasColumn('faq_categories', 'slug')) {
                $table->string('slug')->after('name');
            }
            
            if (!Schema::hasColumn('faq_categories', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            
            if (!Schema::hasColumn('faq_categories', 'is_active')) {
                $table->boolean('is_active')->default(1)->after('description');
            }
            
            if (!Schema::hasColumn('faq_categories', 'display_order')) {
                $table->integer('display_order')->default(0)->after('is_active');
            }
            
            if (!Schema::hasColumn('faq_categories', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->dropColumn(['name', 'slug', 'description', 'is_active', 'display_order', 'deleted_at']);
        });
    }
};
