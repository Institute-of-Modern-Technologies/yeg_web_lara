<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CreateUserAccountsForExistingRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yeg:create-user-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user accounts for existing students and schools in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to create user accounts for existing records...');

        // Get or create user types
        $studentUserType = UserType::firstOrCreate(
            ['slug' => 'student'],
            ['name' => 'Student', 'description' => 'Regular student account']
        );
        
        $schoolAdminType = UserType::firstOrCreate(
            ['slug' => 'school_admin'],
            ['name' => 'School Admin', 'description' => 'School administrator account']
        );
        
        // Process students
        $this->processStudents($studentUserType);
        
        // Process approved schools
        $this->processSchools($schoolAdminType);
        
        $this->info('User account creation completed!');
    }
    
    /**
     * Process students to create their user accounts
     *
     * @param \App\Models\UserType $userType
     * @return void
     */
    private function processStudents($userType)
    {
        $this->info('Processing students...');
        
        // Get all students regardless of status
        $students = Student::select('id', 'first_name', 'last_name', 'email', 'status')->get();
        
        $this->info('Found ' . count($students) . ' total students in the database');
        
        $count = 0;
        
        foreach ($students as $student) {
            // Check if user already exists with this email or by checking for existing username pattern
            $baseUsername = strtolower($student->first_name);
            $potentialUsernames = [];
            $potentialUsernames[] = $baseUsername;
            
            // Add some variations for checking
            for ($i = 1; $i <= 5; $i++) {
                $potentialUsernames[] = $baseUsername . $i;
            }
            
            $existingUser = null;
            
            // Check by email first
            if ($student->email) {
                $existingUser = User::where('email', $student->email)->first();
                if ($existingUser) {
                    $this->line("User already exists for student {$student->id} with email: {$student->email}");
                    $this->line("  Username: {$existingUser->username}");
                    continue;
                }
            }
            
            // Then check by potential usernames
            foreach ($potentialUsernames as $potentialUsername) {
                $existingUser = User::where('username', $potentialUsername)->first();
                if ($existingUser) {
                    $this->line("User likely exists for student {$student->id} with username: {$existingUser->username}");
                    continue 2; // Skip to the next student
                }
            }
            
            try {
                // Create username from first name
                $baseUsername = strtolower($student->first_name);
                $username = $baseUsername;
                $counter = 1;
                
                // Check if username exists and generate a unique one if needed
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter++;
                }
                
                // Create the user
                User::create([
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'username' => $username,
                    'password' => Hash::make('student123'),
                    'user_type_id' => $userType->id
                ]);
                
                $count++;
                $this->line("Created user for student {$student->id}: {$username}");
            } catch (\Exception $e) {
                $this->error("Failed to create user for student {$student->id}: {$e->getMessage()}");
                Log::error("Failed to create user for student {$student->id}: {$e->getMessage()}");
            }
        }
        
        $this->info("Created $count user accounts for students");
    }
    
    /**
     * Process schools to create their user accounts
     *
     * @param \App\Models\UserType $userType
     * @return void
     */
    private function processSchools($userType)
    {
        $this->info('Processing schools...');
        
        // Get all schools regardless of status
        $schools = School::select('id', 'name', 'email', 'status')->get();
        
        $this->info('Found ' . count($schools) . ' total schools in the database');
        
        $count = 0;
        
        foreach ($schools as $school) {
            // Check if user already exists with this email or by checking for existing username pattern
            $baseUsername = Str::slug($school->name);
            $potentialUsernames = [];
            $potentialUsernames[] = $baseUsername;
            
            // Add some variations for checking
            for ($i = 1; $i <= 5; $i++) {
                $potentialUsernames[] = $baseUsername . $i;
            }
            
            $existingUser = null;
            
            // Check by email first
            if ($school->email) {
                $existingUser = User::where('email', $school->email)->first();
                if ($existingUser) {
                    $this->line("User already exists for school {$school->id} with email: {$school->email}");
                    $this->line("  Username: {$existingUser->username}");
                    continue;
                }
            }
            
            // Then check by potential usernames
            foreach ($potentialUsernames as $potentialUsername) {
                $existingUser = User::where('username', $potentialUsername)->first();
                if ($existingUser) {
                    $this->line("User likely exists for school {$school->id} with username: {$existingUser->username}");
                    continue 2; // Skip to the next school
                }
            }
            
            try {
                // Create username from school name
                $baseUsername = Str::slug($school->name);
                $username = $baseUsername;
                $counter = 1;
                
                // Check if username exists and generate a unique one if needed
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter++;
                }
                
                // Create the user - generate a placeholder email if none exists
                $email = $school->email;
                if (!$email) {
                    // Generate a placeholder email using the username
                    $email = $username . '@placeholder.yeg.edu';
                }
                
                User::create([
                    'name' => $school->name,
                    'email' => $email,
                    'username' => $username,
                    'password' => Hash::make('school123'),
                    'user_type_id' => $userType->id
                ]);
                
                $count++;
                $this->line("Created user for school {$school->id}: {$username}");
            } catch (\Exception $e) {
                $this->error("Failed to create user for school {$school->id}: {$e->getMessage()}");
                Log::error("Failed to create user for school {$school->id}: {$e->getMessage()}");
            }
        }
        
        $this->info("Created $count user accounts for schools");
    }
}
