<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="{{ asset('css/sticky-headers.css') }}" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Registration - Step 2 - Young Experts Group</title>
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
                        <p class="text-sm text-white text-opacity-90">Step 2: Select Your School</p>
                    </div>
                    
                    <div class="p-6 max-h-[600px] overflow-y-auto custom-scrollbar">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <span class="font-semibold">2</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Select Your School</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Please select your school from the list or enter your school name if not found.</p>
                </div>
                
                <form action="{{ route('student.registration.process_step2_inschool') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">School Information</label>
                        
                        <!-- Option tabs -->
                        <div class="flex border-b border-gray-200 mb-4">
                            <button type="button" id="select-school-tab" 
                                class="py-2 px-4 border-b-2 border-primary text-primary font-medium text-sm focus:outline-none"
                                onclick="switchTab('select')">
                                Select from List
                            </button>
                            <button type="button" id="enter-school-tab" 
                                class="py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm focus:outline-none"
                                onclick="switchTab('enter')">
                                Enter Manually
                            </button>
                        </div>
                        
                        <!-- Select school option -->
                        <div id="select-school-content" class="animate-fade-in">
                            <select id="school_id" name="school_id" class="mt-1 block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md">
                                <option value="">-- Select School --</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Enter school manually option -->
                        <div id="enter-school-content" class="hidden animate-fade-in">
                            <input type="text" id="school_name" name="school_name" placeholder="Enter your school name" 
                                class="mt-1 block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md">
                            <p class="text-sm text-gray-500 mt-1">If your school is not in our list, please enter the name above.</p>
                        </div>
                        
                        @error('school_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        
                        @error('school_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mt-8 flex justify-between">
                        <a href="/students/register" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 shadow-md hover:shadow-lg flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-primary to-red-700 hover:from-primary-dark hover:to-red-800 text-white font-medium py-3 px-6 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50 shadow-md hover:shadow-lg flex items-center">
                            Continue <i class="fas fa-arrow-right ml-2"></i>
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
                <p>&copy; {{ date('Y') }} Young Experts Group. All rights reserved.</p>
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
            
            // School selection tab functionality
            function switchTab(tab) {
                // Update tab styles
                if (tab === 'select') {
                    document.getElementById('select-school-tab').classList.add('border-primary', 'text-primary');
                    document.getElementById('select-school-tab').classList.remove('border-transparent', 'text-gray-500');
                    document.getElementById('enter-school-tab').classList.add('border-transparent', 'text-gray-500');
                    document.getElementById('enter-school-tab').classList.remove('border-primary', 'text-primary');
                    
                    // Show select content, hide manual entry
                    document.getElementById('select-school-content').classList.remove('hidden');
                    document.getElementById('enter-school-content').classList.add('hidden');
                    
                    // Enable select field and disable input field for form submission
                    document.getElementById('school_id').setAttribute('required', '');
                    document.getElementById('school_name').removeAttribute('required');
                    document.getElementById('school_name').value = '';
                } else {
                    document.getElementById('enter-school-tab').classList.add('border-primary', 'text-primary');
                    document.getElementById('enter-school-tab').classList.remove('border-transparent', 'text-gray-500');
                    document.getElementById('select-school-tab').classList.add('border-transparent', 'text-gray-500');
                    document.getElementById('select-school-tab').classList.remove('border-primary', 'text-primary');
                    
                    // Show manual entry, hide select content
                    document.getElementById('enter-school-content').classList.remove('hidden');
                    document.getElementById('select-school-content').classList.add('hidden');
                    
                    // Enable input field and disable select field for form submission
                    document.getElementById('school_name').setAttribute('required', '');
                    document.getElementById('school_id').removeAttribute('required');
                    document.getElementById('school_id').value = '';
                }
            }
        });
</script>
</body>
</html>
