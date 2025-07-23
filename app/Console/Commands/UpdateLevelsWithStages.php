<?php

namespace App\Console\Commands;

use App\Models\Level;
use App\Models\Stage;
use Illuminate\Console\Command;

class UpdateLevelsWithStages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-levels-with-stages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Associates existing levels with stages and assigns level numbers without losing data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if we have stages
        $stages = Stage::orderBy('order')->get();
        if ($stages->isEmpty()) {
            $this->error('No stages found in the database. Please run the StageSeeder first.');
            return 1;
        }
        
        // Get all existing levels
        $levels = Level::all();
        if ($levels->isEmpty()) {
            $this->info('No levels found in the database. Nothing to update.');
            return 0;
        }
        
        $this->info('Found ' . $levels->count() . ' levels and ' . $stages->count() . ' stages.');
        
        // Group stages by their general purpose for more sensible assignment
        $introStage = $stages->where('name', 'like', '%Introduction%')->first() 
                    ?? $stages->where('name', 'like', '%Basic%')->first() 
                    ?? $stages->first();
        
        $advancedStage = $stages->where('name', 'like', '%Advanced%')->first() 
                      ?? $stages->where('name', 'like', '%Master%')->first() 
                      ?? $stages->last();
        
        $middleStages = $stages->filter(function($stage) use ($introStage, $advancedStage) {
            return $stage->id !== $introStage->id && $stage->id !== $advancedStage->id;
        });
        
        $this->info('Intro stage: ' . $introStage->name);
        if ($middleStages->isNotEmpty()) {
            $this->info('Middle stages: ' . $middleStages->pluck('name')->implode(', '));
        }
        $this->info('Advanced stage: ' . $advancedStage->name);
        
        $bar = $this->output->createProgressBar($levels->count());
        $bar->start();
        
        // Process each level and assign a stage
        foreach ($levels as $level) {
            // Determine which stage to assign based on level name
            $stage = null;
            $levelNumber = null;
            
            if (preg_match('/beginner|basic|fundamental|intro/i', $level->name)) {
                $stage = $introStage;
                $levelNumber = 1;
            } else if (preg_match('/advanced|expert|master/i', $level->name)) {
                $stage = $advancedStage;
                $levelNumber = 3;
            } else if (preg_match('/intermediate|mid|middle/i', $level->name)) {
                if ($middleStages->isNotEmpty()) {
                    $stage = $middleStages->random(1)->first();
                } else {
                    $stage = $stages->random(1)->first();
                }
                $levelNumber = 2;
            } else {
                // For any other levels, assign a random stage
                $stage = $stages->random(1)->first();
                $levelNumber = rand(1, 3);
            }
            
            // Update the level with stage_id and level_number
            $level->stage_id = $stage->id;
            $level->level_number = $levelNumber;
            $level->save();
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Successfully updated all levels with stage associations and level numbers.');
        
        return 0;
    }
}
