<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\FaqCategory;

class PageController extends Controller
{
    /**
     * Show the about page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        // Get active FAQs with their categories
        $faqCategories = FaqCategory::with(['faqs' => function($query) {
            $query->where('is_active', true)->orderBy('display_order');
        }])->where('is_active', true)
          ->orderBy('display_order')
          ->get();

        // Meta information for SEO
        $metaTitle = 'About Us - Young Experts Group';
        $metaDescription = 'Learn about Young Experts Group, our mission, vision, and how we\'re transforming tech education for young minds in Africa.';
        $metaKeywords = 'about Young Experts, our story, tech education Africa, youth empowerment, digital skills training';

        return view('pages.about', compact('faqCategories', 'metaTitle', 'metaDescription', 'metaKeywords'));
    }

    /**
     * Show the career guidance page.
     *
     * @return \Illuminate\View\View
     */
    public function careerGuidance()
    {
        // Meta information for SEO
        $metaTitle = 'Career Guidance - Young Experts Group';
        $metaDescription = 'Explore career pathways in technology with Young Experts Group. Find resources, guidance, and support for young tech enthusiasts.';
        $metaKeywords = 'tech careers, youth career guidance, digital careers, tech education, future skills, technology career paths';

        return view('pages.career-guidance', compact('metaTitle', 'metaDescription', 'metaKeywords'));
    }

    /**
     * Show the programs page.
     *
     * @return \Illuminate\View\View
     */
    public function programs()
    {
        // Meta information for SEO
        $metaTitle = 'Our Programs - Young Experts Group';
        $metaDescription = 'Discover our innovative technology programs designed for young minds. Coding, digital marketing, graphic design, and more for children and teenagers.';
        $metaKeywords = 'tech programs for kids, coding classes, digital skills, STEM education, technology courses, children programming';

        // Program categories that will be shown on the page
        $programCategories = [
            [
                'name' => 'Little Tech Explorers',
                'age' => '6-9 years',
                'description' => 'Introduction to digital literacy through fun, interactive activities. Students learn basic computer skills, simple block-based coding, and digital art in a playful environment.',
                'color' => 'bg-pink-500',
                'image' => 'https://images.pexels.com/photos/5621936/pexels-photo-5621936.jpeg'
            ],
            [
                'name' => 'Code Stars',
                'age' => '10-13 years',
                'description' => 'Building fundamental programming skills using Scratch and beginning JavaScript. Students create animations, games, and simple apps while learning computational thinking.',
                'color' => 'bg-purple-500',
                'image' => 'https://images.pexels.com/photos/5306484/pexels-photo-5306484.jpeg'
            ],
            [
                'name' => 'Tech Leaders',
                'age' => '14-16 years',
                'description' => 'Advanced coding and project development using Python and web technologies. Students work on real-world problems and develop portfolio-ready projects.',
                'color' => 'bg-green-500',
                'image' => 'https://images.pexels.com/photos/7237211/pexels-photo-7237211.jpeg'
            ]
        ];

        return view('pages.programs', compact('metaTitle', 'metaDescription', 'metaKeywords', 'programCategories'));
    }
    
    /**
     * Show the enrollment and rates page.
     *
     * @return \Illuminate\View\View
     */
    public function enrollment()
    {
        // Meta information for SEO
        $metaTitle = 'Enrollment & Rates - Young Experts Group';
        $metaDescription = 'Learn about our program fees, enrollment process, and register your child for our tech education programs. Scholarship options available.';
        $metaKeywords = 'tech program rates, coding class fees, enrollment process, tech education cost, children technology courses pricing';
        
        // Get program categories with pricing information from the database
        $programRates = [];
        
        // Get fees for standard programs
        $fees = \App\Models\Fee::with('programType')
            ->where('is_active', true)
            ->whereHas('programType', function($query) {
                $query->where('is_active', true);
            })
            ->get();
            
        // Define the colors for each program type (you can adjust these based on your preferences)
        $programColors = [
            'Little Tech Explorers' => 'border-pink-500',
            'Code Stars' => 'border-purple-500',
            'Tech Leaders' => 'border-green-500',
            'School Programs' => 'border-yellow-500'
        ];
        
        // Age ranges for each program
        $programAges = [
            'Little Tech Explorers' => '6-9 years',
            'Code Stars' => '10-13 years',
            'Tech Leaders' => '14-16 years',
            'School Programs' => 'For institutions'
        ];
        
        // Add standard programs to the rates array
        foreach ($fees as $fee) {
            $programName = $fee->programType->name ?? '';
            if (empty($programName)) continue;
            
            $programRates[] = [
                'name' => $programName,
                'age' => $programAges[$programName] ?? '',
                'price' => $fee->amount,
                'period' => 'per month',
                'color' => $programColors[$programName] ?? 'border-gray-500'
            ];
        }
        
        // Add school programs as a special case if not already included
        $schoolProgramExists = collect($programRates)->where('name', 'School Programs')->count() > 0;
        
        if (!$schoolProgramExists) {
            $programRates[] = [
                'name' => 'School Programs',
                'age' => 'For institutions',
                'price' => 'Custom',
                'period' => 'Contact for details',
                'color' => 'border-yellow-500'
            ];
        }
        
        return view('pages.enrollment', compact('metaTitle', 'metaDescription', 'metaKeywords', 'programRates'));
    }
}
