<?php

namespace App\Http\Controllers;

use App\Services\HeroSectionService;
use App\Services\YouTubeService;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    protected $heroSectionService;
    protected $youtubeService;

    /**
     * Create a new controller instance.
     *
     * @param HeroSectionService $heroSectionService
     * @param YouTubeService $youtubeService
     * @return void
     */
    public function __construct(HeroSectionService $heroSectionService, YouTubeService $youtubeService)
    {
        $this->heroSectionService = $heroSectionService;
        $this->youtubeService = $youtubeService;
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
        
        // Get YouTube videos for happenings section
        $videos = $this->youtubeService->getAllVideos();
        
        // We don't need to explicitly check auth here - Laravel will pass the auth data automatically
        // This keeps the session alive when browsing between authenticated and non-authenticated pages
        return view('welcome', compact('heroSections', 'videos'));
    }
}
