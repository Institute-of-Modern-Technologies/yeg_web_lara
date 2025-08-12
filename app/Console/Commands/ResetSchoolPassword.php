<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetSchoolPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password for existing school account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting school account password...');
        
        $user = User::where('username', 'school')->first();
        
        if ($user) {
            $user->password = Hash::make('password123');
            $user->save();
            
            $this->info('Password reset successfully!');
            $this->line('');
            $this->line('Login Credentials:');
            $this->line('Username: school');
            $this->line('Password: password123');
            $this->line('');
            $this->line('Navigate to: /school/dashboard');
        } else {
            $this->error('School user not found!');
        }
    }
}
