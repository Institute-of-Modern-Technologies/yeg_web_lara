<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // First check if the column exists
            if (!Schema::hasColumn('students', 'parent_contact')) {
                $table->string('parent_contact')->nullable()->after('phone');
            } else {
                // If column exists but is causing issues, try to modify it
                try {
                    // Drop and recreate the column if it exists but is problematic
                    DB::statement('ALTER TABLE students MODIFY parent_contact VARCHAR(255) NULL');
                } catch (\Exception $e) {
                    // Log the error but don't fail the migration
                    \Log::error('Failed to modify parent_contact column: ' . $e->getMessage());
                }
            }
        });

        // Double check the column exists, report status
        if (Schema::hasColumn('students', 'parent_contact')) {
            \Log::info('parent_contact column exists in students table.');
        } else {
            \Log::error('parent_contact column still does not exist in students table after migration!');
        }
    }

    /**
     * Reverse the migrations.
     * Note: We don't drop the column since it's essential for functionality
     */
    public function down(): void
    {
        // Intentionally left empty - we don't want to remove the column
    }
};
