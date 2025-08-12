<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Hash;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list-and-create-school';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List existing users and create a school account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Existing users:');
        $users = User::select('username', 'email', 'name')->get();
        
        foreach ($users as $user) {
            $this->line($user->username . ' - ' . $user->email . ' - ' . $user->name);
        }
        
        $this->line('');
        $this->info('Creating/updating school account...');
        
        // Check if school user exists
        $schoolUser = User::where('username', 'schooltest')->first();
        
        if ($schoolUser) {
            $this->info('School user already exists:');
            $this->line('Username: schooltest');
            $this->line('Password: password123');
        } else {
            // Create school user
            $schoolUser = User::create([
                'name' => 'School Test User',
                'username' => 'schooltest',
                'email' => 'schooltest@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);
            
            $this->info('School user created successfully!');
            $this->line('Username: schooltest');
            $this->line('Password: password123');
        }
        
        // Check/create corresponding school record
        $school = School::where('email', 'schooltest@example.com')->first();
        
        if (!$school) {
            $school = School::create([
                'name' => 'Test School Portal',
                'email' => 'schooltest@example.com',
                'phone' => '+233123456789',
                'location' => 'Accra, Ghana',
                'owner_name' => 'School Test Owner',
                'avg_students' => 25,
                'status' => 'approved',
                'allow_admin_management' => false,
            ]);
            
            $this->info('School record created successfully!');
        } else {
            $this->info('School record already exists.');
        }
        
        $this->line('');
        $this->info('You can now login with:');
        $this->line('Username: schooltest');
        $this->line('Password: password123');
        $this->line('Then navigate to: /school/dashboard');
    }
}
