<?php

namespace App\Http\Controllers;

use App\Services\HeroSectionService;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    protected $heroSectionService;

    /**
     * Create a new controller instance.
     *
     * @param HeroSectionService $heroSectionService
     * @return void
     */
    public function __construct(HeroSectionService $heroSectionService)
    {
        $this->heroSectionService = $heroSectionService;
    }

    /**
     * Show the application welcome page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get active hero sections
        $heroSections = $this->heroSectionService->getActiveHeroSections();
        
        return view('welcome', compact('heroSections'));
    }
}
