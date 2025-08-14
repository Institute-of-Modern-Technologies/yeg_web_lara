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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'created_by_school_id')) {
                $table->unsignedBigInteger('created_by_school_id')->nullable()->after('id');
                $table->foreign('created_by_school_id')->references('id')->on('schools')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('students', 'is_school_managed')) {
                $table->boolean('is_school_managed')->default(false)->after('created_by_school_id');
            }
            
            if (!Schema::hasColumn('students', 'admin_can_manage')) {
                $table->boolean('admin_can_manage')->default(false)->after('is_school_managed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'created_by_school_id')) {
                $table->dropForeign(['created_by_school_id']);
                $table->dropColumn('created_by_school_id');
            }
            
            if (Schema::hasColumn('students', 'is_school_managed')) {
                $table->dropColumn('is_school_managed');
            }
            
            if (Schema::hasColumn('students', 'admin_can_manage')) {
                $table->dropColumn('admin_can_manage');
            }
        });
    }
};
