<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\User;
use App\Models\UserType;
use App\Notifications\StudentApprovalNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateUserAccountsForStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:generate-users {--all : Generate for all students regardless of existing accounts} {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate user accounts for all students in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get or create student user type
        $studentUserType = UserType::firstOrCreate(
            ['slug' => 'student'],
            ['name' => 'Student', 'description' => 'Regular student account']
        );

        // Count of students without accounts
        $allStudents = Student::all();
        $studentsWithoutAccounts = collect();
        $studentsWithMissingEmail = collect();
        
        foreach ($allStudents as $student) {
            if (empty($student->email)) {
                $studentsWithMissingEmail->push($student);
                continue;
            }

            $existingUser = User::where('email', $student->email)->first();
            if (!$existingUser || $this->option('all')) {
                $studentsWithoutAccounts->push($student);
            }
        }
        
        $this->info("Found {$studentsWithoutAccounts->count()} students without user accounts");
        $this->info("Found {$studentsWithMissingEmail->count()} students without email addresses");
        
        if ($studentsWithMissingEmail->count() > 0) {
            $this->info("{$studentsWithMissingEmail->count()} students found without email addresses");
            if ($this->confirm('Do you want to generate dummy emails for these students?', true)) {
                $this->output->progressStart($studentsWithMissingEmail->count());
                
                foreach ($studentsWithMissingEmail as $student) {
                    $this->output->progressAdvance();
                    
                    // Generate a dummy email using school name and student info
                    $schoolName = "school";
                    if ($student->school_id) {
                        $school = \App\Models\School::find($student->school_id);
                        if ($school) {
                            $schoolName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $school->name));
                        }
                    }
                    
                    // Create dummy email
                    $namePart = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $student->first_name . $student->last_name));
                    if (empty($namePart)) {
                        $namePart = 'student' . $student->id;
                    }
                    $dummyEmail = $namePart . '.' . $student->id . '@' . $schoolName . '.example';
                    
                    // Update the student with the dummy email
                    $student->email = $dummyEmail;
                    $student->save();
                    
                    // Add to students without accounts so a user will be created
                    $studentsWithoutAccounts->push($student);
                    
                    Log::info("Generated dummy email {$dummyEmail} for student ID: {$student->id}");
                }
                
                $this->output->progressFinish();
                $this->info("Generated dummy emails for {$studentsWithMissingEmail->count()} students");
            } else {
                $this->warn("Students without email addresses will not have user accounts created.");
                if ($this->confirm('Do you want to see the list of students without email addresses?')) {
                    $headers = ['ID', 'Name', 'Registration Number'];
                    $rows = [];
                    
                    foreach ($studentsWithMissingEmail as $student) {
                        $rows[] = [$student->id, $student->full_name, $student->registration_number];
                    }
                    
                    $this->table($headers, $rows);
                }
            }
        }
        
        if ($studentsWithoutAccounts->count() === 0) {
            $this->info('All students with email addresses already have user accounts!');
            return 0;
        }
        
        if (!$this->option('force') && !$this->confirm("Do you wish to generate user accounts for these {$studentsWithoutAccounts->count()} students?")) {
            $this->info('Operation cancelled.');
            return 0;
        }
        
        $successCount = 0;
        $failCount = 0;
        
        $this->output->progressStart($studentsWithoutAccounts->count());
        
        foreach ($studentsWithoutAccounts as $student) {
            $this->output->progressAdvance();
            
            try {
                // Create username from first name or full name
                $nameParts = explode(' ', $student->full_name);
                $firstName = $nameParts[0] ?? '';
                $baseUsername = strtolower($firstName ?: $student->full_name);
                $username = preg_replace('/[^a-z0-9]/', '', $baseUsername); // Remove special characters
                
                if (empty($username)) {
                    $username = 'student' . $student->id;
                }
                
                $counter = 1;
                $originalUsername = $username;
                
                // Check if username exists
                while (User::where('username', $username)->exists()) {
                    $username = $originalUsername . $counter++;
                }
                
                // Generate a random password
                $password = Str::random(8);
                
                // Create user account
                $user = User::updateOrCreate(
                    ['email' => $student->email],
                    [
                        'name' => $student->full_name,
                        'username' => $username,
                        'password' => Hash::make($password),
                        'user_type_id' => $studentUserType->id
                    ]
                );
                
                // Send notification with login credentials
                try {
                    $student->notify(new StudentApprovalNotification($student, $username, $password));
                    $this->line(" Sent notification to {$student->email}");
                } catch (\Exception $e) {
                    $this->warn(" Failed to send notification to {$student->email}: {$e->getMessage()}");
                    Log::warning("Failed to send login notification to student {$student->id}: {$e->getMessage()}");
                }
                
                Log::info("User account created for student: {$student->id}");
                $successCount++;
                
            } catch (\Exception $e) {
                Log::error("Failed to create user for student {$student->id}: {$e->getMessage()}");
                $failCount++;
            }
        }
        
        $this->output->progressFinish();
        
        $this->newLine();
        $this->info("Process completed: {$successCount} user accounts created, {$failCount} failures");
        
        return 0;
    }
}
