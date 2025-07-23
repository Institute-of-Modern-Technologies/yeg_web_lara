<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define sample activities for the IMT educational system
        $activities = [
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'Computer Science',
            'Literature',
            'History',
            'Geography',
            'Art',
            'Music',
            'Physical Education',
            'Foreign Languages',
            'Robotics',
            'Programming',
            'Leadership Training'
        ];
        
        foreach ($activities as $activity) {
            Activity::create([
                'name' => $activity,
                'slug' => Str::slug($activity)
            ]);
        }
        
        $this->command->info('Sample activities seeded successfully.');
    }
}
