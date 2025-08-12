<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestSchoolAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:create-test-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test school account for portal testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating test school account...');

        // Check if test school already exists
        $existingSchool = School::where('email', 'test@school.com')->first();
        if ($existingSchool) {
            $this->info('Test school already exists:');
            $this->line('Email: test@school.com');
            $this->line('Password: password123');
            return;
        }

        // Create test school
        $school = School::create([
            'name' => 'Test School',
            'email' => 'test@school.com',
            'phone' => '+233123456789',
            'location' => 'Accra, Ghana',
            'owner_name' => 'Test School Owner',
            'avg_students' => 50,
            'status' => 'approved',
            'allow_admin_management' => false,
        ]);

        // Create user account for the school
        $user = User::create([
            'name' => 'Test School Admin',
            'username' => 'testschool',
            'email' => 'test@school.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $this->info('Test school account created successfully!');
        $this->line('');
        $this->line('Login Credentials:');
        $this->line('Email: test@school.com');
        $this->line('Password: password123');
        $this->line('');
        $this->line('Access the school portal at: /school/dashboard');
    }
}
