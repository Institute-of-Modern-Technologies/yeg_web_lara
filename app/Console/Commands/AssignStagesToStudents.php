<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Stage;

class AssignStagesToStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:assign-stages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the first active stage to all students who do not have a stage assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to assign stages to students...');

        // Get the first active stage
        $firstStage = Stage::where('status', 'active')
            ->orderBy('order')
            ->first();
        
        if (!$firstStage) {
            $this->error('No active stages found! Please create at least one active stage first.');
            return 1;
        }
        
        // Get all students without a stage
        $studentsWithoutStage = Student::whereNull('stage_id')->get();
        $count = $studentsWithoutStage->count();
        
        if ($count === 0) {
            $this->info('All students already have stages assigned.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        // Assign the first stage to all students without a stage
        foreach ($studentsWithoutStage as $student) {
            $student->stage_id = $firstStage->id;
            $student->save();
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Successfully assigned stage '{$firstStage->name}' to {$count} students!");
        
        return 0;
    }
}
