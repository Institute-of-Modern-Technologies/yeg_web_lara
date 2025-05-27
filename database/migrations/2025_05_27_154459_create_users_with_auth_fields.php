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
        // Create user_types table
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default user types
        DB::table('user_types')->insert([
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Has complete system access', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'School Admin', 'slug' => 'school_admin', 'description' => 'Manages school-specific data', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Student', 'slug' => 'student', 'description' => 'Regular student user', 'created_at' => now(), 'updated_at' => now()]
        ]);
        
        // Modify existing users table to add username and user_type_id
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('email');
            $table->foreignId('user_type_id')->after('username')->constrained('user_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropColumn(['username', 'user_type_id']);
        });
        Schema::dropIfExists('user_types');
    }
};
