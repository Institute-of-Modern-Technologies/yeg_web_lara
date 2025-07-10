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
            // Check and add all fields that might be missing
            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender')->nullable();
            }
            if (!Schema::hasColumn('students', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('students', 'city')) {
                $table->string('city')->nullable()->change();
            }
            if (!Schema::hasColumn('students', 'region')) {
                $table->string('region')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_contact')) {
                $table->string('parent_contact')->nullable()->change();
            }
            if (!Schema::hasColumn('students', 'class')) {
                $table->string('class')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to drop these columns, as they're now part of the standard schema
    }
};
