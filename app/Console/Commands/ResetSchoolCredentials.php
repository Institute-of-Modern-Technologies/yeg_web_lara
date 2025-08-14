<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserType;
use App\Models\School;
use Illuminate\Support\Facades\Hash;

class ResetSchoolCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:reset-credentials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset school admin credentials for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking and setting up school admin credentials...');

        // Get or create school_admin user type
        $schoolAdminType = UserType::where('slug', 'school_admin')->first();
        if (!$schoolAdminType) {
            $schoolAdminType = UserType::create([
                'name' => 'School Admin',
                'slug' => 'school_admin',
                'description' => 'School Administrator'
            ]);
            $this->info('Created school_admin user type');
        }

        // Check if school user exists
        $user = User::where('username', 'school')->first();
        
        if (!$user) {
            // Create school user
            $user = User::create([
                'name' => 'Test School Admin',
                'username' => 'school',
                'email' => 'school@test.com',
                'password' => Hash::make('password123'),
                'user_type_id' => $schoolAdminType->id,
            ]);
            $this->info('Created school user');
        } else {
            // Update existing user
            $user->password = Hash::make('password123');
            $user->user_type_id = $schoolAdminType->id;
            $user->save();
            $this->info('Updated existing school user');
        }

        // Create or update associated school record
        $school = School::where('email', $user->email)->first();
        if (!$school) {
            $school = School::create([
                'name' => 'Test School',
                'email' => $user->email,
                'phone' => '123-456-7890',
                'location' => 'Test City',
                'owner_name' => $user->name,
                'avg_students' => 100,
                'status' => 'approved'
            ]);
            $this->info('Created school record');
        } else {
            $this->info('School record already exists');
        }

        $this->info('School admin credentials ready:');
        $this->line('Username: school');
        $this->line('Password: password123');
        $this->line('User Type: ' . $schoolAdminType->name);
        $this->line('School: ' . $school->name);
        $this->line('School Email: ' . $school->email);
        
        return 0;
    }
}
