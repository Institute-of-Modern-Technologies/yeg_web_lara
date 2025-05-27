<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First ensure user types exist
        if (!UserType::count()) {
            UserType::insert([
                ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Has complete system access', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'School Admin', 'slug' => 'school_admin', 'description' => 'Manages school-specific data', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Student', 'slug' => 'student', 'description' => 'Regular student user', 'created_at' => now(), 'updated_at' => now()]
            ]);
        }
        
        // Create a Super Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'user_type_id' => 1
        ]);
        
        // Create a School Admin
        User::create([
            'name' => 'School Admin',
            'email' => 'school@example.com',
            'username' => 'school',
            'password' => Hash::make('password'),
            'user_type_id' => 2
        ]);
        
        // Create a Student
        User::create([
            'name' => 'Student',
            'email' => 'student@example.com',
            'username' => 'student',
            'password' => Hash::make('password'),
            'user_type_id' => 3
        ]);
    }
}
