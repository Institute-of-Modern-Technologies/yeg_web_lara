<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'School Portal') - Young Experts Group</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 for modals -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @yield('styles')
</head>
<body class="font-sans antialiased bg-black">
    <div class="min-h-screen">
        <!-- Navigation - Sticky header with shadow -->
        <nav class="bg-gradient-to-r from-gray-950 via-black to-gray-950 border-b border-gray-800 fixed top-0 left-0 right-0 z-50 shadow-lg shadow-gray-900/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-green-400 to-blue-500 bg-clip-text text-transparent">YEG School Portal</h1>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="hidden md:ml-10 md:flex md:space-x-8">
                            <a href="{{ route('school.dashboard') }}" 
                               class="border-transparent text-gray-400 hover:text-green-400 hover:border-green-400 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('school.dashboard') ? 'border-green-400 text-green-400' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('school.students.index') }}" 
                               class="border-transparent text-gray-400 hover:text-blue-400 hover:border-blue-400 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('school.students.*') ? 'border-blue-400 text-blue-400' : '' }}">
                                Students
                            </a>
                            <a href="#" 
                               class="border-transparent text-gray-400 hover:text-purple-400 hover:border-purple-400 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                                Reports
                            </a>
                            <a href="#" 
                               class="border-transparent text-gray-400 hover:text-yellow-400 hover:border-yellow-400 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                                Settings
                            </a>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-400">{{ Auth::user()->name }}</span>
                            <div class="relative">
                                <button type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400 focus:ring-offset-black" id="user-menu-button">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5" id="user-menu">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content - Added top padding to account for fixed header -->
        <main class="pt-24 pb-6 px-4">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
    
    <!-- User Menu Toggle Script -->
    <script>
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('user-menu-button');
            const menu = document.getElementById('user-menu');
            
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
