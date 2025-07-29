<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetStudentPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:reset-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all student user passwords to the default password (student123)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting student password reset process...');

        // Get the student user type ID
        $studentUserType = UserType::where('name', 'student')->first();

        if (!$studentUserType) {
            $this->error('Student user type not found!');
            return 1;
        }

        // Find all users with student user type
        $studentUsers = User::where('user_type_id', $studentUserType->id)->get();
        $count = $studentUsers->count();
        
        if ($count === 0) {
            $this->info('No student users found to update.');
            return 0;
        }

        $this->info("Found {$count} student users. Resetting passwords...");
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        foreach ($studentUsers as $user) {
            // Update password to student123
            $user->password = Hash::make('student123');
            $user->save();
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Password reset complete! All student users now have the default password: student123');
        
        return 0;
    }
}
