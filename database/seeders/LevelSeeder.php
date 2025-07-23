<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Stage;
use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get stages to associate with levels
        $stages = Stage::all();
        
        if ($stages->isEmpty()) {
            $this->command->warn('No stages found. Please run StageSeeder first.');
            return;
        }
        
        // Get all activities to distribute among levels
        $activities = Activity::all();
        
        if ($activities->isEmpty()) {
            $this->command->warn('No activities found. Please run ActivitySeeder first.');
            return;
        }

        // Define level types that will be created for each stage
        $levelTypes = [
            [
                'name' => 'Beginner',
                'description' => 'Foundation level for students just starting their learning journey at IMT.',
                'status' => 'active'
            ],
            [
                'name' => 'Intermediate',
                'description' => 'Mid-level education for students who have mastered basic concepts.',
                'status' => 'active'
            ],
            [
                'name' => 'Advanced',
                'description' => 'Higher level concepts for experienced students.',
                'status' => 'active'
            ],
            [
                'name' => 'Expert',
                'description' => 'Expert-level material for students seeking mastery of complex topics.',
                'status' => 'active'
            ],
        ];
        
        // For each stage, create multiple levels
        foreach ($stages as $stage) {
            // Create 2-4 levels per stage (random number)
            $numLevelsForStage = rand(2, 4);
            
            for ($levelNumber = 1; $levelNumber <= $numLevelsForStage; $levelNumber++) {
                // Pick a random level type for variety
                $levelType = $levelTypes[array_rand($levelTypes)];
                
                // Create level name like "Beginner - Level 1" or "Advanced - Level 3"
                $levelName = $levelType['name'] . ' - Level ' . $levelNumber;
                
                $level = Level::create([
                    'name' => $levelName,
                    'slug' => Str::slug($levelName),
                    'status' => $levelType['status'],
                    'description' => $levelType['description'] . ' ' . $stage->name . ' Stage - Level ' . $levelNumber,
                    'stage_id' => $stage->id,
                    'level_number' => $levelNumber
                ]);
                
                // Assign activities - higher levels get more activities
                $activityCount = 3 + $levelNumber; // Level 1 gets 4 activities, Level 2 gets 5, etc.
                
                // First pick activities from this stage if available
                $stageActivities = $stage->activities;
                if ($stageActivities->count() > 0) {
                    $selectedActivities = $stageActivities->random(min(ceil($activityCount/2), $stageActivities->count()));
                } else {
                    $selectedActivities = collect();
                }
                
                // Add some random activities from all activities to make it up to activity count
                $remainingNeeded = max(0, $activityCount - $selectedActivities->count());
                if ($remainingNeeded > 0) {
                    $additionalActivities = $activities->diff($selectedActivities)
                        ->random(min($remainingNeeded, $activities->count() - $selectedActivities->count()));
                    $selectedActivities = $selectedActivities->merge($additionalActivities);
                }
                
                // Attach activities
                $level->activities()->attach($selectedActivities->pluck('id'));
                
                $this->command->info("Created {$stage->name} stage - '{$levelName}' with {$selectedActivities->count()} activities.");
            }
        }
        
        $this->command->info('Sample levels seeded successfully.');
    }
}
