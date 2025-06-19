<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Registration - Young Experts Group</title>
    <link href="{{ asset('css/sticky-headers.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e11d48',
                        secondary: '#f59e0b'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6;
        }
        
        /* Animation classes */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Carousel styling */
        .carousel-container {
            position: relative;
            overflow: hidden;
        }
        
        .carousel-item {
            display: none;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            transition: opacity 0.6s ease-in-out;
            opacity: 0;
        }
        
        .carousel-item.active {
            display: block;
            opacity: 1;
            z-index: 1;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c50000;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a30000;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
        }
    </style>
</head>
<body class="has-sticky-header">
    <header class="bg-white text-gray-800 shadow-md sticky-header">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span class="text-primary text-xl font-medium">Young</span>
                    <span class="text-secondary mx-1 text-xl font-medium">Experts</span>
                    <span class="text-primary text-xl font-medium">Group</span>
                </a>
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="text-primary hover:text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                <!-- Desktop navigation -->
                <nav class="hidden md:flex space-x-6 items-center">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('school.register') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-school mr-1"></i> School Registration
                    </a>
                    <a href="{{ url('/students/register') }}" class="text-primary border-b-2 border-secondary flex items-center">
                        <i class="fas fa-user-graduate mr-1"></i> Student Registration
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </nav>
            </div>
            <!-- Mobile navigation menu (hidden by default) -->
            <div id="mobile-menu" class="hidden md:hidden mt-3 pb-2 bg-white border-t border-gray-200">
                <div class="flex flex-col space-y-2">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-home mr-2 w-5 text-center"></i> Home
                    </a>
                    <a href="{{ route('school.register') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-school mr-2 w-5 text-center"></i> School Registration
                    </a>
                    <a href="{{ url('/students/register') }}" class="text-primary py-2 bg-gray-100 px-2 rounded flex items-center">
                        <i class="fas fa-user-graduate mr-2 w-5 text-center"></i> Student Registration
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center mt-2">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <main class="bg-gray-50 min-h-screen py-8">
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Page Title -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Student Registration</h1>
            <p class="text-gray-600">Join the Young Experts Group learning programs</p>
        </div>
        
        <!-- Two-column layout -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row">
                <!-- Left Column - Educational IT Carousel -->
                <div class="md:w-5/12">
                    <div class="carousel-container h-full min-h-[600px] relative">
                        <!-- Carousel items -->
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80" alt="Educational Technology" class="w-full h-full object-cover">
                        </div>

                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80" alt="Coding Education" class="w-full h-full object-cover">
                        </div>
                        
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80" alt="IT Skills Development" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Carousel controls -->
                        <div class="absolute top-1/2 left-0 transform -translate-y-1/2 flex justify-between w-full px-4">
                            <button id="carousel-prev" class="bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75 focus:outline-none">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button id="carousel-next" class="bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75 focus:outline-none">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        
                        <!-- Carousel indicators -->
                        <div class="absolute bottom-14 left-0 right-0 flex justify-center space-x-2">
                            <button class="carousel-indicator active w-3 h-3 bg-white rounded-full focus:outline-none"></button>
                            <button class="carousel-indicator w-3 h-3 bg-white bg-opacity-50 rounded-full focus:outline-none"></button>
                            <button class="carousel-indicator w-3 h-3 bg-white bg-opacity-50 rounded-full focus:outline-none"></button>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Form -->
                <div class="md:w-7/12 border-l border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary to-red-700 text-white">
                        <h2 class="text-xl font-bold">Student Registration</h2>
                        <p class="text-sm text-white text-opacity-90">Step 1: Select Program Type</p>
                    </div>
                    
                    <div class="p-6 max-h-[600px] overflow-y-auto custom-scrollbar">

                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <span class="font-semibold">1</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Which program would you like to enroll in?</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Please select one of the following program types.</p>
                </div>
                
                <form action="{{ route('student.registration.process_step1') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($programTypes as $programType)
                        <div class="border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="radio" name="program_type_id" id="program_{{ $programType->id }}" value="{{ $programType->id }}" class="w-5 h-5 text-primary focus:ring-primary">
                                </div>
                                <label for="program_{{ $programType->id }}" class="ml-3 cursor-pointer flex-grow">
                                    <h3 class="font-medium text-gray-800">{{ $programType->name }}</h3>
                                    @if(isset($programType->description))
                                    <p class="text-gray-600 text-sm">{{ $programType->description }}</p>
                                    @endif
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @error('program_type_id')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    
                    <div class="mt-8">
                        <button type="submit" class="bg-gradient-to-r from-primary to-red-700 hover:from-primary-dark hover:to-red-800 text-white font-medium py-3 px-6 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50 shadow-md hover:shadow-lg flex items-center">
                            <i class="fas fa-arrow-right mr-2"></i> Continue to Next Step
                        </button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </main>

    <footer class="bg-gray-800 text-white mt-10">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center">
                <p>© {{ date('Y') }} Young Experts Group. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Program option selection
            const programOptions = document.querySelectorAll('.program-option');
            
            programOptions.forEach(option => {
                const radio = option.querySelector('.program-radio');
                const radioCircle = option.querySelector('.program-radio-circle');
                const radioDot = option.querySelector('.program-radio-dot');
                
                option.addEventListener('click', function() {
                    // Reset all options
                    programOptions.forEach(opt => {
                        opt.classList.remove('border-primary', 'bg-primary', 'bg-opacity-5');
                        opt.querySelector('.program-radio').checked = false;
                        opt.querySelector('.program-radio-dot').classList.add('hidden');
                    });
                    
                    // Select current option
                    option.classList.add('border-primary', 'bg-primary', 'bg-opacity-5');
                    radio.checked = true;
                    radioDot.classList.remove('hidden');
                });
            });
            
            // Carousel functionality
            const carouselItems = document.querySelectorAll('.carousel-item');
            const indicators = document.querySelectorAll('.carousel-indicator');
            const prevButton = document.getElementById('carousel-prev');
            const nextButton = document.getElementById('carousel-next');
            let currentIndex = 0;
            const totalItems = carouselItems.length;
            let autoplayInterval;
            
            // Function to show a specific slide
            function showSlide(index) {
                // Make sure index is within bounds
                if (index >= totalItems) index = 0;
                if (index < 0) index = totalItems - 1;
                
                currentIndex = index;
                
                // Hide all slides
                carouselItems.forEach(item => {
                    item.classList.remove('active');
                });
                
                // Update indicators
                indicators.forEach(indicator => {
                    indicator.classList.remove('active');
                    indicator.classList.add('bg-opacity-50');
                });
                
                // Show the selected slide and update indicator
                carouselItems[currentIndex].classList.add('active');
                indicators[currentIndex].classList.add('active');
                indicators[currentIndex].classList.remove('bg-opacity-50');
            }
            
            // Next slide function
            function nextSlide() {
                showSlide(currentIndex + 1);
            }
            
            // Previous slide function
            function prevSlide() {
                showSlide(currentIndex - 1);
            }
            
            // Set up event listeners
            if (prevButton && nextButton) {
                nextButton.addEventListener('click', nextSlide);
                prevButton.addEventListener('click', prevSlide);
            }
            
            // Set up indicator clicks
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => showSlide(index));
            });
            
            // Set up autoplay
            function startAutoplay() {
                stopAutoplay(); // Clear any existing interval first
                autoplayInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
            }
            
            function stopAutoplay() {
                if (autoplayInterval) clearInterval(autoplayInterval);
            }
            
            // Start autoplay
            startAutoplay();
            
            // Pause autoplay on hover
            const carouselContainer = document.querySelector('.carousel-container');
            if (carouselContainer) {
                carouselContainer.addEventListener('mouseenter', stopAutoplay);
                carouselContainer.addEventListener('mouseleave', startAutoplay);
            }
        });
    </script>
</body>
</html>
