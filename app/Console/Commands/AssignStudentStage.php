<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssignStudentStage extends Command
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
    protected $description = 'Assign the first stage to students with NULL stage_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Assigning stages to students with NULL stage_id...');

        // Get the first stage by order
        $firstStage = \App\Models\Stage::orderBy('order')->first();
        
        if (!$firstStage) {
            $this->error('No stages found in the database. Please create stages first.');
            return 1;
        }
        
        // Find students with NULL stage_id and update them
        $count = \App\Models\Student::whereNull('stage_id')
            ->update(['stage_id' => $firstStage->id]);
        
        $this->info("Successfully assigned stage '{$firstStage->name}' to {$count} students.");
        return 0;
    }
}
