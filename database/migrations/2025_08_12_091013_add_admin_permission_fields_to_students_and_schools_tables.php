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
        // Add fields to schools table for admin permission management
        Schema::table('schools', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('schools', 'allow_admin_management')) {
                $table->boolean('allow_admin_management')->default(false)->after('status');
            }
            
            if (!Schema::hasColumn('schools', 'admin_permission_granted_at')) {
                $table->timestamp('admin_permission_granted_at')->nullable()->after('allow_admin_management');
            }
            
            if (!Schema::hasColumn('schools', 'admin_permission_granted_by')) {
                $table->string('admin_permission_granted_by')->nullable()->after('admin_permission_granted_at');
            }
        });

        // Add fields to students table for admin management permission
        Schema::table('students', function (Blueprint $table) {
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('students', 'admin_can_manage')) {
                $table->boolean('admin_can_manage')->default(false)->after('user_id');
            }
            
            // Add foreign key constraints if they don't exist
            if (Schema::hasColumn('students', 'created_by_school_id')) {
                // Check if foreign key already exists
                $foreignKeys = $this->getForeignKeys('students');
                if (!in_array('students_created_by_school_id_foreign', $foreignKeys)) {
                    try {
                        $table->foreign('created_by_school_id')->references('id')->on('schools')->onDelete('set null');
                    } catch (\Exception $e) {
                        // Foreign key might already exist with different name
                    }
                }
            }
            
            // Check for foreign key constraint before trying to add it
            if (Schema::hasColumn('students', 'user_id')) {
                // Check if the foreign key constraint already exists
                $foreignKeys = $this->getForeignKeys('students');
                if (!in_array('students_user_id_foreign', $foreignKeys)) {
                    try {
                        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                    } catch (\Exception $e) {
                        // Foreign key might already exist with different name
                    }
                }
            }
        });
    }

    /**
     * Helper method to get foreign keys for a table
     */
    protected function getForeignKeys(string $table): array
    {
        $conn = Schema::getConnection();
        $databaseName = $conn->getDatabaseName();
        
        $foreignKeys = [];
        
        try {
            $foreignKeyResults = DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' 
                AND TABLE_SCHEMA = ? 
                AND TABLE_NAME = ?",
                [$databaseName, $table]
            );
            
            foreach ($foreignKeyResults as $result) {
                $foreignKeys[] = $result->CONSTRAINT_NAME;
            }
        } catch (\Exception $e) {
            // Just return empty array if any error occurs
        }
        
        return $foreignKeys;
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove fields from students table
        Schema::table('students', function (Blueprint $table) {
            // Only drop if column exists
            if (Schema::hasColumn('students', 'admin_can_manage')) {
                $table->dropColumn('admin_can_manage');
            }
        });
        
        // Remove fields from schools table
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'allow_admin_management')) {
                $table->dropColumn('allow_admin_management');
            }
            if (Schema::hasColumn('schools', 'admin_permission_granted_at')) {
                $table->dropColumn('admin_permission_granted_at');
            }
            if (Schema::hasColumn('schools', 'admin_permission_granted_by')) {
                $table->dropColumn('admin_permission_granted_by');
            }
        });
    }
};
