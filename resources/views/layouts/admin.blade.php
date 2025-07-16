<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Young Experts Group') }} - Admin Dashboard</title>
    
    <!-- Resource Hints for Performance Optimization -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    <!-- Preload Critical Assets -->
    <link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    
    <!-- Fonts with Display Swap for Faster Rendering -->
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|montserrat:400,500,600,700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#950713',
                        secondary: '#ffcb05',
                        'neon-pink': '#FF00FF',
                        'primary-dark': '#7a0610'
                    },
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                        montserrat: ['Montserrat', 'sans-serif'],
                    },
                    boxShadow: {
                        'custom': '0 4px 20px rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        .active-nav-link {
            background-color: rgba(149, 7, 19, 0.1);
            color: #950713;
            border-left: 4px solid #950713;
        }
        .sidebar-link:hover {
            background-color: rgba(149, 7, 19, 0.05);
        }
    </style>
</head>
<body class="antialiased bg-gray-100">
    <div x-data="{ sidebarOpen: false }">
        <!-- Mobile Navigation Toggle -->
        <div class="md:hidden bg-white shadow-sm sticky top-0 z-50">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-[#950713] focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <span class="font-semibold text-lg text-gray-800">YEG Admin</span>
                </div>
                
                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-lg z-50">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" 
             class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out md:translate-x-0 md:relative md:inset-auto md:top-auto md:left-auto">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200">
                <a href="/admin" class="text-xl font-bold text-[#950713]">
                    YEG Admin
                </a>
            </div>
            
            <!-- Navigation Links -->
            <nav class="mt-5 px-2">
                <a href="/admin" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->is('admin') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-tachometer-alt mr-3 {{ request()->is('admin') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Dashboard
                </a>
                
                <!-- Trainers -->
                <a href="{{ route('admin.trainers.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.trainers.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-chalkboard-teacher mr-3 {{ request()->routeIs('admin.trainers.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Trainers
                </a>

                <!-- Students -->
                <a href="{{ route('admin.students.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.students.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-user-graduate mr-3 {{ request()->routeIs('admin.students.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Students
                </a>

                <!-- School Logos -->
                <a href="{{ route('admin.school-logos.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.school-logos.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-images mr-3 {{ request()->routeIs('admin.school-logos.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    School Logos
                </a>
                
                <!-- Hero Sections -->
                <a href="{{ route('admin.hero-sections.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.hero-sections.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-image mr-3 {{ request()->routeIs('admin.hero-sections.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Hero Sections
                </a>
                
                <!-- Events -->
                <a href="{{ route('admin.events.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.events.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-calendar-alt mr-3 {{ request()->routeIs('admin.events.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Events
                </a>
                
                <!-- Happenings -->
                <a href="{{ route('admin.happenings.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.happenings.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-newspaper mr-3 {{ request()->routeIs('admin.happenings.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Happenings
                </a>
                
                <!-- Testimonials -->
                <a href="{{ route('admin.testimonials.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.testimonials.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-quote-right mr-3 {{ request()->routeIs('admin.testimonials.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Testimonials
                </a>
                
                <!-- Partner Schools -->
                <a href="{{ route('admin.partner-schools.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.partner-schools.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-handshake mr-3 {{ request()->routeIs('admin.partner-schools.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Partner Schools
                </a>
                
                <!-- Fees -->
                <a href="{{ route('admin.fees.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md sidebar-link {{ request()->routeIs('admin.fees.*') ? 'active-nav-link' : 'text-gray-600' }}">
                    <i class="fas fa-money-bill-wave mr-3 {{ request()->routeIs('admin.fees.*') ? 'text-[#950713]' : 'text-gray-400' }}"></i>
                    Fees
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div :class="{'md:pl-64': sidebarOpen}" class="flex-1 min-h-screen md:pl-64 transition-all duration-300 ease-in-out">
            <!-- Top Navigation -->
            <div class="hidden md:block bg-white shadow-sm sticky top-0 z-10">
                <div class="flex items-center justify-between h-16 px-6">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @if(request()->routeIs('admin.trainers.*'))
                            Trainer Management
                        @elseif(request()->routeIs('admin.students.*'))
                            Student Management
                        @elseif(request()->routeIs('admin.school-logos.*'))
                            School Logos
                        @elseif(request()->routeIs('admin.hero-sections.*'))
                            Hero Sections
                        @elseif(request()->routeIs('admin.events.*'))
                            Events Management
                        @elseif(request()->routeIs('admin.happenings.*'))
                            Happenings Management
                        @elseif(request()->routeIs('admin.testimonials.*'))
                            Testimonials Management
                        @elseif(request()->routeIs('admin.partner-schools.*'))
                            Partner Schools
                        @elseif(request()->routeIs('admin.fees.*'))
                            Fee Management
                        @else
                            @yield('title', 'Dashboard')
                        @endif
                    </h2>
                    
                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-lg z-50">
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </a>
                            <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
    
    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Extra Scripts -->
    @yield('scripts')
</body>
</html>
