@extends('layouts.app')

@section('content')

<!-- AOS CSS Library -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
    /* Force Montserrat font on every element in the welcome page */
    #welcome-page *:not(.fas):not(.far):not(.fa):not(.fab) {
        font-family: 'Montserrat', sans-serif !important;
    }
    
    /* Force full width on mobile */
    html, body {
        width: 100vw;
        overflow-x: hidden;
        max-width: 100%;
    }
    
    #welcome-page {
        width: 100vw !important;
        max-width: 100vw !important;
        margin: 0 !important;
        padding: 0 !important;
        left: 0 !important;
        right: 0 !important;
        overflow-x: hidden;
    }
</style>
<div id="welcome-page" class="font-montserrat w-full max-w-full overflow-x-hidden" style="margin-right: auto; margin-left: auto; position: relative; left: 0; right: 0;">

    <!-- Hero Section with Carousel -->
    <section id="hero-section" class="hero-section relative overflow-hidden min-h-[600px] md:min-h-[700px] bg-primary">
        <style>
            /* Smooth carousel transitions */
            .carousel-item {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;
                z-index: 1;
            }
            
            .carousel-item.active {
                opacity: 1;
                visibility: visible;
                z-index: 2;
            }
            
            /* Enhanced carousel dot styling */
            .carousel-dot {
                width: 12px;
                height: 12px;
                background-color: rgba(255, 255, 255, 0.5);
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.4s ease;
                border: 2px solid transparent;
            }
            
            .carousel-dot:hover {
                transform: scale(1.2);
                background-color: rgba(255, 255, 255, 0.8);
            }
            
            .carousel-dot.active {
                background-color: white;
                box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
                transform: scale(1.1);
            }
            
            /* Smooth button hover effects */
            .carousel-prev, .carousel-next {
                transition: all 0.3s ease;
            }
            
            .carousel-prev:hover, .carousel-next:hover {
                transform: translateY(-50%) scale(1.1);
                background-color: rgba(255, 255, 255, 0.5);
            }
        </style>
        @if($heroSections->isEmpty())
        <!-- Default Hero Section (Shown when no hero sections are configured) -->
        <div class="carousel-item active" id="default-slide">
            <!-- Background Image -->
            <div class="absolute inset-0 w-full h-full parallax-bg" id="hero-parallax-bg">
                <img class="max-w-full w-full h-full object-cover parallax-img fade-in-bg" src="{{ asset('images/Hero picture 3.png') }}" alt="Hero Image">
                <!-- Subtle overlay for text readability -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent"></div>
            </div>
            
            <!-- Text Content -->
            <div class="relative h-full flex items-end pb-16 justify-start pl-8 md:pl-16 lg:pl-24">
                <div class="container-fluid px-0">
                    <div class="max-w-2xl text-center py-6 px-8" data-aos="fade-up" data-aos-delay="300">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-3 leading-tight text-shadow-lg" data-aos="zoom-in" data-aos-delay="100" aria-label="Get Career Ready">
                            <span class="headline-word text-white">Get</span>
                            <span class="headline-word text-secondary">CAREER-READY</span>
                        </h1>
                        <h2 class="text-2xl md:text-3xl mb-4 font-light text-shadow-md" data-aos="fade-up" data-aos-delay="250">
                            <span class="text-white">Nurturing Future Innovators</span>
                        </h2>
                        <div class="relative inline-block" data-aos="zoom-in" data-aos-delay="400">
                            <button class="heroRegisterBtn bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 inline-block text-lg px-6 py-3 rounded-md shadow-lg text-white font-medium flex items-center space-x-2">
                                <i class="fas fa-user-plus"></i>
                                <span>Join Us Today</span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                            </button>
                            <div class="heroDropdownMenu absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl overflow-hidden z-50 hidden transform transition-all duration-300 ease-in-out border border-gray-100">
                                <div class="py-2">
                                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-700">Register as:</p>
                                    </div>
                                    <a href="/students/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                        <i class="fas fa-user-graduate mr-3 text-blue-500"></i>
                                        <div>
                                            <p class="font-medium">Student</p>
                                            <p class="text-xs text-gray-500">Join our learning programs</p>
                                        </div>
                                    </a>
                                    <a href="/teachers/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                        <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>
                                        <div>
                                            <p class="font-medium">Trainer</p>
                                            <p class="text-xs text-gray-500">Become a YEG instructor</p>
                                        </div>
                                    </a>
                                    <a href="/schools/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                        <i class="fas fa-school mr-3 text-purple-500"></i>
                                        <div>
                                            <p class="font-medium">School</p>
                                            <p class="text-xs text-gray-500">Partner with Young Experts</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Dynamic Hero Sections from Database -->
        @foreach($heroSections as $index => $heroSection)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" id="slide-{{ $heroSection->id }}">
            <!-- Background Image -->
            <div class="absolute inset-0 w-full h-full">
                <img class="max-w-full w-full h-full object-cover" src="{{ asset('storage/' . $heroSection->image_path) }}" alt="{{ $heroSection->title }}">
                <!-- Custom overlay with configured color and opacity -->
                <div class="absolute inset-0" style="{{ app(\App\Services\HeroSectionService::class)->generateOverlayStyles($heroSection) }}"></div>
            </div>
            
            <!-- Text Content -->
            <div class="relative h-full flex {{ $heroSection->text_position == 'top' ? 'items-start pt-32' : ($heroSection->text_position == 'middle' ? 'items-center' : 'items-end pb-16') }} justify-start pl-8 md:pl-16 lg:pl-24">
                <div class="container-fluid px-0">
                    <x-hero-section-text :heroSection="$heroSection" />
                </div>
            </div>
        </div>
        @endforeach
        
        <!-- Carousel Controls -->
        <div class="carousel-controls absolute bottom-6 w-full flex justify-center space-x-4 z-10">
            @foreach($heroSections as $index => $heroSection)
            <div class="carousel-dot {{ $index === 0 ? 'active' : '' }}" data-slide="slide-{{ $heroSection->id }}"></div>
            @endforeach
        </div>
        
        <!-- Carousel Navigation Arrows -->
        <button class="carousel-prev absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/30 hover:bg-white/50 rounded-full p-2 z-10">
            <i class="fas fa-chevron-left text-white"></i>
        </button>
        <button class="carousel-next absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/30 hover:bg-white/50 rounded-full p-2 z-10">
            <i class="fas fa-chevron-right text-white"></i>
        </button>
        @endif
    </section>

    <!-- Our Stages Section -->
    <section id="our-stages" class="py-16 bg-white relative overflow-hidden">
        <div id="our-stages-bg"></div>
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto relative z-10">
            <h2 class="text-4xl font-bold text-center mb-12 text-primary" data-aos="fade-up" data-aos-delay="100">Our Stages</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 mx-auto w-full max-w-4xl">
                <!-- Discover Card -->
                <div class="bg-white rounded-md p-6 shadow-md border flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1" style="border-color: #950713; border-width: 2px;" data-aos="flip-up" data-aos-delay="300">
                    <div class="text-3xl mb-3" style="color: #950713;">
                        <i class="fas fa-users icon-animated"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: #950713;">Discover</h3>
                    <p class="text-sm" style="color: #950713;">Transforming Ideas<br>into Innovation.</p>
                </div>
                
                <!-- Build Card -->
                <div class="bg-white rounded-md p-6 shadow-md border flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1" style="border-color: #950713; border-width: 2px;" data-aos="fade-right" data-aos-delay="400">
                    <div class="text-3xl mb-3" style="color: #950713;">
                        <i class="fas fa-university icon-animated"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: #950713;">Build</h3>
                    <p class="text-sm" style="color: #950713;">Crafting the Next<br>Big Thing.</p>
                </div>
                
                <!-- Mastery Card -->
                <div class="bg-white rounded-md p-6 shadow-md border flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1" style="border-color: #950713; border-width: 2px;" data-aos="fade-left" data-aos-delay="500">
                    <div class="text-3xl mb-3" style="color: #950713;">
                        <i class="fas fa-bell icon-animated"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: #950713;">Mastery</h3>
                    <p class="text-sm" style="color: #950713;">Where Skill Meets<br>Innovation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section id="about" class="py-16 bg-gray-50">
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold mb-4" data-aos="fade-up" data-aos-delay="100">Welcome</h2>
                <p class="text-lg" data-aos="fade-up" data-aos-delay="200">
                    Equipping young minds with skills in 
                    <span style="color: #950713;">Technology</span>, 
                    <span class="bg-yellow-400 text-black px-1">Entrepreneurship</span>, 
                    and 
                    <span style="color: #FF00FF;">Creativity</span>.
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 w-full mx-auto max-w-6xl">
                <!-- Left Column - Our Program -->
                <div class="border border-gray-200 rounded-md p-6 relative" data-aos="zoom-in" data-aos-delay="300">
                    <div class="flex items-center mb-4">
                        <div class="bg-neon-pink text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="text-xl font-bold">Our Program</h3>
                    </div>
                    
                    <p class="mb-4">
                        We collaborate with schools to provide <span style="color: #FF00FF;">innovative</span>, <span style="color: #FF00FF;">engaging</span>, and <span style="color: #FF00FF;">practical</span> learning experiences that prepare students for a <span class="bg-yellow-400 text-black px-1">tech-driven future</span>.
                    </p>
                    
                    <!-- Video Element -->
                    <div class="mb-6 mt-6 rounded-md overflow-hidden shadow-md">
                        <div class="relative">
                            <div id="youtube-player" class="w-full aspect-video max-w-full"></div>
                            <!-- Custom Controls -->
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 p-2 flex justify-center gap-4 transition-opacity duration-300">
                                <button id="play-pause-btn" class="text-white hover:text-gray-200 focus:outline-none">
                                    <i class="fas fa-pause"></i>
                                </button>
                                <button id="mute-btn" class="text-white hover:text-gray-200 focus:outline-none">
                                    <i class="fas fa-volume-mute"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    @section('scripts')
                    @parent
                    <!-- Active Navigation Link Script -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get all sections that we want to track
                            const sections = document.querySelectorAll('section[id]');
                            
                            // Get all navigation links
                            const navLinks = document.querySelectorAll('.nav-link');
                            
                            // Add active class to clicked nav links
                            navLinks.forEach(link => {
                                link.addEventListener('click', function(e) {
                                    // Remove active class from all links
                                    navLinks.forEach(link => link.classList.remove('active'));
                                    
                                    // Add active class to clicked link
                                    this.classList.add('active');
                                });
                            });
                            
                            // Function to update active link based on scroll position
                            function updateActiveLink() {
                                // Get current scroll position
                                let scrollPosition = window.scrollY;
                                
                                // Check each section to see if it's in view
                                sections.forEach(section => {
                                    const sectionTop = section.offsetTop - 100;
                                    const sectionHeight = section.offsetHeight;
                                    const sectionId = section.getAttribute('id');
                                    
                                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                                        // Remove active class from all links
                                        navLinks.forEach(link => link.classList.remove('active'));
                                        
                                        // Add active class to corresponding link
                                        const activeLink = document.querySelector(`.nav-link[data-section="${sectionId}"]`);
                                        if (activeLink) activeLink.classList.add('active');
                                    }
                                });
                            }
                            
                            // Listen for scroll events
                            window.addEventListener('scroll', updateActiveLink);
                            
                            // Initial call to set active link on page load
                            updateActiveLink();
                        });
                    </script>
                    
                    <!-- YouTube API and Control Script -->
                    <script>
                        // Load YouTube API
                        var tag = document.createElement('script');
                        tag.src = "https://www.youtube.com/iframe_api";
                        var firstScriptTag = document.getElementsByTagName('script')[0];
                        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                        
                        var player;
                        var isPlaying = true;
                        var isMuted = true; // Start as muted
                        
                        function onYouTubeIframeAPIReady() {
                            player = new YT.Player('youtube-player', {
                                height: '100%',
                                width: '100%',
                                videoId: 'x_kUqKoTZR8',
                                playerVars: {
                                    'autoplay': 1,
                                    'controls': 0,
                                    'showinfo': 0,
                                    'rel': 0,
                                    'loop': 1,
                                    'playlist': 'x_kUqKoTZR8',
                                    'modestbranding': 1,
                                    'mute': 1 // Start muted by default
                                },
                                events: {
                                    'onReady': onPlayerReady
                                }
                            });
                        }
                        
                        function onPlayerReady(event) {
                            event.target.playVideo();
                            
                            // Set up play/pause button
                            document.getElementById('play-pause-btn').addEventListener('click', function() {
                                if (isPlaying) {
                                    player.pauseVideo();
                                    this.innerHTML = '<i class="fas fa-play"></i>';
                                } else {
                                    player.playVideo();
                                    this.innerHTML = '<i class="fas fa-pause"></i>';
                                }
                                isPlaying = !isPlaying;
                            });
                            
                            // Set up mute button
                            document.getElementById('mute-btn').addEventListener('click', function() {
                                if (isMuted) {
                                    player.unMute();
                                    this.innerHTML = '<i class="fas fa-volume-up"></i>';
                                } else {
                                    player.mute();
                                    this.innerHTML = '<i class="fas fa-volume-mute"></i>';
                                }
                                isMuted = !isMuted;
                            });
                        }
                    </script>
                    @endsection

                </div>
                
                <!-- Right Column - Why Partner With Us -->
                <div class="border border-gray-200 rounded-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-neon-pink text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="text-xl font-bold">Why Partner With Us</h3>
                    </div>
                    
                    <p class="mb-6">
                        <span>T</span>he Young Experts Group (YEG) collaborates with schools to provide hands-on 
                        <span class="bg-cyan-500 text-white px-1">Technology</span>, 
                        <span class="bg-yellow-400 text-black px-1">Entrepreneurship</span>, and
                        <span class="bg-neon-pink text-white px-1" style="background-color: #FF00FF !important;">Creativity</span> training. Our tailored programs complement academic learning and prepare students for a tech-driven future.
                    </p>
                    
                    <!-- Feature Boxes -->
                    <div class="space-y-4">
                        <!-- Integrated Learning -->
                        <div class="border rounded-md p-3" style="border-color: #950713; background-color: rgba(149, 7, 19, 0.05);">
                            <div class="flex items-start">
                                <div class="text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 mt-1 flex-shrink-0" style="background-color: #950713;">
                                    <i class="fas fa-puzzle-piece text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold" style="color: #950713;">Integrated Learning</h4>
                                    <p class="text-sm">We work with your school to design a program that fits seamlessly into your curriculum needs.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Practical & Fun -->
                        <div class="border rounded-md p-3" style="border-color: #ffcb05; background-color: rgba(255, 203, 5, 0.1);">
                            <div class="flex items-start">
                                <div class="text-black rounded-full w-6 h-6 flex items-center justify-center mr-3 mt-1 flex-shrink-0" style="background-color: #ffcb05;">
                                    <i class="fas fa-lightbulb text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold" style="color: #ffcb05;">Practical & Fun</h4>
                                    <p class="text-sm">Hands-on projects that keep students engaged while learning valuable skills.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Flexible & Customizable -->
                        <div class="border rounded-md p-3" style="border-color: #FF00FF; background-color: rgba(255, 0, 255, 0.05);">
                            <div class="flex items-start">
                                <div class="text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 mt-1 flex-shrink-0" style="background-color: #FF00FF;">
                                    <i class="fas fa-sliders-h text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold" style="color: #FF00FF;">Flexible & Customizable</h4>
                                    <p class="text-sm">Programs are designed to match your school's specific needs.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Happenings Section -->
    <section id="happenings" class="py-16 bg-primary text-white" style="background-color: #950713 !important;">
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 w-full">
                <!-- Left Column - Blog Posts -->
                <div class="lg:col-span-2">
                    <div data-aos="fade-right">
                        <h4 class="text-sm mb-2">HAPPENINGS AT <span class="text-secondary font-bold" style="color: #ffcb05 !important;">YOUNG EXPERTS</span></h4>
                        <h2 class="text-3xl font-bold mb-8">
                            This Week at<span class="text-secondary" style="color: #ffcb05 !important;"> Young Experts </span><span class="text-cyan-400">GROUP</span>:
                            <br>where your ideas come to life!
                        </h2>
                    </div>
                    
                    <!-- YouTube Videos Section -->
                    <div class="flex flex-col space-y-4">
                        <!-- Section Title -->
                        <h3 class="text-xl font-bold mb-4">Latest From Our Channel</h3>
                        
                        <!-- Video 1: YEG Workshop Highlights -->
                        <div class="flex flex-col md:flex-row bg-black bg-opacity-20 rounded-md p-4" data-aos="fade-up">
                            <div class="flex-shrink-0 md:mr-6 mb-4 md:mb-0 w-full md:w-1/3 lg:w-1/4">
                                <div class="relative rounded-md overflow-hidden w-full h-48 md:h-64 shadow-lg">
                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/fE0mA2GG3Ug" 
                                        title="YEG Workshop Highlights" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center text-xs mb-2">
                                    <span class="mr-3 mb-1"><i class="fas fa-user text-secondary mr-1" style="color: #ffcb05 !important;"></i> YOUNG EXPERTS GROUP</span>
                                    <span class="mb-1"><i class="far fa-calendar text-secondary mr-1" style="color: #ffcb05 !important;"></i> JULY 15, 2025</span>
                                </div>
                                <h3 class="text-xl font-bold mb-2">YEG Workshop Highlights</h3>
                                <p class="text-sm text-gray-200 mb-4">
                                    Experience our hands-on approach to teaching technology and innovation. Our workshops are designed to engage students in practical learning experiences that build real-world skills and confidence.
                                </p>
                                <div class="flex space-x-4">
                                    <a href="https://www.youtube.com/watch?v=fE0mA2GG3Ug" target="_blank" 
                                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                        <i class="fab fa-youtube mr-2"></i> Watch on YouTube
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Young Experts Group YouTube Videos Section -->
                        <div class="bg-black bg-opacity-20 rounded-md p-4" data-aos="fade-up" style="background-color: rgba(149, 7, 19, 0.2) !important;">
                            <div class="mb-4 text-center">
                                <h3 class="text-xl font-bold">Young Experts Group Video Gallery</h3>
                                <p class="text-sm text-gray-200">
                                    Watch our latest videos showcasing student projects and workshop highlights.
                                </p>
                            </div>
                            
                            <!-- Featured Video (your original embed that worked) -->
                            <div class="mb-6">
                                <div class="relative w-full overflow-hidden" style="padding-bottom: 56.25%;">
                                    <iframe class="absolute top-0 left-0 w-full h-full shadow-lg rounded-lg" 
                                        src="https://www.youtube.com/embed/fk1zODdid0A?si=mlH9XC1urpW3rvDe" 
                                        title="YouTube video player" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        referrerpolicy="strict-origin-when-cross-origin" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                <h4 class="mt-2 text-center font-medium">Featured: Young Experts Group Classes</h4>
                            </div>
                            
                            <!-- Spacer between video and button -->
                            <div class="mb-6"></div>
                            
                            <!-- Visit Channel Button -->
                            <div class="mt-4 flex justify-center">
                                <a href="https://www.youtube.com/@YoungExpertsGroup" target="_blank" 
                                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white" style="background-color: #950713;">
                                    <i class="fab fa-youtube mr-2"></i> Visit Our YouTube Channel for More Videos
                                </a>
                            </div>
                        </div>

                        
                        <!-- More Videos Button -->
                        <div class="text-center mt-6">
                            <a href="https://www.youtube.com/@YoungExpertsGroup" target="_blank" 
                               class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                <i class="fab fa-youtube mr-2"></i> View All Videos
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Stats/Register -->
                <div class="lg:col-span-1">
                    <div class="bg-white text-black rounded-md p-6" data-aos="fade-left">
                        <h3 class="text-sm text-center mb-4">FUN FACTS ABOUT <span class="text-secondary font-bold">YOUNG EXPERTS</span></h3>
                        
                        <!-- Stats 1 -->
                        <div class="text-center mb-6" data-aos="flip-up" data-aos-delay="200">
                            <h2 class="text-4xl font-bold text-[#950713] counter-value">500+</h2>
                            <p class="text-xs uppercase text-gray-600">
                                NUMBER OF STUDENTS<br>
                                <span class="text-xs normal-case">learning with</span><br>
                                <span class="text-xs text-secondary font-bold">Young Experts</span>
                            </p>
                        </div>
                        
                        <!-- Stats 2 -->
                        <div class="text-center mb-6" data-aos="flip-up" data-aos-delay="400">
                            <h2 class="text-4xl font-bold text-[#950713] counter-value">10+</h2>
                            <p class="text-xs uppercase text-gray-600">
                                NUMBER OF SCHOOLS<br>
                                <span class="text-xs normal-case">partner schools</span><br>
                                <span class="text-xs text-secondary font-bold">of Young Experts</span>
                            </p>
                        </div>
                        
                        <!-- Stats 3 -->
                        <div class="text-center mb-6" data-aos="flip-up" data-aos-delay="600">
                            <h2 class="text-4xl font-bold text-[#950713] counter-value">50+</h2>
                            <p class="text-xs uppercase text-gray-600">
                                NUMBER OF TRAINERS<br>
                                <span class="text-xs normal-case">available at</span><br>
                                <span class="text-xs text-secondary font-bold">Young Experts</span>
                            </p>
                        </div>
                        
                        <!-- Register Button with Dropdown -->
                        <div class="relative" id="happeningsDropdownContainer">
                            <button id="happeningsRegisterBtn" class="w-full bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white py-3 rounded-md mt-4 flex items-center justify-center space-x-2 transition-all duration-300 text-sm sm:text-base">
                                <i class="fas fa-user-plus"></i>
                                <span>Register Now</span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-300" id="happeningsChevron"></i>
                            </button>
                            <div id="happeningsDropdownMenu" class="absolute left-0 right-0 mt-2 bg-white rounded-lg shadow-xl overflow-hidden z-50 hidden transform transition-all duration-300 ease-in-out border border-gray-100">
                                <div class="py-2">
                                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-700">Register as:</p>
                                    </div>
                                    <a href="/students/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                        <i class="fas fa-user-graduate mr-3 text-blue-500"></i>
                                        <div>
                                            <p class="font-medium">Student</p>
                                            <p class="text-xs text-gray-500">Join our learning programs</p>
                                        </div>
                                    </a>
                                    <a href="/teachers/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                        <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>
                                        <div>
                                            <p class="font-medium">Trainer</p>
                                            <p class="text-xs text-gray-500">Become a YEG instructor</p>
                                        </div>
                                    </a>
                                    <a href="/schools/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                        <i class="fas fa-school mr-3 text-purple-500"></i>
                                        <div>
                                            <p class="font-medium">School</p>
                                            <p class="text-xs text-gray-500">Partner with Young Experts</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 bg-blue-50">
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto">
            <!-- Top Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
                <!-- Left Column - Text -->
                <div class="flex flex-col justify-center">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">FAQs</h2>
                    <p class="text-gray-600">
                        Have questions? Here you'll find the answers most
                        valued by our learners, along with access to step-
                        by-step instructions and support.
                    </p>
                </div>
                
                <!-- Right Column - Illustration -->
                <div class="flex justify-center items-center relative">
                    <div class="relative">
                        <img class="max-w-full" src="{{ asset('images/clipart.png') }}" alt="FAQ Illustration" class="max-w-full h-auto rounded-lg">
                        <!-- Question Marks -->
                        <div class="absolute -top-4 right-12 text-3xl text-orange-400">?</div>
                        <div class="absolute top-12 -left-4 text-3xl text-orange-400">?</div>
                        <div class="absolute -bottom-2 right-20 text-3xl text-orange-400">?</div>
                        <div class="absolute bottom-24 -right-4 text-3xl text-orange-400">?</div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-12"></div>
            
            <!-- FAQ Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Categories -->
                <div class="lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-6 text-gray-800 font-montserrat">Categories</h3>
                    
                    <!-- Category List -->
                    <div class="space-y-2">
                        <!-- About Us -->
                        <a href="{{ route('about') }}" class="block transition-all duration-300 hover:shadow-md">
                            <div class="border border-teal-300 rounded-md overflow-hidden">
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                    <span class="text-gray-700 font-montserrat">About us</span>
                                    <div class="w-6 h-6 bg-teal-100 rounded-full flex items-center justify-center text-teal-500">
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Career Guidance -->
                        <a href="{{ route('career.guidance') }}" class="block">
                            <div class="border border-teal-300 rounded-md overflow-hidden">
                                <div class="flex items-center justify-between p-3 cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <span class="text-gray-700 font-semibold font-montserrat">Career Guidance</span>
                                    <div class="w-6 h-6 bg-teal-100 rounded-full flex items-center justify-center text-teal-500">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Our Programs -->
                        <a href="{{ route('programs') }}" class="block">
                            <div class="border border-teal-300 rounded-md overflow-hidden">
                                <div class="flex items-center justify-between p-3 cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <span class="text-gray-700 font-semibold font-montserrat">Our Programs</span>
                                    <div class="w-6 h-6 bg-teal-100 rounded-full flex items-center justify-center text-teal-500">
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                        

                        <!-- Enrollment and rates -->
                        <a href="{{ route('enrollment') }}" class="block">
                            <div class="border border-[#950713] rounded-md overflow-hidden">
                                <div class="flex items-center justify-between p-3 cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <span class="text-gray-700 font-semibold font-montserrat">Enrollment and rates</span>
                                    <div class="w-6 h-6 bg-[#ffcb05]/20 rounded-full flex items-center justify-center text-[#950713]">
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Right Column - FAQ Questions -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-semibold mb-6 text-[#950713] font-montserrat" data-aos="fade-up">FAQ'S</h3>
                    
                    <!-- FAQ Accordion -->
                    <div class="space-y-4" id="faq-accordion">
                        <!-- Question 1 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">What is the Young Experts Group (YEG)?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    The Young Experts Group (YEG) is an educational initiative that focuses on equipping young minds with skills in technology, entrepreneurship, and creativity. We partner with schools to provide innovative learning experiences that prepare students for a technology-driven future.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Question 2 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">Who can join the YEG program?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    Our programs are designed for students of various age groups, typically ranging from elementary to high school levels. We also work directly with schools to implement our curriculum as part of their educational offerings.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Question 3 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">What will participants learn?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    Participants will learn a variety of skills across three main pillars: Technology (coding, digital literacy, app development), Entrepreneurship (business fundamentals, problem-solving, project management), and Creativity (design thinking, innovation, digital arts).
                                </p>
                            </div>
                        </div>
                        
                        <!-- Question 4 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">How is the program structured?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    Our programs follow a three-stage approach: Discover (exploring foundational concepts), Build (applying knowledge through hands-on projects), and Mastery (refining skills and creating comprehensive solutions). Each stage builds upon the previous one to ensure a complete learning journey.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Question 5 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">What makes YEG different from regular tech classes?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    YEG stands out by integrating technology education with entrepreneurship and creativity. We focus on practical, project-based learning rather than theoretical knowledge alone. Our curriculum is designed to be engaging, fun, and directly applicable to real-world scenarios.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Question 6 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">What do students receive at the end of the program?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    Upon completion, students receive a certificate of achievement, a digital portfolio of their projects, and access to the YEG alumni network. More importantly, they gain valuable skills, confidence, and a foundation for future academic and career pursuits.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Question 7 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">Where is YEG held?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    YEG programs are flexible in location. We can implement our curriculum directly within schools during regular hours or after school. We also offer online options for remote learning and dedicated workshops at our partner locations.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Question 8 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">How do we register our child or school?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    You can register through our website by clicking the 'Register Now' button. For schools interested in partnering with us, please reach out through the 'Contact Us' section or send an email to partnerships@youngexpertsgroup.com for a personalized consultation.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Question 9 -->
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 faq-question" onclick="toggleFaq(this)">
                                <h4 class="text-gray-700 font-semibold font-montserrat">Is there a cost to participate?</h4>
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 faq-icon">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100 faq-answer" style="display: none;">
                                <p class="text-gray-600">
                                    Yes, there is a program fee that varies based on the specific course, duration, and delivery method. We offer scholarship options for qualifying students and special rates for school partnerships. Contact us for detailed pricing information.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Partnered Schools Showcase -->
    <section class="py-16 bg-primary text-white" style="background-color: #950713 !important;">
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <span class="inline-block text-yellow-400 text-sm font-semibold tracking-wider mb-3">OUR PARTNERS</span>
                <h2 class="text-4xl font-bold mb-4 text-white">
                    Partnered <span class="text-yellow-400">Schools</span>
                </h2>
                <div class="w-20 h-1 bg-yellow-400 mx-auto mb-6"></div>
                <p class="text-lg text-white/90 max-w-3xl mx-auto">
                    We're proud to collaborate with leading educational institutions to deliver exceptional learning experiences.
                    Join our network and become part of something extraordinary.
                </p>
            </div>
            
            @php
                // Get active partner schools ordered by display_order
                $partnerSchools = \App\Models\PartnerSchool::active()->ordered()->get();
            @endphp
            
            <!-- School Showcase Carousel -->
            <div class="relative max-w-5xl mx-auto">
                @if($partnerSchools->isEmpty())
                <!-- No Schools Message -->
                <div class="bg-gradient-to-r from-primary to-red-900 rounded-lg overflow-hidden relative p-10 text-center">
                    <p class="text-white text-lg">No partner schools available at the moment. Be the first to join our network!</p>
                </div>
                @else
                <!-- School Slides -->
                <div class="schools-carousel overflow-hidden">
                    <div class="schools-carousel-inner flex transition-transform duration-500">
                        @foreach($partnerSchools as $index => $school)
                        <div class="school-slide flex-shrink-0 w-full {{ $index > 0 ? 'hidden' : '' }}" data-index="{{ $index }}">
                            <div class="bg-gradient-to-r from-primary to-red-900 rounded-lg overflow-hidden relative">
                                <div class="aspect-w-16 aspect-h-9 relative flex items-center justify-center p-8 bg-white">
                                    <!-- School logo with robust fallback -->
                                    <div class="school-logo-container flex items-center justify-center h-full w-full">
                                        @if($school->image_path && file_exists(public_path('storage/' . $school->image_path)))
                                            <img src="{{ asset('storage/' . $school->image_path) }}" 
                                                 alt="{{ $school->name }}" 
                                                 class="w-auto h-auto max-h-96 mx-auto object-contain" 
                                                 style="min-height: 250px; min-width: 350px;" 
                                                 onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                            <div class="hidden text-center">
                                                <img src="{{ asset('images/favicon.png') }}" 
                                                     alt="{{ $school->name }}" 
                                                     class="w-24 h-24 mx-auto mb-3">
                                                <p class="text-gray-700 font-semibold text-lg">{{ $school->name }}</p>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <img src="{{ asset('images/favicon.png') }}" 
                                                     alt="{{ $school->name }}" 
                                                     class="w-24 h-24 mx-auto mb-3">
                                                <p class="text-gray-700 font-semibold text-lg">{{ $school->name }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- School Name Badge -->
                                    <div class="absolute bottom-6 left-6">
                                        <div class="bg-red-700 text-white py-2 px-4 inline-block font-bold uppercase">
                                            {{ $school->name }}
                                        </div>
                                    </div>
                                    
                                    @if($school->website_url)
                                    <!-- Visit Website Button -->
                                    <div class="absolute top-6 right-6">
                                        <a href="{{ $school->website_url }}" target="_blank" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-md p-2 inline-block">
                                            <i class="fas fa-external-link-alt text-white"></i>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- Carousel Navigation Dots -->
                <div class="flex justify-center mt-6 space-x-2">
                    @foreach($partnerSchools as $index => $school)
                    <button class="school-nav-dot w-3 h-3 rounded-full {{ $index == 0 ? 'bg-[#950713]' : 'bg-gray-400' }}" data-index="{{ $index }}"></button>
                    @endforeach
                </div>
                
                <!-- School Carousel Script -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const slides = document.querySelectorAll('.school-slide');
                    const dots = document.querySelectorAll('.school-nav-dot');
                    const totalSlides = slides.length;
                    let currentIndex = 0;
                    let autoplayInterval;
                    
                    // Initialize first slide
                    if (slides.length > 0) {
                        slides[0].classList.remove('hidden');
                    }
                    
                    // Show slide by index
                    function showSlide(index) {
                        // Hide all slides
                        slides.forEach(slide => slide.classList.add('hidden'));
                        
                        // Show current slide
                        slides[index].classList.remove('hidden');
                        
                        // Update navigation dots
                        dots.forEach(dot => dot.classList.replace('bg-[#950713]', 'bg-gray-400'));
                        dots[index].classList.replace('bg-gray-400', 'bg-[#950713]');
                        
                        currentIndex = index;
                    }
                    
                    // Add click events to dots
                    dots.forEach(dot => {
                        dot.addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index'));
                            showSlide(index);
                            restartAutoplay();
                        });
                    });
                    
                    // Autoplay function
                    function startAutoplay() {
                        if (totalSlides > 1) {
                            autoplayInterval = setInterval(() => {
                                const nextIndex = (currentIndex + 1) % totalSlides;
                                showSlide(nextIndex);
                            }, 5000); // Change slide every 5 seconds
                        }
                    }
                    
                    // Restart autoplay
                    function restartAutoplay() {
                        clearInterval(autoplayInterval);
                        startAutoplay();
                    }
                    
                    // Start autoplay
                    startAutoplay();
                });
                </script>
                
                <!-- Carousel Navigation Arrows (only if more than one school) -->
                @if($partnerSchools->count() > 1)
                <div class="absolute top-1/2 -translate-y-1/2 left-0 right-0 flex justify-between px-4 z-10">
                    <button class="school-prev bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-chevron-left text-white"></i>
                    </button>
                    <button class="school-next bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-chevron-right text-white"></i>
                    </button>
                </div>
                @endif
                @endif
            </div>
        </div>
    </section>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Partner Schools Carousel functionality
        const slides = document.querySelectorAll('.school-slide');
        const dots = document.querySelectorAll('.school-nav-dot');
        const prevBtn = document.querySelector('.school-prev');
        const nextBtn = document.querySelector('.school-next');
        const totalSlides = slides.length;
        let currentSlide = 0;
        let slideInterval;
        
        // Only initialize if there are slides
        if (totalSlides > 0) {
            // Function to show a specific slide
            function showSlide(index) {
                // Hide all slides
                slides.forEach(slide => {
                    slide.classList.add('hidden');
                });
                
                // Show the current slide
                slides[index].classList.remove('hidden');
                
                // Update dots
                dots.forEach((dot, idx) => {
                    dot.classList.toggle('bg-cyan-400', idx === index);
                    dot.classList.toggle('bg-gray-400', idx !== index);
                });
            }
            
            // Go to previous slide
            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
                resetInterval();
            }
            
            // Go to next slide
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
                resetInterval();
            }
            
            // Reset the auto-advance interval
            function resetInterval() {
                if (slideInterval) clearInterval(slideInterval);
                if (totalSlides > 1) {
                    slideInterval = setInterval(nextSlide, 5000);
                }
            }
            
            // Add click events to dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                    resetInterval();
                });
            });
            
            // Add click events to navigation buttons
            if (prevBtn) prevBtn.addEventListener('click', prevSlide);
            if (nextBtn) nextBtn.addEventListener('click', nextSlide);
            
            // Initialize the carousel
            showSlide(currentSlide);
            
            // Auto-advance slides every 5 seconds if there's more than one slide
            if (totalSlides > 1) {
                resetInterval();
                
                // Pause on hover
                const carousel = document.querySelector('.schools-carousel');
                if (carousel) {
                    carousel.addEventListener('mouseenter', () => {
                        if (slideInterval) clearInterval(slideInterval);
                    });
                    
                    carousel.addEventListener('mouseleave', () => {
                        resetInterval();
                    });
                }
            }
        }
    });
    </script>

    <!-- Explore Our Events Section -->
    <section id="events" class="py-16" style="background-color: rgba(255, 203, 5, 0.25);">
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold text-primary mb-3">Explore Our Events</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Discover our wide range of events tailored to enhance your learning journey.
                </p>
            </div>
            
            <!-- Event Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                @php
                    // Get active events ordered by display_order
                    $events = \App\Models\Event::active()->ordered()->get();
                @endphp
                
                @forelse($events as $event)
                    <!-- Event Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                        <div class="relative">
                            @if($event->media_type == 'image')
                                <img class="max-w-full" src="{{ asset('storage/' . $event->media_path) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="relative w-full h-48 bg-gray-900">
                                    <video class="absolute inset-0 w-full h-full object-cover" poster="{{ asset('images/video-poster.jpg') }}" controls>
                                        <source src="{{ asset('storage/' . $event->media_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <span class="play-button bg-white bg-opacity-80 rounded-full p-3">
                                            <i class="fas fa-play text-primary text-xl"></i>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <!-- Enhanced Level Badge (Above Title) -->
                            <div class="mb-2">
                                @php
                                    // Determine icon based on level
                                    $levelIcon = 'fa-layer-group';
                                    $level = $event->level ?? 'All Levels';
                                    
                                    if (stripos($level, 'basic') !== false || stripos($level, 'beginner') !== false) {
                                        $levelIcon = 'fa-seedling';
                                    } elseif (stripos($level, 'intermediate') !== false) {
                                        $levelIcon = 'fa-chart-line';
                                    } elseif (stripos($level, 'advanced') !== false) {
                                        $levelIcon = 'fa-crown';
                                    } elseif (stripos($level, 'master') !== false) {
                                        $levelIcon = 'fa-award';
                                    }
                                    
                                    // Generate gradient based on level color
                                    $baseColor = $event->level_color ?? '#6366f1';
                                    $textColor = $baseColor == '#ffffff' ? '#000000' : '#ffffff';
                                @endphp
                                
                                <span class="text-[10px] px-3 py-1 rounded-full inline-flex items-center gap-1 whitespace-nowrap font-semibold tracking-wide transform transition-all duration-300 hover:scale-105 hover:shadow-md" 
                                      style="background: linear-gradient(145deg, {{ $baseColor }}, {{ $baseColor }}cc); color: {{ $textColor }}; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                    <i class="fas {{ $levelIcon }} mr-1 text-[8px]"></i>
                                    {{ $level }}
                                </span>
                            </div>
                            <!-- Event Title -->
                            <h3 class="text-xl font-bold text-primary mb-1">{{ $event->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                {{ $event->getShortDescription(100) }}
                            </p>
                            <div class="flex justify-between items-center">
                                @if($event->duration)
                                    <span class="text-sm text-gray-500">{{ $event->duration }}</span>
                                @else
                                    <span class="text-sm text-gray-500">Coming soon</span>
                                @endif
                                <a href="{{ route('events.public.show', $event->id) }}" class="text-sm text-primary hover:text-primary-dark flex items-center">
                                    Learn more 
                                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- No Events Message -->
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-500">No events available at the moment. Check back soon!</p>
                    </div>
                @endforelse
            </div>
        </div>
            
            <!-- View All Button -->
            <div class="text-center mt-10">
                @if($events->isNotEmpty())
                <a href="{{ route('events.gallery') }}" class="inline-block text-white py-3 px-6 rounded-md hover:opacity-90 transition duration-300" style="background-color: #FF00FF;">
                    View All Events 
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Why Young Experts Group Section -->
    <section id="about-us" class="py-16 bg-gray-50">
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto">
            <!-- Header -->
            <div class="text-center mb-14" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-4">
                    Why <span class="text-[#950713]">Young</span> <span class="bg-black text-yellow-400 px-2">Experts</span> <span class="text-cyan-400">Group</span> is the best Solution for Innovation
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Our comprehensive curriculum covers all key subjects with expert trainers and innovative methods.
                </p>
            </div>
            
            <!-- Program Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-8 max-w-6xl mx-auto">
                <!-- Coding -->
                <div class="text-center" data-aos="zoom-in" data-aos-delay="100">
                    <div class="bg-blue-100 w-24 h-24 mx-auto rounded-lg p-4 mb-4 flex items-center justify-center">
                        <img class="max-w-full" src="{{ asset('images/coding.png') }}" alt="Coding" class="w-16 h-16 object-contain">
                    </div>
                    <h3 class="text-[#950713] font-bold mb-2">Coding</h3>
                    <p class="text-gray-600 text-sm">
                        Learn programming skills through interactive and project-based learning
                    </p>
                </div>
                
                <!-- Digital Marketing -->
                <div class="text-center" data-aos="zoom-in" data-aos-delay="200">
                    <div class="bg-orange-100 w-24 h-24 mx-auto rounded-lg p-4 mb-4 flex items-center justify-center">
                        <img class="max-w-full" src="{{ asset('images/digital-marketing.png') }}" alt="Digital Marketing" class="w-16 h-16 object-contain">
                    </div>
                    <h3 class="text-[#950713] font-bold mb-2">Digital Marketing</h3>
                    <p class="text-gray-600 text-sm">
                        Develop business skills and strategies to bring ideas to market
                    </p>
                </div>
                
                <!-- Graphics Design -->
                <div class="text-center" data-aos="zoom-in" data-aos-delay="300">
                    <div class="bg-blue-100 w-24 h-24 mx-auto rounded-lg p-4 mb-4 flex items-center justify-center">
                        <img class="max-w-full" src="{{ asset('images/graphic-designing.png') }}" alt="Graphics Design" class="w-16 h-16 object-contain">
                    </div>
                    <h3 class="text-[#950713] font-bold mb-2">Graphics Design</h3>
                    <p class="text-gray-600 text-sm">
                        Explore creative design principles through hands-on projects
                    </p>
                </div>
                
                <!-- Entrepreneurship -->
                <div class="text-center" data-aos="zoom-in" data-aos-delay="400">
                    <div class="bg-blue-100 w-24 h-24 mx-auto rounded-lg p-4 mb-4 flex items-center justify-center">
                        <img class="max-w-full" src="{{ asset('images/Enterpreneurship.png') }}" alt="Entrepreneurship" class="w-16 h-16 object-contain">
                    </div>
                    <h3 class="text-[#950713] font-bold mb-2">Entrepreneurship</h3>
                    <p class="text-gray-600 text-sm">
                        Develop business skills and strategies to bring ideas to market
                    </p>
                </div>
                
                <!-- Artificial Intelligence -->
                <div class="text-center">
                    <div class="bg-yellow-100 w-24 h-24 mx-auto rounded-lg p-4 mb-4 flex items-center justify-center">
                        <img class="max-w-full" src="{{ asset('images/Artificial Intelligence.png') }}" alt="Artificial Intelligence" class="w-16 h-16 object-contain">
                    </div>
                    <h3 class="text-primary font-bold mb-2">Artificial Intelligence</h3>
                    <p class="text-gray-600 text-sm">
                        Create content for social media, analytics, and digital platforms
                    </p>
                </div>
                
                <!-- Creativity Workshops -->
                <div class="text-center">
                    <div class="bg-blue-100 w-24 h-24 mx-auto rounded-lg p-4 mb-4 flex items-center justify-center">
                        <img class="max-w-full" src="{{ asset('images/creativity-workshop.png') }}" alt="Creativity Workshops" class="w-16 h-16 object-contain">
                    </div>
                    <h3 class="text-primary font-bold mb-2">Creativity Workshops</h3>
                    <p class="text-gray-600 text-sm">
                        Discover how to turn creative ideas into practical solutions
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Partnered Schools Marquee Section -->
    <section id="partners" class="py-16 bg-gray-100 relative">
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto relative z-10">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-3 text-primary">
                    Partnered <span class="text-yellow-500">Schools</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    We're proud to partner with leading educational institutions to deliver exceptional learning experiences
                </p>
            </div>
            
            <!-- School Logos Marquee - Dynamic Implementation -->
            @php
                $schoolLogos = App\Models\SchoolLogo::where('is_active', true)
                    ->orderBy('display_order')
                    ->get();
            @endphp
            
            @if($schoolLogos->count() > 0)
                <div class="relative">
                    <div class="overflow-hidden">
                        <div class="flex items-center animate-marquee whitespace-nowrap">
                            <!-- First set of logos -->
                            @foreach($schoolLogos as $logo)
                                <div class="inline-block mx-3 sm:mx-5 md:mx-7">
                                    <img src="{{ asset('storage/' . $logo->logo_path) }}" 
                                         alt="{{ $logo->name }}" 
                                         style="height: 50px; max-width: 50px;" 
                                         class="w-auto object-contain opacity-70 hover:opacity-100 transition-opacity duration-300"
                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder-school.svg') }}'">
                                </div>
                            @endforeach
                            <!-- Second set for seamless looping -->
                            @foreach($schoolLogos as $logo)
                                <div class="inline-block mx-3 sm:mx-5 md:mx-7">
                                    <img src="{{ asset('storage/' . $logo->logo_path) }}" 
                                         alt="{{ $logo->name }}" 
                                         style="height: 50px; max-width: 50px;" 
                                         class="w-auto object-contain opacity-70 hover:opacity-100 transition-opacity duration-300"
                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder-school.svg') }}'">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <!-- Fallback if no logos are available -->
                <p class="text-center text-gray-500">Partner school logos will be displayed here.</p>
            @endif
        </div>
    </section>
    
    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .animate-marquee {
            display: inline-block;
            animation: marquee 30s linear infinite;
            white-space: nowrap;
        }
        
        .animate-marquee:hover {
            animation-play-state: paused;
        }
        
        .marquee-container {
            overflow: hidden;
            width: 100%;
        }
    </style>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 bg-white">
        <div class="w-full px-4 sm:px-6 md:container md:mx-auto mx-auto">
            <!-- Header -->
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-[#950713] mb-3">What Our Community Says</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Hear from our students, parents, and community members about their experiences with
                    our educational programs.
                </p>
            </div>
            
            @php
                // Get active testimonials ordered by display_order
                $testimonials = \App\Models\Testimonial::active()->ordered()->get();
            @endphp
            
            <!-- Testimonials Carousel -->
            <div class="relative">
                <div class="testimonials-container overflow-hidden">
                    <div class="testimonials-slider flex">
                        @forelse($testimonials as $key => $testimonial)
                            <div class="testimonial-card flex-shrink-0 w-full md:w-1/3 px-4">
                                <div class="bg-white rounded-lg border border-gray-200 p-6 h-full" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                    <!-- Person Info -->
                                    <div class="flex items-center mb-4">
                                        <div class="w-14 h-14 rounded-full overflow-hidden mr-4">
                                            <img class="max-w-full" src="{{ asset('storage/' . $testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-primary">{{ $testimonial->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $testimonial->role }}{{ $testimonial->institution ? ', ' . $testimonial->institution : '' }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Testimonial Content -->
                                    <p class="text-gray-700 mb-4">
                                        "{{ $testimonial->getShortContent(150) }}"
                                    </p>
                                    
                                    <!-- Read More Link -->
                                    <a href="#" class="text-neon-pink hover:underline inline-block mb-4" data-testimonial-id="{{ $testimonial->id }}">Read More →</a>
                                    
                                    <!-- Star Rating -->
                                    <div class="flex text-yellow-400">
                                        {!! $testimonial->getRatingStars() !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-full text-center py-8">
                                <p class="text-gray-500">No testimonials available at the moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                @if($testimonials->count() > 0)
                    <!-- Carousel Navigation -->
                    <div class="flex justify-center mt-8 items-center space-x-2">
                        <button class="carousel-nav-btn prev-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300">
                            <i class="fas fa-chevron-left text-gray-500"></i>
                        </button>
                        
                        <div class="carousel-dots flex space-x-2">
                            @for($i = 0; $i < ceil($testimonials->count() / 3); $i++)
                                <button class="dot w-3 h-3 rounded-full {{ $i == 0 ? 'bg-neon-pink' : 'bg-gray-300' }}" data-index="{{ $i }}"></button>
                            @endfor
                        </div>
                        
                        <button class="carousel-nav-btn next-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300">
                            <i class="fas fa-chevron-right text-gray-500"></i>
                        </button>
                    </div>
                @endif
            </div>
            
            <!-- Testimonial Modal for Read More -->
            <div id="testimonial-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 relative">
                    <button id="close-modal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    
                    <div id="modal-content" class="mt-4">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Testimonials carousel functionality
            const slider = document.querySelector('.testimonials-slider');
            const dots = document.querySelectorAll('.dot');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            const cardWidth = document.querySelector('.testimonial-card')?.offsetWidth;
            const totalSlides = {{ $testimonials->count() }};
            const slidesPerView = window.innerWidth >= 768 ? 3 : 1;
            const totalGroups = Math.ceil(totalSlides / slidesPerView);
            let currentGroup = 0;
            
            if (slider && totalSlides > 0) {
                // Update dots
                const updateDots = () => {
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('bg-neon-pink', index === currentGroup);
                        dot.classList.toggle('bg-gray-300', index !== currentGroup);
                    });
                };
                
                // Slide to group
                const slideToGroup = (index) => {
                    if (index < 0) index = totalGroups - 1;
                    if (index >= totalGroups) index = 0;
                    
                    currentGroup = index;
                    const translateValue = -index * (slidesPerView * cardWidth);
                    slider.style.transform = `translateX(${translateValue}px)`;
                    updateDots();
                };
                
                // Attach click events to dots
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => slideToGroup(index));
                });
                
                // Attach click events to prev/next buttons
                if (prevBtn) {
                    prevBtn.addEventListener('click', () => slideToGroup(currentGroup - 1));
                }
                
                if (nextBtn) {
                    nextBtn.addEventListener('click', () => slideToGroup(currentGroup + 1));
                }
                
                // Initialize slider transition
                slider.style.transition = 'transform 0.5s ease';
                
                // Testimonial modal functionality
                const modal = document.getElementById('testimonial-modal');
                const modalContent = document.getElementById('modal-content');
                const closeModal = document.getElementById('close-modal');
                const readMoreLinks = document.querySelectorAll('[data-testimonial-id]');
                
                // Data for testimonials
                const testimonialData = {
                    @foreach($testimonials as $testimonial)
                        {{ $testimonial->id }}: {
                            name: "{{ $testimonial->name }}",
                            role: "{{ $testimonial->role }}",
                            institution: "{{ $testimonial->institution }}",
                            content: "{{ $testimonial->content }}",
                            image: "{{ asset('storage/' . $testimonial->image_path) }}",
                            rating: {{ $testimonial->rating }}
                        },
                    @endforeach
                };
                
                // Open modal with testimonial content
                readMoreLinks.forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const id = link.getAttribute('data-testimonial-id');
                        const data = testimonialData[id];
                        
                        if (data) {
                            // Generate star rating HTML
                            let stars = '';
                            for (let i = 1; i <= 5; i++) {
                                stars += `<i class="fas ${i <= data.rating ? 'fa-star' : 'far fa-star'}"></i>`;
                            }
                            
                            // Populate modal content
                            modalContent.innerHTML = `
                                <div class="flex items-center mb-6">
                                    <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
                                        <img class="max-w-[40px] sm:max-w-[60px] md:max-w-[100px]" src="${data.image}" alt="${data.name}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-primary">${data.name}</h3>
                                        <p class="text-sm text-gray-600">${data.role}${data.institution ? ', ' + data.institution : ''}</p>
                                        <div class="flex text-yellow-400 mt-1">${stars}</div>
                                    </div>
                                </div>
                                <div class="prose max-w-none">
                                    <p class="text-gray-700">"${data.content}"</p>
                                </div>
                            `;
                            
                            // Show modal
                            modal.classList.remove('hidden');
                            document.body.style.overflow = 'hidden';  // Prevent scrolling
                        }
                    });
                });
                
                // Close modal
                if (closeModal) {
                    closeModal.addEventListener('click', () => {
                        modal.classList.add('hidden');
                        document.body.style.overflow = '';  // Restore scrolling
                    });
                }
                
                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = '';  // Restore scrolling
                    }
                });
            }
        });
    </script>
    @endpush

