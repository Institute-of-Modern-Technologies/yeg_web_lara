<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\School;
use App\Models\User;

class CreateSchoolRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:create-record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create school record for existing school user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating school record for existing user...');
        
        $user = User::where('username', 'school')->first();
        
        if (!$user) {
            $this->error('School user not found!');
            return;
        }
        
        $existingSchool = School::where('email', $user->email)->first();
        
        if ($existingSchool) {
            $this->info('School record already exists!');
            $this->line('School: ' . $existingSchool->name);
            $this->line('Email: ' . $existingSchool->email);
            return;
        }
        
        // Create school record
        $school = School::create([
            'name' => 'Test School Portal',
            'email' => $user->email,
            'phone' => '+233123456789',
            'location' => 'Accra, Ghana',
            'owner_name' => 'School Admin',
            'avg_students' => 50,
            'status' => 'approved',
            'allow_admin_management' => false,
        ]);
        
        $this->info('School record created successfully!');
        $this->line('School: ' . $school->name);
        $this->line('Email: ' . $school->email);
        $this->line('');
        $this->line('You can now access the school portal at: /school/dashboard');
    }
}
