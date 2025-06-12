<?php

namespace Database\Seeders;

use App\Models\HeroSection;
use Illuminate\Database\Seeder;

class HeroSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeroSection::create([
            'title' => 'Get CAREER-READY',
            'subtitle' => 'Nurturing Future Innovators',
            'image_path' => 'hero-sections/sample-hero.jpg', // Make sure this file exists in storage
            'button_text' => 'Join Us Today',
            'button_link' => '/students/register',
            'is_active' => true,
            'display_order' => 1,
            'text_color' => '#ffffff',
            'title_color' => '#ffffff',
            'subtitle_color' => '#ffffff',
            'brand_text' => 'Innovation Academy',
            'brand_text_color' => '#ffcb05',
            'overlay_color' => '#000000',
            'overlay_opacity' => 0.5,
            'text_position' => 'bottom',
        ]);

        // Example of a hero section without brand text
        HeroSection::create([
            'title' => 'Develop Skills for Tomorrow',
            'subtitle' => 'Technology, Innovation, Creativity',
            'image_path' => 'hero-sections/sample-hero2.jpg', // Make sure this file exists in storage
            'button_text' => 'Explore Programs',
            'button_link' => '/programs',
            'is_active' => true,
            'display_order' => 2,
            'text_color' => '#ffffff',
            'title_color' => '#e6f7ff',
            'subtitle_color' => '#ffffff',
            'brand_text' => null, // No brand text for this hero section
            'brand_text_color' => null,
            'overlay_color' => '#000033',
            'overlay_opacity' => 0.6,
            'text_position' => 'middle',
        ]);
    }
}
