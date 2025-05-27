<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Young Experts Group</title>
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
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .carousel-item {
            display: none;
            transition: opacity 0.5s ease;
        }
        .carousel-item.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side - Image Carousel -->
        <div class="hidden md:block md:w-1/2 bg-primary relative overflow-hidden">
            <div class="carousel h-full">
                <div class="carousel-item active h-full">
                    <div class="h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80')">
                        <div class="h-full w-full flex items-center justify-center bg-black bg-opacity-40">
                            <div class="text-center px-8">
                                <h2 class="text-3xl font-bold text-white mb-4">Welcome to Young Experts Group</h2>
                                <p class="text-lg text-white">Empowering students through education and innovation</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item h-full">
                    <div class="h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80')">
                        <div class="h-full w-full flex items-center justify-center bg-black bg-opacity-50">
                            <div class="text-center px-8">
                                <h2 class="text-3xl font-bold text-white mb-4">Explore Opportunities</h2>
                                <p class="text-lg text-white">Discover new skills and advance your knowledge</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item h-full">
                    <div class="h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1232&q=80')">
                        <div class="h-full w-full flex items-center justify-center bg-black bg-opacity-50">
                            <div class="text-center px-8">
                                <h2 class="text-3xl font-bold text-white mb-4">Join Our Community</h2>
                                <p class="text-lg text-white">Connect with peers and expert mentors</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carousel Controls -->
            <div class="absolute bottom-5 left-0 right-0 flex justify-center space-x-2">
                <button class="carousel-dot w-3 h-3 rounded-full bg-white bg-opacity-50 active"></button>
                <button class="carousel-dot w-3 h-3 rounded-full bg-white bg-opacity-50"></button>
                <button class="carousel-dot w-3 h-3 rounded-full bg-white bg-opacity-50"></button>
            </div>
            
            <!-- Logo Overlay -->
            <div class="absolute top-8 left-8">
                <div class="flex items-center">
                    <span class="text-white text-2xl font-bold">Young</span>
                    <span class="text-secondary mx-1 text-2xl font-bold">Experts</span>
                    <span class="text-white text-2xl font-bold">Group</span>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16">
            <div class="w-full max-w-md">
                <!-- Mobile Logo (visible only on small screens) -->
                <div class="mb-8 flex items-center justify-center md:hidden">
                    <span class="text-primary text-2xl font-bold">Young</span>
                    <span class="text-secondary mx-1 text-2xl font-bold">Experts</span>
                    <span class="text-primary text-2xl font-bold">Group</span>
                </div>
                
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h2>
                    <p class="text-gray-600">Please sign in to your account</p>
                </div>
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
                    @csrf
                    <!-- Username field -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="username" name="username" type="text" required 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                                placeholder="Enter your username">
                        </div>
                    </div>

                    <!-- Password field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                                placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-primary hover:text-red-700">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i> Sign in
                        </button>
                    </div>
                </form>
                
                <div class="mt-10 text-center text-sm text-gray-500">
                    <p> 2025 Young Experts Group. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Carousel functionality
            const carouselItems = document.querySelectorAll('.carousel-item');
            const carouselDots = document.querySelectorAll('.carousel-dot');
            let currentIndex = 0;
            
            function showSlide(index) {
                // Hide all slides
                carouselItems.forEach(item => {
                    item.classList.remove('active');
                });
                
                // Remove active state from all dots
                carouselDots.forEach(dot => {
                    dot.classList.remove('bg-opacity-100');
                    dot.classList.add('bg-opacity-50');
                });
                
                // Show current slide and activate dot
                carouselItems[index].classList.add('active');
                carouselDots[index].classList.remove('bg-opacity-50');
                carouselDots[index].classList.add('bg-opacity-100');
            }
            
            function nextSlide() {
                currentIndex = (currentIndex + 1) % carouselItems.length;
                showSlide(currentIndex);
            }
            
            // Add click event to dots
            carouselDots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentIndex = index;
                    showSlide(currentIndex);
                });
            });
            
            // Auto-advance carousel
            setInterval(nextSlide, 5000);
        });
    </script>
</body>
</html>