@endsection

@section('scripts')
    <script>
    // --- Parallax Effect for Hero ---
    document.addEventListener('scroll', function() {
      var bg = document.getElementById('hero-parallax-bg');
      if (bg) {
        var scrollY = window.scrollY;
        var speed = 0.3; // Adjust for faster/slower parallax
        var yPos = -(scrollY * speed);
        bg.style.transform = 'translate3d(0, ' + yPos + 'px, 0)';
        
        // Fade out as you scroll down
        var opacity = 1 - (scrollY / 1000);
        opacity = Math.max(opacity, 0.3); // Don't let it fade completely
        var img = document.querySelector('.parallax-img');
        if (img) {
          img.style.opacity = opacity;
        }
      }
    });
    
    // Initialize once on page load for immediate effect
    document.addEventListener('DOMContentLoaded', function() {
      var bg = document.getElementById('hero-parallax-bg');
      if (bg) {
        bg.style.transform = 'translate3d(0, 0, 0)';
      }
    });
    
    // Registration dropdowns functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Helper function to handle dropdown functionality
        function setupDropdown(buttonId, menuId, buttonSelector) {
            const button = buttonId ? document.getElementById(buttonId) : buttonSelector;
            const menu = buttonId ? document.getElementById(menuId) : buttonSelector && buttonSelector.nextElementSibling;
            const chevronIcon = button ? button.querySelector('.fa-chevron-down') : null;
            
            if (button && menu) {
                // Toggle dropdown on button click
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    menu.classList.toggle('hidden');
                    if (chevronIcon) {
                        chevronIcon.classList.toggle('rotate-180');
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!button.contains(event.target) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                        if (chevronIcon) {
                            chevronIcon.classList.remove('rotate-180');
                        }
                    }
                });
            }
        }
        
        // Setup all dropdowns
        setupDropdown('registerDropdownButton', 'registerDropdownMenu');
        setupDropdown('happeningsRegisterBtn', 'happeningsDropdownMenu');
        
        // Handle hero section dropdown
        const heroButtons = document.querySelectorAll('.heroRegisterBtn');
        heroButtons.forEach(button => {
            const menu = button.nextElementSibling;
            const chevron = button.querySelector('.fa-chevron-down');
            
            if (button && menu) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    menu.classList.toggle('hidden');
                    if (chevron) {
                        chevron.classList.toggle('rotate-180');
                    }
                });
                
                document.addEventListener('click', function(event) {
                    if (!button.contains(event.target) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                        if (chevron) {
                            chevron.classList.remove('rotate-180');
                        }
                    }
                });
            }
        });
    });
    
    // Toggle FAQ accordion items
    function toggleFaq(element) {
        // Get the answer panel (next sibling after the question)
        const answer = element.nextElementSibling;
        const icon = element.querySelector('.faq-icon i');
        
        // Toggle the display of the answer
        if (answer.style.display === 'none' || !answer.style.display) {
            answer.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
            element.classList.add('bg-gray-50');
        } else {
            answer.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
            element.classList.remove('bg-gray-50');
        }
    }

    $(document).ready(function() {
        // Hero Carousel functionality with smooth transitions
        $('.carousel-dot').click(function() {
            if (isTransitioning) return; // Prevent rapid clicking
            
            isTransitioning = true;
            setTimeout(() => { isTransitioning = false; }, 1000); // Match transition duration
            
            const slideId = $(this).data('slide');
            
            // Update dots immediately
            $('.carousel-dot').removeClass('active');
            $(this).addClass('active');
            
            // Smoothly transition between slides
            $('.carousel-item').removeClass('active');
            $('#' + slideId).addClass('active');
        });
        
        // Hero Carousel Navigation Arrows
        $('.carousel-prev').click(function() {
            // Don't proceed if a transition is already happening
            if (isTransitioning) return;
            
            isTransitioning = true;
            setTimeout(() => { isTransitioning = false; }, 1000); // Match transition duration
            
            const activeDot = $('.carousel-dot.active');
            let prevDot = activeDot.prev('.carousel-dot');
            
            // If no previous dot, go to the last dot (loop back)
            if (prevDot.length === 0) {
                prevDot = $('.carousel-dot:last');
            }
            
            // Update dots immediately
            $('.carousel-dot').removeClass('active');
            prevDot.addClass('active');
            
            // Get slide ID and update slides with smooth transition
            const slideId = prevDot.data('slide');
            $('.carousel-item').removeClass('active');
            $('#' + slideId).addClass('active');
        });
        
        $('.carousel-next').click(function() {
            // Don't proceed if a transition is already happening
            if (isTransitioning) return;
            
            isTransitioning = true;
            setTimeout(() => { isTransitioning = false; }, 1000); // Match transition duration
            
            const activeDot = $('.carousel-dot.active');
            let nextDot = activeDot.next('.carousel-dot');
            
            // If no next dot, go to the first dot (loop)
            if (nextDot.length === 0) {
                nextDot = $('.carousel-dot:first');
            }
            
            // Update dots immediately
            $('.carousel-dot').removeClass('active');
            nextDot.addClass('active');
            
            // Get slide ID and update slides with smooth transition
            const slideId = nextDot.data('slide');
            $('.carousel-item').removeClass('active');
            $('#' + slideId).addClass('active');
        });
        
        // Auto-rotate carousel every 6 seconds with smooth transitions
        let carouselInterval;
        let isTransitioning = false;
        
        function startCarouselAutoplay() {
            carouselInterval = setInterval(function() {
                if (!isTransitioning && $('.carousel-item').length > 1) {
                    $('.carousel-next').click();
                }
            }, 6000);
        }
        
        function stopCarouselAutoplay() {
            clearInterval(carouselInterval);
        }
        
        // Start autoplay on page load if there are hero sections
        if ($('.carousel-dot').length > 1) {
            startCarouselAutoplay();
        }
        
        // Pause autoplay when user interacts with carousel
        $('.carousel-dot, .carousel-prev, .carousel-next').on('mouseenter', function() {
            stopCarouselAutoplay();
        }).on('mouseleave', function() {
            // Restart autoplay shortly after user stops interaction
            setTimeout(startCarouselAutoplay, 2000);
        });
        
        // Also handle click events to reset the timer
        $('.carousel-dot, .carousel-prev, .carousel-next').on('click', function() {
            stopCarouselAutoplay();
            // Restart autoplay after user interaction
            setTimeout(startCarouselAutoplay, 6000);
        });
        
        // Category toggle functionality for FAQs
        $('.categories .border').click(function() {
            // Handle category selection logic here
            console.log('Category clicked');
        });
        
        // Testimonials Carousel functionality
        let currentSlide = 0;
        const testimonialCards = $('.testimonial-card');
        const totalSlides = Math.ceil(testimonialCards.length / 3);
        const dots = $('.carousel-dots button');
        
        // Initialize dots
        dots.eq(0).addClass('active');
        
        // Previous button
        $('.carousel-nav-btn:first-child').click(function() {
            if (currentSlide > 0) {
                currentSlide--;
                updateTestimonialsSlider();
            }
        });
        
        // Next button
        $('.carousel-nav-btn:last-child').click(function() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateTestimonialsSlider();
            }
        });
        
        // Dot navigation
        dots.click(function() {
            currentSlide = $(this).index();
            updateTestimonialsSlider();
        });
        
        function updateTestimonialsSlider() {
            const translateValue = -currentSlide * 100 + '%';
            $('.testimonials-slider').css('transform', 'translateX(' + translateValue + ')');
            
            // Update active dot
            dots.removeClass('bg-neon-pink').addClass('bg-gray-300');
            dots.eq(currentSlide).removeClass('bg-gray-300').addClass('bg-neon-pink');
        }
    });

    // Registration dropdowns functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Helper function to handle dropdown functionality
        function setupDropdown(buttonId, menuId, buttonSelector) {
            const button = buttonId ? document.getElementById(buttonId) : buttonSelector;
            const menu = buttonId ? document.getElementById(menuId) : buttonSelector && buttonSelector.nextElementSibling;
            const chevronIcon = button ? button.querySelector('.fa-chevron-down') : null;
            
            if (button && menu) {
                // Toggle dropdown on button click
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    menu.classList.toggle('hidden');
                    if (chevronIcon) {
                        chevronIcon.classList.toggle('rotate-180');
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!button.contains(event.target) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                        if (chevronIcon) {
                            chevronIcon.classList.remove('rotate-180');
                        }
                    }
                });
            }
        }
        
        // Setup all dropdowns
        setupDropdown('registerDropdownButton', 'registerDropdownMenu');
        setupDropdown('happeningsRegisterBtn', 'happeningsDropdownMenu');
        
        // Handle hero section dropdown
        const heroButtons = document.querySelectorAll('.heroRegisterBtn');
        heroButtons.forEach(button => {
            const menu = button.nextElementSibling;
            const chevron = button.querySelector('.fa-chevron-down');
            
            if (button && menu) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    menu.classList.toggle('hidden');
                    if (chevron) {
                        chevron.classList.toggle('rotate-180');
                    }
                });
                
                document.addEventListener('click', function(event) {
                    if (!button.contains(event.target) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                        if (chevron) {
                            chevron.classList.remove('rotate-180');
                        }
                    }
                });
            }
        });
    });
    
    // Toggle FAQ accordion items
    function toggleFaq(element) {
        // Get the answer panel (next sibling after the question)
        const answer = element.nextElementSibling;
        const icon = element.querySelector('.faq-icon i');
        
        // Toggle the display of the answer
        if (answer.style.display === 'none' || !answer.style.display) {
            answer.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
            element.classList.add('bg-gray-50');
        } else {
            answer.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
            element.classList.remove('bg-gray-50');
        }
    }

    $(document).ready(function() {
        // Hero Carousel functionality with smooth transitions
        $('.carousel-dot').click(function() {
            if (isTransitioning) return; // Prevent rapid clicking
            
            isTransitioning = true;
            setTimeout(() => { isTransitioning = false; }, 1000); // Match transition duration
            
            const slideId = $(this).data('slide');
            
            // Update dots immediately
            $('.carousel-dot').removeClass('active');
            $(this).addClass('active');
            
            // Smoothly transition between slides
            $('.carousel-item').removeClass('active');
            $('#' + slideId).addClass('active');
            });
            
        // Hero Carousel Navigation Arrows
        $('.carousel-prev').click(function() {
            // Don't proceed if a transition is already happening
            if (isTransitioning) return;
            
            isTransitioning = true;
            setTimeout(() => { isTransitioning = false; }, 1000); // Match transition duration
            
            const activeDot = $('.carousel-dot.active');
            let prevDot = activeDot.prev('.carousel-dot');
            
            // If no previous dot, go to the last dot (loop back)
            if (prevDot.length === 0) {
                prevDot = $('.carousel-dot:last');
            }
            
            // Update dots immediately
            $('.carousel-dot').removeClass('active');
            prevDot.addClass('active');
            
            // Get slide ID and update slides with smooth transition
            const slideId = prevDot.data('slide');
            $('.carousel-item').removeClass('active');
            $('#' + slideId).addClass('active');
            });
            
        $('.carousel-next').click(function() {
            // Don't proceed if a transition is already happening
            if (isTransitioning) return;
            
            isTransitioning = true;
            setTimeout(() => { isTransitioning = false; }, 1000); // Match transition duration
            
            const activeDot = $('.carousel-dot.active');
            let nextDot = activeDot.next('.carousel-dot');
            
            // If no next dot, go to the first dot (loop)
            if (nextDot.length === 0) {
                nextDot = $('.carousel-dot:first');
            }
            
            // Update dots immediately
            $('.carousel-dot').removeClass('active');
            nextDot.addClass('active');
            
            // Get slide ID and update slides with smooth transition
            const slideId = nextDot.data('slide');
            $('.carousel-item').removeClass('active');
            $('#' + slideId).addClass('active');
            });
            
        // Auto-rotate carousel every 6 seconds with smooth transitions
        let carouselInterval;
        let isTransitioning = false;
        
        function startCarouselAutoplay() {
            carouselInterval = setInterval(function() {
                if (!isTransitioning && $('.carousel-item').length > 1) {
                    $('.carousel-next').click();
                }
            }, 6000);
        }
        
        function stopCarouselAutoplay() {
            clearInterval(carouselInterval);
        }
        
        // Start autoplay on page load if there are hero sections
        if ($('.carousel-dot').length > 1) {
            startCarouselAutoplay();
        }
            
        // Pause autoplay when user interacts with carousel
        $('.carousel-dot, .carousel-prev, .carousel-next').on('mouseenter', function() {
            stopCarouselAutoplay();
        }).on('mouseleave', function() {
            // Restart autoplay shortly after user stops interaction
            setTimeout(startCarouselAutoplay, 2000);
        });
        
        // Also handle click events to reset the timer
        $('.carousel-dot, .carousel-prev, .carousel-next').on('click', function() {
            stopCarouselAutoplay();
            // Restart autoplay after user interaction
            setTimeout(startCarouselAutoplay, 6000);
        });
            
        // Category toggle functionality for FAQs
        $('.categories .border').click(function() {
            // Handle category selection logic here
            console.log('Category clicked');
        });
        
        // Testimonials Carousel functionality
        let currentSlide = 0;
        const testimonialCards = $('.testimonial-card');
        const totalSlides = Math.ceil(testimonialCards.length / 3);
        const dots = $('.carousel-dots button');
        
        // Initialize dots
        dots.eq(0).addClass('active');
        
        // Previous button
        $('.carousel-nav-btn:first-child').click(function() {
            if (currentSlide > 0) {
                currentSlide--;
                updateTestimonialsSlider();
            }
        });
        
        // Next button
        $('.carousel-nav-btn:last-child').click(function() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateTestimonialsSlider();
            }
        });
            
        // Dot navigation
        dots.click(function() {
            currentSlide = $(this).index();
            updateTestimonialsSlider();
        });
        
        function updateTestimonialsSlider() {
            const translateValue = -currentSlide * 100 + '%';
            $('.testimonials-slider').css('transform', 'translateX(' + translateValue + ')');
            
            // Update active dot
            dots.removeClass('bg-neon-pink').addClass('bg-gray-300');
            dots.eq(currentSlide).removeClass('bg-gray-300').addClass('bg-neon-pink');
        }
        });
    </script>

    <!-- AOS Library and Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: false,
                mirror: false,
                offset: 100,
                delay: 100
            });
        });
        
        // Direct jQuery implementation for happenings dropdown
        $(document).ready(function() {
            // Remove any existing event handlers to prevent conflicts
            $(document).off('click', '#happeningsRegisterBtn');
            
            // Direct implementation with jQuery
            $('#happeningsRegisterBtn').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Force display:block instead of toggling classes for more reliable behavior
                var menu = $('#happeningsDropdownMenu');
                var chevron = $('#happeningsChevron');
                
                if (menu.is(':visible')) {
                    menu.hide().addClass('hidden');
                    chevron.removeClass('rotate-180');
                } else {
                    menu.show().removeClass('hidden');
                    chevron.addClass('rotate-180');
                    console.log('Dropdown shown via jQuery');
                }
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#happeningsDropdownContainer').length) {
                    $('#happeningsDropdownMenu').hide().addClass('hidden');
                    $('#happeningsChevron').removeClass('rotate-180');
                }
            });
            
            // Debug information
            console.log('jQuery dropdown handlers initialized:', {
                buttonExists: $('#happeningsRegisterBtn').length > 0,
                menuExists: $('#happeningsDropdownMenu').length > 0
            });
        });
    </script>
</div>
@endsection

