<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'YEG - Student Portal')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
    
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#950713',
                    }
                }
            }
        }
    </script>
    
    <!-- App CSS -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --color-primary: #950713;
        }
        
        .bg-primary, .btn-primary {
            background-color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
        }
        
        .text-primary {
            color: var(--color-primary) !important;
        }
        
        .border-primary {
            border-color: var(--color-primary) !important;
        }
        
        .btn-outline-primary {
            color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--color-primary) !important;
            color: #fff !important;
        }
        
        /* Modern dropdown styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            min-width: 220px;
            animation-duration: 0.2s;
        }
        
        .dropdown-menu.show {
            animation: fadeInDown 0.3s ease forwards;
        }
        
        .dropdown-item {
            padding: 0.65rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
            border-radius: 0.25rem;
            margin: 0 0.25rem;
            width: calc(100% - 0.5rem);
        }
        
        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }
        
        .dropdown-divider {
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            margin: 0.5rem 0;
        }
        
        /* User avatar styling */
        .user-avatar {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        /* Show dropdown arrow animation */
        #user-dropdown-button[aria-expanded="true"] #dropdown-arrow {
            transform: rotate(180deg);
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation Bar (Floating) -->
        <nav class="bg-teal-600 text-white shadow-lg fixed top-0 left-0 right-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Left side - Logo and main navigation -->
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center mr-8">
                            <span class="text-2xl font-bold">YEG</span>
                        </div>
                        
                        <!-- Main Navigation Links -->
                        <div class="hidden md:flex md:space-x-1">
                            <a href="{{ route('student.dashboard') }}" class="text-white hover:bg-white hover:bg-opacity-10 px-4 py-2 rounded-md text-sm font-medium flex items-center transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                            <a href="{{ route('student.mywork') }}" class="text-white hover:bg-white hover:bg-opacity-10 px-4 py-2 rounded-md text-sm font-medium flex items-center transition-all duration-200 {{ request()->routeIs('student.mywork*') ? 'bg-white bg-opacity-20' : '' }}">
                                <i class="fas fa-briefcase mr-2"></i> My Work
                            </a>
                            <a href="{{ route('student.challenges.index') }}" class="text-white hover:bg-white hover:bg-opacity-10 px-4 py-2 rounded-md text-sm font-medium flex items-center transition-all duration-200 {{ request()->routeIs('student.challenges*') ? 'bg-white bg-opacity-20' : '' }}">
                                <i class="fas fa-trophy mr-2"></i> Challenges
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right side - User menu and notifications -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="text-white hover:text-teal-200 p-2 rounded-full relative">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">3</span>
                            </button>
                        </div>
                        
                        <!-- User Dropdown - Alpine.js Accordion Style -->
                        <div x-data="{ open: false }" class="relative" id="user-dropdown-container">
                            <button @click="open = !open" type="button" class="flex items-center space-x-2 text-white hover:bg-white hover:bg-opacity-10 p-2 rounded-md transition-all duration-200">
                                <div class="w-8 h-8 rounded-full overflow-hidden bg-white text-teal-600 flex items-center justify-center shadow-sm">
                                    @if(auth()->user() && auth()->user()->profile_photo)
                                        <img src="{{ asset('uploads/profile-photos/' . auth()->user()->profile_photo) }}?v={{ time() }}" 
                                             alt="{{ auth()->user()->name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'font-semibold\'>{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}</span>'">
                                    @else
                                        <span class="font-semibold">{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}</span>
                                    @endif
                                </div>
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-medium">{{ auth()->user() ? auth()->user()->name : 'User' }}</div>
                                    <div class="text-xs text-gray-100">Student</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white ml-1 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50">
                                <a href="{{ route('student.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user-circle mr-2 text-teal-600"></i> Profile
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-cog mr-2 text-teal-600"></i> Settings
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Mobile menu button -->
                        <div class="md:hidden">
                            <button id="mobile-menu-button" class="text-white hover:text-teal-200 p-2">
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="md:hidden hidden bg-teal-700">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('student.dashboard') }}" class="text-white hover:text-teal-200 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('student.dashboard') ? 'bg-teal-800' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('student.mywork') }}" class="text-white hover:text-teal-200 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('student.mywork*') ? 'bg-teal-800' : '' }}">
                        <i class="fas fa-briefcase mr-2"></i> My Work
                    </a>
                    <a href="{{ route('student.challenges.index') }}" class="text-white hover:text-teal-200 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('student.challenges*') ? 'bg-teal-800' : '' }}">
                        <i class="fas fa-trophy mr-2"></i> Challenges
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="flex-grow pt-16 pb-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-primary text-white py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p>&copy; {{ date('Y') }} Young Experts Group (YEG). All rights reserved.</p>
                    </div>
                    <div>
                        <a href="mailto:imtghanabranch@gmail.com" class="text-white hover:text-teal-200">Contact Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Bootstrap Bundle JS -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Mobile menu toggle only - user dropdown now handled by Bootstrap
            $('#mobile-menu-button').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#mobile-menu').toggleClass('hidden');
            });
            
            // Close mobile menu when clicking outside
            $(document).on('click', function(e) {
                // Close mobile menu only
                if (!$('#mobile-menu').is(e.target) && $('#mobile-menu').has(e.target).length === 0 &&
                    !$('#mobile-menu-button').is(e.target) && $('#mobile-menu-button').has(e.target).length === 0) {
                    $('#mobile-menu').addClass('hidden');
                }
            });
            
            // Initialize any Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
    
    @yield('scripts')
</body>
</html>
