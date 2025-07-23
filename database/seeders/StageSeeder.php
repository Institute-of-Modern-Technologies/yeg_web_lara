<?php

namespace Database\Seeders;

use App\Models\Stage;
use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define sample stages for the IMT educational system
        $stages = [
            [
                'name' => 'Introduction',
                'description' => 'Introduces key concepts and foundational principles.',
                'status' => 'active',
                'order' => 0
            ],
            [
                'name' => 'Knowledge Building',
                'description' => 'Building core knowledge and understanding of the subject.',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Practical Application',
                'description' => 'Applying learned concepts to real-world scenarios.',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Analysis & Problem Solving',
                'description' => 'Developing analytical skills and problem-solving techniques.',
                'status' => 'active',
                'order' => 3
            ],
            [
                'name' => 'Creation & Innovation',
                'description' => 'Creating new solutions and innovative approaches.',
                'status' => 'active',
                'order' => 4
            ],
            [
                'name' => 'Evaluation & Mastery',
                'description' => 'Final stage focusing on evaluation and mastery of the subject.',
                'status' => 'active',
                'order' => 5
            ],
        ];
        
        // Get all activities to distribute among stages
        $activities = Activity::all();
        
        if ($activities->isEmpty()) {
            $this->command->warn('No activities found. Please run ActivitySeeder first.');
            return;
        }
        
        // Group activities by their general category for more logical distribution
        $scienceActivities = $activities->filter(function($activity) {
            return Str::contains(strtolower($activity->name), ['physics', 'chemistry', 'biology', 'mathematics', 'science']);
        });
        
        $techActivities = $activities->filter(function($activity) {
            return Str::contains(strtolower($activity->name), ['computer', 'programming', 'robotics']);
        });
        
        $humanitiesActivities = $activities->filter(function($activity) {
            return Str::contains(strtolower($activity->name), ['literature', 'history', 'art', 'music', 'language']);
        });
        
        $otherActivities = $activities->diff($scienceActivities)->diff($techActivities)->diff($humanitiesActivities);
        
        // Create stages and associate with activities
        foreach ($stages as $index => $stageData) {
            $stage = Stage::create([
                'name' => $stageData['name'],
                'slug' => Str::slug($stageData['name']),
                'status' => $stageData['status'],
                'description' => $stageData['description'],
                'order' => $stageData['order']
            ]);
            
            // Associate activities based on stage type
            // Different distribution for different stages to make it more realistic
            switch($index) {
                case 0: // Introduction - mix of everything
                    $selectedActivities = $activities->random(min(8, $activities->count()));
                    break;
                    
                case 1: // Knowledge Building - more science and humanities
                    $selected = collect();
                    $selected = $selected->merge($scienceActivities->random(min(3, $scienceActivities->count())));
                    $selected = $selected->merge($humanitiesActivities->random(min(3, $humanitiesActivities->count())));
                    $selectedActivities = $selected;
                    break;
                    
                case 2: // Practical Application - more tech and science
                    $selected = collect();
                    $selected = $selected->merge($techActivities->random(min(2, $techActivities->count())));
                    $selected = $selected->merge($scienceActivities->random(min(3, $scienceActivities->count())));
                    $selected = $selected->merge($otherActivities->random(min(2, $otherActivities->count())));
                    $selectedActivities = $selected;
                    break;
                    
                case 3: // Analysis & Problem Solving - mix with focus on technical
                    $selected = collect();
                    $selected = $selected->merge($techActivities->random(min(3, $techActivities->count())));
                    $selected = $selected->merge($scienceActivities->random(min(2, $scienceActivities->count())));
                    $selected = $selected->merge($otherActivities->random(min(1, $otherActivities->count())));
                    $selectedActivities = $selected;
                    break;
                    
                case 4: // Creation & Innovation - creative mix
                    $selected = collect();
                    $selected = $selected->merge($techActivities->random(min(2, $techActivities->count())));
                    $selected = $selected->merge($humanitiesActivities->random(min(3, $humanitiesActivities->count())));
                    $selectedActivities = $selected;
                    break;
                    
                case 5: // Evaluation & Mastery - comprehensive coverage
                    $selectedActivities = $activities->random(min(10, $activities->count()));
                    break;
                    
                default:
                    $selectedActivities = $activities->random(min(5, $activities->count()));
            }
            
            if ($selectedActivities->count() > 0) {
                $stage->activities()->attach($selectedActivities->pluck('id'));
            }
            
            $this->command->info("Created stage '{$stageData['name']}' with {$selectedActivities->count()} activities.");
        }
        
        $this->command->info('Sample stages seeded successfully.');
    }
}
