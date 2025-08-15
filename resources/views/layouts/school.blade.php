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
    
    <style>
        /* Ultra-Modern Navigation Link Styles */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            margin: 0 0.5rem;
            position: relative;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 1;
            border-radius: 0.75rem;
            overflow: hidden;
            letter-spacing: 0.025em;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 0.75rem;
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: scale(0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .nav-link:hover {
            color: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
        }
        
        .nav-link:hover::before {
            opacity: 1;
            transform: scale(1);
        }
        
        .nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            margin-right: 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.07);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .nav-link-active {
            color: white;
            font-weight: 600;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }
        
        .nav-link-active::before {
            opacity: 1;
            transform: scale(1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 
                0 8px 32px 0 rgba(0, 0, 0, 0.2),
                inset 0 0 0 1px rgba(255, 255, 255, 0.05);
        }
        
        .nav-link-active .nav-icon {
            transform: scale(1.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
        }
        
        .nav-link-green .nav-icon {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.8) 0%, rgba(52, 211, 153, 0.8) 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4), inset 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        
        .nav-link-blue .nav-icon {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.8) 0%, rgba(96, 165, 250, 0.8) 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4), inset 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        
        .nav-link-purple .nav-icon {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.8) 0%, rgba(167, 139, 250, 0.8) 100%);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4), inset 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
    </style>
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
                               class="nav-link {{ request()->routeIs('school.dashboard') ? 'nav-link-active nav-link-green' : '' }}">
                                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('school.students.index') }}" 
                               class="nav-link {{ request()->routeIs('school.students.*') ? 'nav-link-active nav-link-blue' : '' }}">
                                <span class="nav-icon"><i class="fas fa-user-graduate"></i></span>
                                <span>Students</span>
                            </a>
                            <a href="{{ route('school.profile') }}" 
                               class="nav-link {{ request()->routeIs('school.profile*') ? 'nav-link-active nav-link-purple' : '' }}">
                                <span class="nav-icon"><i class="fas fa-user-cog"></i></span>
                                <span>Profile</span>
                            </a>
                            <a href="#" 
                               class="nav-link">
                                <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                                <span>Reports</span>
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
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('uploads/profile-photos/' . rawurlencode(Auth::user()->profile_photo)) }}?v={{ time() }}" 
                                             alt="{{ Auth::user()->name }}" 
                                             class="h-8 w-8 rounded-full object-cover border border-gray-600"
                                             onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML = '<div class=\'h-8 w-8 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center\'><span class=\'text-white text-sm font-medium\'>{{ substr(Auth::user()->name, 0, 1) }}</span></div>'">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-800 border border-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5" id="user-menu">
                                    <a href="{{ route('school.profile') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Sign out
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
