<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\School;

class UpdateSchoolStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-school-students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update school students to properly link to their schools';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating students with proper school associations...');
        
        // Get all students that have a school_id but no created_by_school_id
        $students = Student::whereNotNull('school_id')
            ->whereNull('created_by_school_id')
            ->get();
        
        $count = 0;
        
        foreach ($students as $student) {
            // Set created_by_school_id to the school_id
            $student->created_by_school_id = $student->school_id;
            $student->is_school_managed = true; // Mark as managed by school
            $student->save();
            $count++;
        }
        
        $this->info("Updated $count students with proper school associations.");
        
        return 0;
    }
}
