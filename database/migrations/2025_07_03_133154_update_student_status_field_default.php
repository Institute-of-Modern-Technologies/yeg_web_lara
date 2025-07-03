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
        // Modify the enum to include 'pending' and make it the default value
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'completed', 'pending') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change the default back to 'active'
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('active', 'inactive', 'completed', 'pending') NOT NULL DEFAULT 'active'");
    }
};
