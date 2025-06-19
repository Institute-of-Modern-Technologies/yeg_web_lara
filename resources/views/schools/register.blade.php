<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>School Registration - Young Experts Group</title>
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
            transition: opacity 1s ease-in-out;
        }
        
        .carousel-item.active {
            display: block;
            opacity: 1;
        }
        
        .carousel-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 1rem;
            text-align: center;
        }
        
        /* Form styling enhancements */
        .form-card {
            transition: all 0.3s ease;
        }
        
        /* Custom scrollbar styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e11d48;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1123d;
        }
        
        /* Input field styling */
        input[type="text"],
        input[type="email"],
        input[type="number"] {
            transition: all 0.3s ease;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 0.75rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus {
            border-color: #e11d48;
            box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.2);
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
                    <a href="{{ route('school.register') }}" class="text-primary border-b-2 border-secondary flex items-center">
                        <i class="fas fa-school mr-1"></i> School Registration
                    </a>
                    <a href="{{ url('/students/register') }}" class="text-gray-700 hover:text-primary flex items-center">
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
                    <a href="{{ route('school.register') }}" class="text-primary py-2 bg-gray-100 px-2 rounded flex items-center">
                        <i class="fas fa-school mr-2 w-5 text-center"></i> School Registration
                    </a>
                    <a href="{{ url('/students/register') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-user-graduate mr-2 w-5 text-center"></i> Student Registration
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center mt-2">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">School Registration</h1>
                <p class="text-gray-600">Join the Young Experts Group network by registering your school below</p>
            </div>
            
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Two-column layout -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
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

                    <!-- Right Column - Registration Form -->
                    <div class="md:w-7/12 border-l border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary to-red-700 text-white">
                            <h2 class="font-semibold text-lg flex items-center">
                                <i class="fas fa-school mr-2"></i>
                                <span>School Information</span>
                            </h2>
                        </div>
                        
                        <div class="p-6 h-[600px] overflow-y-auto custom-scrollbar">
                    <form action="{{ route('school.register.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- School Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">School Name <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('name') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                    <input type="text" name="name" id="name" class="w-full py-2 px-3 border-0 focus:ring-0 focus:outline-none" value="{{ old('name') }}" required placeholder="Enter school name">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Phone Number -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('phone') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" name="phone" id="phone" class="w-full py-2 px-3 border-0 focus:ring-0 focus:outline-none" value="{{ old('phone') }}" required placeholder="Enter phone number">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email (Optional) -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-gray-400">(Optional)</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('email') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" id="email" class="w-full py-2 px-3 border-0 focus:ring-0 focus:outline-none" value="{{ old('email') }}" placeholder="Enter email address">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('location') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" name="location" id="location" class="w-full py-2 px-3 border-0 focus:ring-0 focus:outline-none" value="{{ old('location') }}" required placeholder="Enter school location">
                                </div>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- GPS Coordinates (Optional) -->
                            <div>
                                <label for="gps_coordinates" class="block text-sm font-medium text-gray-700 mb-1">GPS Coordinates <span class="text-gray-400">(Optional)</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('gps_coordinates') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-map-pin text-gray-400"></i>
                                    </div>
                                    <input type="text" name="gps_coordinates" id="gps_coordinates" class="w-full py-2 px-3 border-0 focus:ring-0 focus:outline-none" value="{{ old('gps_coordinates') }}" placeholder="e.g., 5.6037, -0.1870">
                                </div>
                                @error('gps_coordinates')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Owner Name -->
                            <div>
                                <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">Owner Name <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('owner_name') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" name="owner_name" id="owner_name" class="w-full py-2 px-3 border-0 focus:ring-0 focus:outline-none" value="{{ old('owner_name') }}" required placeholder="Enter owner name">
                                </div>
                                @error('owner_name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Average Students -->
                            <div>
                                <label for="average_students" class="block text-sm font-medium text-gray-700 mb-1">Average Number of Students <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('average_students') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-users text-gray-400"></i>
                                    </div>
                                    <input type="number" name="average_students" id="average_students" class="w-full py-2 px-3 border-0 focus:ring-0 focus:outline-none" value="{{ old('average_students') }}" required placeholder="Enter student count">
                                </div>
                                @error('average_students')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Logo Upload -->
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">School Logo <span class="text-gray-400">(Optional)</span></label>
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-grow">
                                        <input type="file" name="logo" id="logo" class="hidden" accept="image/*">
                                        <label for="logo" class="flex items-center justify-center w-full py-2 px-4 border border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-cloud-upload-alt text-primary mr-2"></i>
                                            <span class="text-sm text-gray-700">Choose file</span>
                                        </label>
                                    </div>
                                    <div class="shrink-0 h-12 w-12 rounded-lg border border-gray-200 overflow-hidden bg-gray-100">
                                        <img id="logo-preview" class="h-full w-full object-contain" src="{{ asset('images/placeholder.png') }}" alt="Logo preview">
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500" id="file-name">No file selected</p>
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <a href="{{ url('/') }}" class="mr-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-primary to-red-700 text-white rounded-lg hover:from-red-700 hover:to-primary transition-all duration-300 shadow-md flex items-center transform hover:-translate-y-0.5">
                                <i class="fas fa-paper-plane mr-2"></i> Submit Registration
                            </button>
                        </div>
                    </form>
                </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Information Notes -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-blue-800 font-semibold flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Important Information
                </h3>
                <ul class="mt-2 space-y-1 text-sm text-blue-700 list-disc list-inside">
                    <li>Your school registration will be reviewed by our team</li>
                    <li>You will be contacted via the provided phone number or email</li>
                    <li>For any inquiries, please contact us at <a href="mailto:support@yeg.com" class="underline">support@yeg.com</a></li>
                </ul>
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
        // Logo preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('logo');
            const preview = document.getElementById('logo-preview');
            const fileNameDisplay = document.getElementById('file-name');
            
            if(fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        // Update file name display
                        fileNameDisplay.textContent = this.files[0].name;
                        
                        // Preview image
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        // Reset if no file selected
                        preview.src = "{{ asset('images/placeholder.png') }}";
                        fileNameDisplay.textContent = 'No file selected';
                    }
                });
            }
        });

        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    // Toggle the mobile menu
                    if (mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.remove('hidden');
                        mobileMenu.classList.add('animate-fade-in');
                    } else {
                        mobileMenu.classList.add('hidden');
                        mobileMenu.classList.remove('animate-fade-in');
                    }
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
        });
    </script>
</body>
</html>
