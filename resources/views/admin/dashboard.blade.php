<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Young Experts Group</title>
    
    <!-- Large Favicon Implementation for Admin Dashboard -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}?v={{ rand(1000,9999) }}" sizes="512x512">
    <style>
        /* Force browsers to display favicon at maximum possible size */
        link[rel="icon"] {
            width: 64px !important; 
            height: 64px !important;
        }
    </style>
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#950713',
                        secondary: '#f59e0b'
                    }
                }
            }
        }
    </script>
    <!-- Include our custom sticky header CSS -->    
    <link href="{{ asset('css/sticky-headers.css') }}" rel="stylesheet">
    
    <style>
        /* Basic resets */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            overflow-x: hidden;
            background-color: #f3f4f6;
            padding-top: 60px; /* Space for fixed header */
        }
        
        /* Header styling */
        .app-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 1000; /* Higher z-index to stay on top */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            transition: all 0.3s ease;
        }
        
        /* Sidebar styling */
        #sidebar {
            position: fixed;
            left: 0;
            top: 60px; /* Start below header */
            bottom: 0;
            height: calc(100% - 60px); /* Full height minus header */
            width: 256px; /* 16rem */
            background-color: #1f2937;
            z-index: 990; /* Lower than header but still high */
            overflow-y: auto;
            transition: all 0.3s ease;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Mobile sidebar takes full width */
        @media (max-width: 767px) {
            #sidebar {
                width: 100%;
                z-index: 1001; /* Higher than header on mobile */
                transform: translateX(-100%);
            }
            
            #sidebar.mobile-visible {
                transform: translateX(0);
            }
        }
        
        /* Desktop collapsed sidebar - mini mode */
        @media (min-width: 768px) {
            #sidebar.collapsed {
                width: 70px;
            }
            
            #sidebar.collapsed .sidebar-link-text,
            #sidebar.collapsed .sidebar-accordion-content,
            #sidebar.collapsed .sidebar-section-header {
                display: none;
            }
        }
        
        /* Sidebar accordion styling */
        .sidebar-accordion-item {
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
        }
        
        /* Section headers - consistent styling */
        .sidebar-section-header {
            padding-top: 1rem;
            padding-bottom: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
            border-top: 1px solid rgba(107, 114, 128, 0.3);
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 600;
            color: #9CA3AF;
            letter-spacing: 0.05em;
        }
        
        .sidebar-accordion-button {
            transition: all 0.3s ease;
        }
        
        .sidebar-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .sidebar-accordion-content.open {
            max-height: 1000px; /* Arbitrary large value */
        }
        
        /* Rotate icon when accordion is open */
        .sidebar-accordion-button .rotate-icon {
            transition: transform 0.3s ease;
        }
        
        .sidebar-accordion-button.active .rotate-icon {
            transform: rotate(180deg);
        }
        
        /* Content area */
        .content-area {
            width: 100%;
            min-height: 100vh;
            flex: 1;
            transition: padding 0.3s ease;
            padding-left: 0; /* No padding on mobile */
        }
        
        @media (min-width: 768px) {
            .content-area {
                padding-left: 256px; /* Match sidebar width */
            }
            
            .content-area.sidebar-collapsed {
                padding-left: 70px; /* Match collapsed sidebar width */
            }
        }
        
        .active-menu-item {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="app-header bg-primary text-white shadow-md w-full overflow-visible">
            <div class="px-4 py-3 w-full">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <!-- Sidebar toggle button (mobile only) -->
                        <button id="sidebar-toggle" class="md:hidden text-white hover:text-gray-300 focus:outline-none mr-3 flex-shrink-0">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <a href="/" class="font-semibold text-xl tracking-tight text-white hover:text-gray-200 transition-colors">
                            <img src="{{ asset('images/favicon.png') }}" alt="YEG Logo" class="h-8 sm:h-9">
                        </a>
                        <span class="text-xs sm:text-sm bg-secondary px-2 py-1 rounded">Super Admin</span>
                    </div>
                    
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="text-white focus:outline-none relative">
                                <i class="fas fa-bell text-xl"></i>
                                @if(isset($pendingRegistrations) && count($pendingRegistrations) > 0)
                                    <span class="absolute -top-1 -right-1 bg-[#950713] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">{{ count($pendingRegistrations) }}</span>
                                @endif
                            </button>
                            
                            <!-- Dropdown Notifications Panel -->
                            <div x-show="open" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50" style="display: none;">
                                <div class="bg-[#950713] text-white px-4 py-2 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold">Notifications</h3>
                                    <span class="text-xs bg-white text-[#950713] px-2 py-1 rounded-full">{{ isset($pendingRegistrations) ? count($pendingRegistrations) : 0 }} New</span>
                                </div>
                                
                                <div class="max-h-64 overflow-y-auto">
                                    @if(isset($pendingRegistrations) && count($pendingRegistrations) > 0)
                                        @foreach($pendingRegistrations as $registration)
                                            @php
                                                // Set appropriate route and icon based on registration type
                                                $route = '';
                                                $icon = '';
                                                $typeText = '';
                                                
                                                if($registration->type === 'student') {
                                                    $route = route('admin.students.show', $registration->id);
                                                    $icon = 'fa-user-graduate';
                                                    $typeText = 'New student registration';
                                                } elseif($registration->type === 'teacher') {
                                                    $route = route('admin.trainers.show', $registration->id);
                                                    $icon = 'fa-chalkboard-teacher';
                                                    $typeText = 'New trainer registration';
                                                } elseif($registration->type === 'school') {
                                                    $route = route('admin.schools.show', $registration->id);
                                                    $icon = 'fa-school';
                                                    $typeText = 'New school registration';
                                                }
                                            @endphp
                                            <a href="{{ $route }}" class="block border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                <div class="p-3">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 mt-1">
                                                            <div class="w-8 h-8 rounded-full bg-[#950713]/10 flex items-center justify-center text-[#950713]">
                                                                <i class="fas {{ $icon }} text-sm"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium text-gray-800">{{ $registration->full_name }}</p>
                                                            <p class="text-xs text-gray-500 mt-1 flex items-center">
                                                                <i class="fas fa-clock mr-1 text-[#950713]"></i>
                                                                {{ $typeText }}
                                                            </p>
                                                            <p class="text-xs text-gray-400 mt-1">
                                                                {{ \Carbon\Carbon::parse($registration->created_at)->diffForHumans() }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="py-4 px-3 text-center text-gray-500 text-sm">
                                            No pending registrations
                                        </div>
                                    @endif
                                </div>
                                
                                @if(isset($pendingRegistrations) && count($pendingRegistrations) > 0)
                                    <div class="bg-gray-50 px-3 py-3">
                                        <p class="text-xs text-gray-600 mb-2 font-medium">View all pending registrations:</p>
                                        <div class="flex justify-center space-x-3">
                                            <a href="{{ route('admin.students.index', ['status' => 'pending']) }}" class="px-2 py-1 text-xs bg-[#950713]/10 text-[#950713] rounded flex items-center">
                                                <i class="fas fa-user-graduate mr-1"></i> Students
                                            </a>
                                            <a href="{{ route('admin.trainers.index', ['status' => 'pending']) }}" class="px-2 py-1 text-xs bg-[#950713]/10 text-[#950713] rounded flex items-center">
                                                <i class="fas fa-chalkboard-teacher mr-1"></i> Trainers
                                            </a>
                                            <a href="{{ route('admin.schools.index', ['status' => 'pending']) }}" class="px-2 py-1 text-xs bg-[#950713]/10 text-[#950713] rounded flex items-center">
                                                <i class="fas fa-school mr-1"></i> Schools
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- User dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-white/20 overflow-hidden flex items-center justify-center text-white font-medium">
                                     @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/profile-photos/' . rawurlencode(Auth::user()->profile_photo)) }}?v={{ time() }}" 
                                            alt="{{ Auth::user()->name }}" class="w-full h-full object-cover"
                                            onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML = '{{ substr(Auth::user()->name, 0, 1) }}'">
                                    @else
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <span class="hidden md:block text-sm">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="transition-all duration-300 md:block" id="sidebar">
            <div class="h-full flex flex-col pt-0"> <!-- No top padding needed since sidebar starts below header -->
                <!-- Navigation -->
                <div class="px-4 py-5">
                    <!-- Dashboard Link - Always visible -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg text-white hover:bg-gray-800 mb-6 bg-gradient-to-r from-primary/20 to-transparent border-l-2 border-secondary">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        <span class="sidebar-link-text">Dashboard</span>
                    </a>
                    
                    <div class="sidebar-section-header px-4 mt-0 pt-0 border-0">Main Navigation</div>
                    
                    <nav class="space-y-3">
                        <!-- Users - Direct Link -->
                        <div class="sidebar-item mb-1">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
                                <i class="fas fa-users w-5 h-5 mr-3"></i>
                                <span class="sidebar-link-text">Users</span>
                            </a>
                        </div>
                        
                        <!-- Section Header -->
                        <div class="sidebar-section-header px-4">Advertisement</div>
                        
                        <!-- Advertisement Accordion -->
                        <div class="sidebar-accordion-item">
                            <button class="sidebar-accordion-button w-full flex items-center justify-between py-2.5 px-4 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-bullhorn w-5 h-5 mr-3"></i>
                                    <span class="sidebar-link-text">Advertisement</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs rotate-icon"></i>
                            </button>
                            
                            <div class="sidebar-accordion-content pl-4">
                                <a href="{{ route('admin.hero-sections.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.hero-sections.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} mt-1">
                                    <i class="fas fa-image w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Hero Sections</span>
                                </a>
                                
                                <a href="{{ route('admin.events.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.events.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Events</span>
                                </a>
                                
                                <a href="{{ route('admin.happenings.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.happenings.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-newspaper w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Happenings</span>
                                </a>
                                
                                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.testimonials.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-quote-left w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Testimonials</span>
                                </a>
                                
                                <a href="{{ route('admin.partner-schools.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.partner-schools.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-school w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Partner Schools</span>
                                </a>
                                
                                <a href="{{ route('admin.school-logos.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.school-logos.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-images w-4 mr-2"></i>
                                    <span class="sidebar-link-text">School Logos</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Section Header -->
                        <div class="sidebar-section-header px-4">Participants</div>
                        
                        <!-- Participants Accordion -->
                        <div class="sidebar-accordion-item">
                            <button class="sidebar-accordion-button w-full flex items-center justify-between py-2.5 px-4 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-user-graduate w-5 h-5 mr-3"></i>
                                    <span class="sidebar-link-text">Participants</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs rotate-icon"></i>
                            </button>
                            
                            <div class="sidebar-accordion-content pl-4">
                                <a href="{{ route('admin.students.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.students.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} mt-1">
                                    <i class="fas fa-user-graduate w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Students</span>
                                </a>
                                
                                <a href="{{ route('admin.trainers.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.trainers.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-chalkboard-teacher w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Trainers</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Section Header -->
                        <div class="sidebar-section-header px-4">System Setup</div>
                        
                        <!-- Setups Accordion -->
                        <div class="sidebar-accordion-item">
                            <button class="sidebar-accordion-button w-full flex items-center justify-between py-2.5 px-4 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                                    <span class="sidebar-link-text">Setups</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs rotate-icon"></i>
                            </button>
                            
                            <div class="sidebar-accordion-content pl-4">
                                <a href="{{ route('admin.program-types.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.program-types.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} mt-1">
                                    <i class="fas fa-list-alt w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Program Types</span>
                                </a>
                                <a href="{{ route('admin.schools.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.schools.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-school w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Schools</span>
                                </a>
                                <a href="{{ route('admin.fees.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.fees.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-money-bill-wave w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Program Fees</span>
                                </a>
                                <a href="{{ route('admin.activities.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.activities.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-tasks w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Activities</span>
                                </a>

                                <a href="{{ route('admin.stages.index') }}" class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs('admin.stages.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <i class="fas fa-chart-line w-4 mr-2"></i>
                                    <span class="sidebar-link-text">Stages</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Other menu items will be added when they're implemented -->
                    </nav>
                </div>
                
                <!-- Super Admin Only Section -->
                @if(Auth::user()->user_type_id == 1)
                <div class="px-4 pt-2 pb-1 border-t border-gray-700">
                    <!-- Section Header -->
                    <div class="sidebar-section-header px-0 mt-0 pt-2 border-0">Administration</div>
                    <!-- System Settings Accordion -->
                    <div class="sidebar-accordion-item">
                        <button class="sidebar-accordion-button w-full flex items-center justify-between py-2.5 px-4 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt w-5 h-5 mr-3"></i>
                                <span class="sidebar-link-text">Super Admin</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs rotate-icon"></i>
                        </button>
                        
                        <div class="sidebar-accordion-content pl-4">
                            <a href="#" class="flex items-center py-2 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white mt-1" onclick="openUserModal(); return false;">
                                <i class="fas fa-user-plus w-4 mr-2"></i>
                                <span class="sidebar-link-text">Create New User</span>
                            </a>
                            <a href="#" class="flex items-center py-2 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                <i class="fas fa-cogs w-4 mr-2"></i>
                                <span class="sidebar-link-text">System Settings</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Bottom section with helpful links -->
                <div class="mt-auto px-4 py-4 border-t border-gray-700 opacity-90">
                    <div class="text-center">
                        <span class="text-xs font-semibold text-gray-500">Young Experts Group Admin</span>
                        <div class="text-xs text-gray-600 mt-1">© 2025</div>
                        <div class="flex justify-center mt-2 space-x-2">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-question-circle"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area - FULL WIDTH -->
        <main class="content-area">
            <div class="p-6">
                @if(Route::currentRouteName() == 'admin.dashboard')
                <!-- Dashboard specific content -->
                <!-- Dashboard Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
                    <div class="flex flex-wrap gap-2 mt-3 md:mt-0">
                        <button class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 flex items-center hover:bg-gray-300">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>{{ date('F Y') }}</span>
                        </button>
                        <button class="px-4 py-2 bg-primary text-white rounded-lg flex items-center hover:bg-red-700">
                            <i class="fas fa-file-export mr-2"></i>
                            <span>Export Data</span>
                        </button>
                    </div>
                </div>

                <!-- Modernized Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow overflow-hidden relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#950713] to-red-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="z-index: 0; opacity: 0.05;"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium uppercase">Total Users</p>
                                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ \App\Models\User::count() }}</h3>
                                </div>
                                <div class="bg-red-100 p-3 rounded-full">
                                    <i class="fas fa-users text-[#950713] text-xl"></i>
                                </div>
                            </div>
                            <div class="flex items-center mt-4">
                                <span class="text-green-500 flex items-center text-sm">
                                    <i class="fas fa-arrow-up mr-1"></i> 12%
                                </span>
                                <span class="text-gray-400 text-sm ml-2">Since last month</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Student Status Cards and Filter Section -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-4 mt-6">
                        <div class="bg-white rounded-xl shadow-md p-6 overflow-hidden">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-user-graduate mr-3 text-[#950713]"></i>
                                    Student Management
                                </h2>
                            </div>
                            
                            <!-- Student Status Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Active Students Card -->
                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-green-200 rounded-full opacity-30 group-hover:opacity-40 transition-opacity"></div>
                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-green-700 font-medium text-sm">Active Students</p>
                                                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $activeStudents }}</h3>
                                            </div>
                                            <div class="bg-white p-3 rounded-full shadow-sm">
                                                <i class="fas fa-user-check text-green-500 text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-4">
                                            <a href="{{ route('admin.students.index', ['status' => 'active']) }}" class="text-green-700 font-medium text-sm hover:text-green-800">
                                                View All <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Available Trainers Card -->
                                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-red-200 rounded-full opacity-30 group-hover:opacity-40 transition-opacity"></div>
                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-[#950713] font-medium text-sm">Available Trainers</p>
                                                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $approvedTrainers }}</h3>
                                            </div>
                                            <div class="bg-white p-3 rounded-full shadow-sm">
                                                <i class="fas fa-chalkboard-teacher text-[#950713] text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-4">
                                            <a href="{{ route('admin.trainers.index', ['status' => 'approved']) }}" class="text-[#950713] font-medium text-sm hover:text-red-800">
                                                View All <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pending Students Card -->
                                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-yellow-200 rounded-full opacity-30 group-hover:opacity-40 transition-opacity"></div>
                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-yellow-700 font-medium text-sm">Pending Students</p>
                                                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingStudents }}</h3>
                                            </div>
                                            <div class="bg-white p-3 rounded-full shadow-sm">
                                                <i class="fas fa-user-clock text-yellow-500 text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-4">
                                            <a href="{{ route('admin.students.index', ['status' => 'pending']) }}" class="text-yellow-700 font-medium text-sm hover:text-yellow-800">
                                                View All <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Schools Card with Filter -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-md p-6 h-full overflow-hidden">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-school mr-3 text-[#950713]"></i>
                                    Schools
                                </h2>
                            </div>
                            
                            <!-- Schools List -->
                            <div class="overflow-y-auto" style="max-height: 300px;">
                                @forelse($schools as $school)
                                <div class="flex items-center justify-between p-3 border-b last:border-b-0 hover:bg-gray-50 transition-colors rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#950713]/10 flex items-center justify-center">
                                            <i class="fas fa-building text-[#950713]"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-800 font-medium">{{ $school->name }}</p>
                                            <p class="text-gray-500 text-sm">{{ $school->city }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-gray-800 font-medium">{{ $school->students_count }}</div>
                                        <div class="text-gray-500 text-sm">Students</div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-6 text-gray-500">No schools found</div>
                                @endforelse
                            </div>
                            
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.schools.index') }}" class="text-[#950713] hover:text-red-800 font-medium">
                                    View All Schools <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Trainers Card with Filter -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-md p-6 h-full overflow-hidden">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-chalkboard-teacher mr-3 text-[#950713]"></i>
                                    Trainers
                                </h2>
                            </div>
                            
                            <!-- Trainers List -->
                            <div class="overflow-y-auto" style="max-height: 300px;">
                                @forelse($teachers as $teacher)
                                <div class="flex items-center p-3 border-b last:border-b-0 hover:bg-gray-50 transition-colors rounded-lg">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#950713]/10 flex items-center justify-center mr-3">
                                        @if($teacher->gender == 'male')
                                            <i class="fas fa-male text-[#950713]"></i>
                                        @elseif($teacher->gender == 'female')
                                            <i class="fas fa-female text-[#950713]"></i>
                                        @else
                                            <i class="fas fa-user text-[#950713]"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-gray-800 font-medium">{{ $teacher->name }}</p>
                                        <p class="text-gray-500 text-sm">{{ $teacher->subject ?? 'General' }}</p>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-6 text-gray-500">No trainers found</div>
                                @endforelse
                            </div>
                            
                            <div class="mt-4 text-center">
                                <a href="#" class="text-[#950713] hover:text-red-800 font-medium">
                                    View All Trainers <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Management Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-800">User Management</h2>
                        @if(Auth::user()->user_type_id == 1)
                        <button class="bg-primary text-white px-4 py-2 rounded-lg flex items-center hover:bg-red-700 transition-colors duration-200 mt-2 md:mt-0" id="add-user-btn" onclick="openUserModal();">
                            <i class="fas fa-plus mr-2"></i> Add New User
                        </button>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between mb-4 space-y-3 md:space-y-0">
                            <div class="relative w-full md:w-64">
                                <input type="text" id="userSearchInput" placeholder="Search users..." class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <div class="flex space-x-2">
                                <select id="userTypeFilter" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">All User Types</option>
                                    <option value="1">Super Admin</option>
                                    <option value="2">School Admin</option>
                                    <option value="3">Student</option>
                                </select>
                                <button id="applyFilterBtn" class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Users Table/Cards -->
                        <div class="overflow-y-auto flex-grow scroll-smooth" style="max-height: calc(100vh - 60px);">
                            @php
                                $paginatedUsers = \App\Models\User::paginate(5);
                            @endphp
                            
                            <!-- Desktop Table View (hidden on mobile) -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="w-full bg-white rounded-lg overflow-hidden">
                                    <thead>
                                        <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                                            <th class="py-3 px-4 text-left font-semibold">Name</th>
                                            <th class="py-3 px-4 text-left font-semibold">Username</th>
                                            <th class="py-3 px-4 text-left font-semibold">Email</th>
                                            <th class="py-3 px-4 text-left font-semibold">User Type</th>
                                            <th class="py-3 px-4 text-center font-semibold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm">
                                        @foreach($paginatedUsers as $user)
                                        <tr data-user-type="{{ $user->user_type_id }}">
                                            <td class="py-3 px-4">{{ $user->name }}</td>
                                            <td class="py-3 px-4">{{ $user->username }}</td>
                                            <td class="py-3 px-4">{{ $user->email }}</td>
                                            <td class="py-3 px-4">
                                                @php
                                                    $badgeClass = 'bg-gray-100 text-gray-800';
                                                    if($user->user_type_id == 1) {
                                                        $badgeClass = 'bg-purple-100 text-purple-800';
                                                    } elseif($user->user_type_id == 2) {
                                                        $badgeClass = 'bg-blue-100 text-blue-800';
                                                    } elseif($user->user_type_id == 3) {
                                                        $badgeClass = 'bg-green-100 text-green-800';
                                                    }
                                                @endphp
                                                <span class="{{ $badgeClass }} px-2 py-1 rounded-full text-xs font-semibold">
                                                    {{ $user->userType ? $user->userType->name : 'Unknown' }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <button class="text-blue-500 hover:text-blue-700" onclick="editUser({{ $user->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if(Auth::id() != $user->id)
                                                    <button class="text-red-500 hover:text-red-700" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Mobile Card View (visible only on mobile) -->
                            <div class="md:hidden space-y-4">
                                @foreach($paginatedUsers as $user)
                                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200" data-user-type="{{ $user->user_type_id }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="text-gray-800 font-medium">{{ $user->name }}</h3>
                                        @php
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            if($user->user_type_id == 1) {
                                                $badgeClass = 'bg-purple-100 text-purple-800';
                                            } elseif($user->user_type_id == 2) {
                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                            } elseif($user->user_type_id == 3) {
                                                $badgeClass = 'bg-green-100 text-green-800';
                                            }
                                        @endphp
                                        <span class="{{ $badgeClass }} px-2 py-1 rounded-full text-xs font-semibold">
                                            {{ $user->userType ? $user->userType->name : 'Unknown' }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm">
                                        <div class="flex">
                                            <span class="text-gray-500 w-24">Username:</span>
                                            <span class="text-gray-800 font-medium">{{ $user->username }}</span>
                                        </div>
                                        <div class="flex">
                                            <span class="text-gray-500 w-24">Email:</span>
                                            <span class="text-gray-800 font-medium">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button class="flex items-center text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md text-sm" onclick="editUser({{ $user->id }})">
                                            <i class="fas fa-edit mr-1.5"></i> Edit
                                        </button>
                                        @if(Auth::id() != $user->id)
                                        <button class="flex items-center text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md text-sm" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fas fa-trash-alt mr-1.5"></i> Delete
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination Links with Tailwind Styling -->
                            <div class="mt-5 flex justify-center">
                                {{ $paginatedUsers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    @yield('content')
                @endif
            </div>
        </main>
    </div>

    <!-- User Management Modals -->
    <!-- Create User Modal -->
    <div id="createUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 overflow-hidden animate-fadeIn transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary to-red-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-user-plus mr-3"></i>
                    <span>Create New User</span>
                </h3>
                <button type="button" class="text-white hover:text-gray-200 focus:outline-none" onclick="closeUserModal()">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="createUserForm">
                    @csrf
                    <div class="space-y-4">
                        <!-- Row 1: Name and Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="name" name="name" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter full name" required>
                                </div>
                            </div>
                            
                            <!-- Email Address -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="email" name="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter email address" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 2: Username and User Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Username -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="username">
                                    Username
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="text" id="username" name="username" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter username" required>
                                </div>
                            </div>
                            
                            <!-- User Type -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="user_type_id">
                                    User Type
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select id="user_type_id" name="user_type_id" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary appearance-none" required>
                                        <option value="" disabled selected>Select user type</option>
                                        <option value="3">Student</option>
                                        <option value="2">School Admin</option>
                                        <option value="1">Super Admin</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 3: Password and Confirm Password -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                                    Password
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password" name="password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter password" required>
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('password', 'passwordToggle')">
                                        <i id="passwordToggle" class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="password_confirmation">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Confirm password" required>
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('password_confirmation', 'confirmPasswordToggle')">
                                        <i id="confirmPasswordToggle" class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center" onclick="closeUserModal()">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 overflow-hidden animate-fadeIn transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary to-red-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-user-edit mr-3"></i>
                    <span>Edit User</span>
                </h3>
                <button type="button" class="text-white hover:text-gray-200 focus:outline-none" onclick="closeEditUserModal()">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="space-y-4">
                        <!-- Row 1: Name and Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_name">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="edit_name" name="name" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter full name" required>
                                </div>
                            </div>
                            
                            <!-- Email Address -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_email">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="edit_email" name="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter email address" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 2: Username and User Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Username -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_username">
                                    Username
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="text" id="edit_username" name="username" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter username" required>
                                </div>
                            </div>
                            
                            <!-- User Type -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_user_type_id">
                                    User Type
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select id="edit_user_type_id" name="user_type_id" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary appearance-none" required>
                                        <option value="" disabled>Select user type</option>
                                        <option value="3">Student</option>
                                        <option value="2">School Admin</option>
                                        <option value="1">Super Admin</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 3: Password (Optional) -->
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    <span class="text-sm text-gray-700 font-medium">Password Update (Optional)</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-3">Leave blank if you don't want to change the password</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Password -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_password">
                                            New Password
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" id="edit_password" name="password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter new password">
                                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('edit_password', 'editPasswordToggle')">
                                                <i id="editPasswordToggle" class="fas fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Confirm Password -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_password_confirmation">
                                            Confirm New Password
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" id="edit_password_confirmation" name="password_confirmation" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Confirm new password">
                                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('edit_password_confirmation', 'editConfirmPasswordToggle')">
                                                <i id="editConfirmPasswordToggle" class="fas fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center" onclick="closeEditUserModal()">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Alpine.js -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    
    <!-- Global Image Preview Handler -->
    <script>
        // Initialize universal image preview handlers
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Global image preview handler initialized');
            
            // Find all image inputs
            const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
            
            // Attach preview handler to each image input
            imageInputs.forEach(input => {
                console.log('Found image input:', input.id || 'unnamed input');
                
                // Find the closest preview container
                // Look for either a container with ID matching [inputId]-preview or a container with ID image-preview
                const inputId = input.id;
                let previewContainer = inputId ? document.getElementById(inputId + '-preview') : null;
                
                if (!previewContainer) {
                    // Check for a container with id="image-preview" in the same form or parent container
                    const form = input.closest('form') || input.parentElement;
                    if (form) {
                        previewContainer = form.querySelector('#image-preview');
                    }
                }
                
                if (previewContainer) {
                    console.log('Found preview container for:', inputId || 'unnamed input');
                    
                    // Add change event listener
                    input.addEventListener('change', function() {
                        console.log('Image input changed:', inputId || 'unnamed input');
                        
                        if (this.files && this.files[0]) {
                            const file = this.files[0];
                            console.log('Selected file:', file.name);
                            
                            // Clear any existing content and create new image
                            previewContainer.innerHTML = '';
                            const img = document.createElement('img');
                            
                            // Add appropriate classes (try to keep original styling)
                            // Check if we're in the advertisement section (testimonials, events, hero-sections)
                            const isAdSection = window.location.pathname.includes('/admin/testimonials') || 
                                                window.location.pathname.includes('/admin/events') || 
                                                window.location.pathname.includes('/admin/hero-sections') ||
                                                window.location.pathname.includes('/admin/partner-schools') ||
                                                window.location.pathname.includes('/admin/happenings');
                            
                            if (isAdSection) {
                                // Advertisement section images should be rectangular with rounded corners
                                img.className = 'mx-auto h-32 object-cover rounded';
                            } else if (inputId === 'profile_photo') {
                                // Profile photos should be circular
                                img.className = 'mx-auto h-32 w-32 object-cover rounded-full';
                            } else {
                                // Default styling
                                img.className = 'mx-auto h-32 object-cover rounded';
                            }
                            img.alt = 'Preview';
                            
                            // Add to container
                            previewContainer.appendChild(img);
                            
                            // Create file reader
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                img.src = e.target.result;
                                
                                // Force show the preview container
                                previewContainer.classList.remove('hidden');
                                previewContainer.style.display = 'block';
                                console.log('Preview should now be visible');
                            };
                            
                            // Handle errors
                            reader.onerror = function() {
                                console.error('Error reading file:', file.name);
                                previewContainer.innerHTML = '<p class="text-red-500">Error previewing image</p>';
                            };
                            
                            // Read the image file
                            reader.readAsDataURL(file);
                        }
                    });
                } else {
                    console.log('No preview container found for:', inputId || 'unnamed input');
                }
            });
        });
    </script>
    
    <!-- Sidebar Accordion and Toggle JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-expand the accordion containing the active link
            const activeLink = document.querySelector('.sidebar-accordion-content a.bg-gray-800');
            if (activeLink) {
                const parentAccordion = activeLink.closest('.sidebar-accordion-content');
                if (parentAccordion) {
                    parentAccordion.classList.add('open');
                    const accordionButton = parentAccordion.previousElementSibling;
                    if (accordionButton && accordionButton.classList.contains('sidebar-accordion-button')) {
                        accordionButton.classList.add('active');
                    }
                }
            }
            
            // Initialize sidebar toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const bodyElement = document.body;
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    const isMobile = window.innerWidth < 768;
                    
                    // Only toggle sidebar on mobile
                    if (isMobile) {
                        sidebar.classList.toggle('mobile-visible');
                    }
                    
                    // Store the mobile sidebar visibility preference
                    if (isMobile) {
                        const isMobileVisible = sidebar.classList.contains('mobile-visible');
                        localStorage.setItem('sidebar-mobile-visible', isMobileVisible);
                    }
                });
                
                // Check for saved preferences with separate mobile/desktop settings
                const isMobile = window.innerWidth < 768;
                
                // Only apply mobile sidebar visibility preference if on mobile
                if (isMobile) {
                    const mobileVisible = localStorage.getItem('sidebar-mobile-visible');
                    if (mobileVisible === 'true') {
                        sidebar.classList.add('mobile-visible');
                    }
                }
                
                // Clear out old localStorage format
                localStorage.removeItem('sidebar-collapsed');
            }
            
            // Initialize accordion functionality
            const accordionButtons = document.querySelectorAll('.sidebar-accordion-button');
            
            accordionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Toggle active class for styling
                    this.classList.toggle('active');
                    
                    // Toggle content visibility
                    const content = this.nextElementSibling;
                    content.classList.toggle('open');
                    
                    // Store accordion state
                    const accordionId = this.closest('.sidebar-accordion-item').dataset.id || 
                                       Array.from(document.querySelectorAll('.sidebar-accordion-button')).indexOf(this);
                    const isOpen = content.classList.contains('open');
                    localStorage.setItem(`accordion-${accordionId}`, isOpen);
                });
                
                // Check for saved accordion state and open if needed
                const accordionItem = button.closest('.sidebar-accordion-item');
                const accordionId = accordionItem.dataset.id || 
                                 Array.from(document.querySelectorAll('.sidebar-accordion-button')).indexOf(button);
                const savedState = localStorage.getItem(`accordion-${accordionId}`);
                
                if (savedState === 'true') {
                    button.classList.add('active');
                    button.nextElementSibling.classList.add('open');
                }
            });
            
            // Automatically open the first accordion by default if none are open
            if (accordionButtons.length > 0) {
                const anyOpen = Array.from(accordionButtons).some(btn => btn.classList.contains('active'));
                if (!anyOpen && accordionButtons[0]) {
                    accordionButtons[0].classList.add('active');
                    accordionButtons[0].nextElementSibling.classList.add('open');
                }
            }
        });
    </script>
    
    <!-- Sticky Header JS -->
    <script src="{{ asset('js/sticky-header.js') }}"></script>
    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fadeOut {
            animation: fadeOut 0.2s ease-in-out;
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }
    </style>
    
    <script>
        // Function to open the user modal - accessible directly from HTML
        function openUserModal() {
            console.log('openUserModal called directly');
            const modal = document.getElementById('createUserModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            } else {
                console.error('Modal element not found!');
            }
            return false;
        }
        
        // Functions to open/close the user modals - accessible directly from HTML
        function closeUserModal() {
            console.log('closeUserModal called');
            const modal = document.getElementById('createUserModal');
            const modalContent = modal.querySelector('div'); // Get the modal content container
            
            // Add fadeOut animation
            if (modalContent) {
                modalContent.classList.add('animate-fadeOut');
                
                // Wait for animation to finish before hiding modal
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    modalContent.classList.remove('animate-fadeOut');
                    document.body.style.overflow = ''; // Restore scrolling
                }, 200);
            } else {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scrolling
            }
            return false;
        }
    
        // Immediately execute scripts after page load
        window.addEventListener('load', function() {
            console.log('Page fully loaded');
            
            // Unified sidebar toggle for mobile and desktop
            const sidebarToggleBtn = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const contentArea = document.querySelector('.content-area');
            
            if (sidebar && sidebarToggleBtn) {
                // Unified sidebar toggle button handler
                sidebarToggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    if (contentArea) {
                        contentArea.classList.toggle('sidebar-collapsed');
                    }
                    
                    // On mobile, also toggle the translate class
                    if (window.innerWidth < 768) {
                        sidebar.classList.toggle('-translate-x-full');
                    }
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    const isMobile = window.innerWidth < 768;
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickInsideButton = sidebarToggleBtn.contains(event.target);
                    
                    if (isMobile && !isClickInsideSidebar && !isClickInsideButton && 
                        !sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.add('-translate-x-full');
                    }
                });
                
                // Handle resize events
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        // On desktop
                        if (sidebar.classList.contains('-translate-x-full')) {
                            sidebar.classList.remove('-translate-x-full');
                        }
                    }
                });
            }
            
            // Debug the modals on the page
            console.log('Modal element:', document.getElementById('createUserModal'));
            
            // Add User button functionality as backup
            const addUserBtn = document.getElementById('add-user-btn');
            console.log('Add User button found:', addUserBtn);
            
            if (addUserBtn) {
                addUserBtn.addEventListener('click', function(e) {
                    console.log('Add User click event fired');
                    e.preventDefault();
                    openUserModal();
                });
            }
            
            // Function to filter users by search term and type
            function filterUsers() {
                const searchTerm = userSearchInput ? userSearchInput.value.toLowerCase() : '';
                const filterType = userTypeFilter ? userTypeFilter.value : '';
                const userTable = document.querySelector('table tbody');
                const userRows = userTable.querySelectorAll('tr');
                
                userRows.forEach(row => {
                    const userName = row.cells[0].textContent.toLowerCase();
                    const userUsername = row.cells[1].textContent.toLowerCase();
                    const userEmail = row.cells[2].textContent.toLowerCase();
                    const userType = row.cells[3].textContent.toLowerCase();
                    const userTypeId = row.getAttribute('data-user-type');
                    
                    // Match search term
                    const matchesSearch = searchTerm === '' || 
                        userName.includes(searchTerm) || 
                        userUsername.includes(searchTerm) || 
                        userEmail.includes(searchTerm) || 
                        userType.includes(searchTerm);
                    
                    // Match filter type
                    const matchesFilter = filterType === '' || userTypeId === filterType;
                    
                    // Show/hide row
                    if (matchesSearch && matchesFilter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // User search functionality
            const userSearchInput = document.getElementById('userSearchInput');
            if (userSearchInput) {
                userSearchInput.addEventListener('keyup', filterUsers);
            }
            
            // User type filter functionality
            const userTypeFilter = document.getElementById('userTypeFilter');
            const applyFilterBtn = document.getElementById('applyFilterBtn');
            
            if (userTypeFilter && applyFilterBtn) {
                applyFilterBtn.addEventListener('click', filterUsers);
                userTypeFilter.addEventListener('change', filterUsers);
            }
            
            // Add click event listeners to close modals when clicking outside
            document.querySelectorAll('.fixed.inset-0.bg-black.bg-opacity-50').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        if (modal.id === 'editUserModal') {
                            closeEditUserModal();
                        } else {
                            closeUserModal();
                        }
                    }
                });
            });
            
            // Close modals with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const visibleModal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50.flex');
                    if (visibleModal) {
                        if (visibleModal.id === 'editUserModal') {
                            closeEditUserModal();
                        } else {
                            closeUserModal();
                        }
                    }
                }
            });
            
            // Create User Form submission handling with AJAX
            const createUserForm = document.getElementById('createUserForm');
            if (createUserForm) {
                createUserForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
                    
                    // Clear previous error messages
                    document.querySelectorAll('.error-message').forEach(el => el.remove());
                    document.querySelectorAll('.border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.remove('focus:ring-red-500');
                        el.classList.remove('focus:border-red-500');
                    });
                    
                    // Get form data
                    const formData = new FormData(this);
                    
                    // Send AJAX request
                    fetch('{{ route("users.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(Object.fromEntries(formData))
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#FF0000',
                            }).then(() => {
                                // Reset form and close modal
                                createUserForm.reset();
                                closeUserModal();
                                
                                // Reload page to refresh user list
                                window.location.reload();
                            });
                        } else {
                            // Show validation errors
                            const errors = data.errors;
                            if (errors) {
                                Object.keys(errors).forEach(field => {
                                    const input = document.getElementById(field);
                                    if (input) {
                                        // Highlight input
                                        input.classList.add('border-red-500');
                                        input.classList.add('focus:ring-red-500');
                                        input.classList.add('focus:border-red-500');
                                        
                                        // Add error message
                                        const errorMsg = document.createElement('div');
                                        errorMsg.className = 'text-red-500 text-xs mt-1 error-message';
                                        errorMsg.textContent = errors[field][0];
                                        input.parentNode.appendChild(errorMsg);
                                    }
                                });
                            }
                            
                            // Show error toast
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            Toast.fire({
                                icon: 'error',
                                title: 'There were errors in your submission'
                            });
                        }
                    })
                    .catch(error => {
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem creating the user. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#FF0000'
                        });
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
                });
            }
            
            // Edit User Form submission handling with AJAX
            const editUserForm = document.getElementById('editUserForm');
            if (editUserForm) {
                editUserForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
                    
                    // Clear previous error messages
                    document.querySelectorAll('#editUserForm .error-message').forEach(el => el.remove());
                    document.querySelectorAll('#editUserForm .border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.remove('focus:ring-red-500');
                        el.classList.remove('focus:border-red-500');
                    });
                    
                    // Get form data
                    const formData = new FormData(this);
                    const userId = document.getElementById('edit_user_id').value;
                    
                    // Send AJAX request
                    fetch(`/admin/users/${userId}`, {
                        method: 'POST', // Laravel accepts POST for PUT with _method
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            Swal.fire({
                                title: 'Success!',
                                text: data.message || 'User updated successfully',
                                icon: 'success',
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                // Close modal
                                closeEditUserModal();
                                
                                // Reload page to refresh user list
                                window.location.reload();
                            });
                        } else {
                            // Show validation errors
                            const errors = data.errors;
                            if (errors) {
                                Object.keys(errors).forEach(field => {
                                    // Handle field name adjustments
                                    let formField = field;
                                    if (field.startsWith('edit_')) {
                                        formField = field;
                                    } else {
                                        formField = 'edit_' + field;
                                    }
                                    
                                    const input = document.getElementById(formField);
                                    if (input) {
                                        // Highlight input
                                        input.classList.add('border-red-500');
                                        input.classList.add('focus:ring-red-500');
                                        input.classList.add('focus:border-red-500');
                                        
                                        // Add error message
                                        const errorMsg = document.createElement('div');
                                        errorMsg.className = 'text-red-500 text-xs mt-1 error-message';
                                        errorMsg.textContent = errors[field][0];
                                        input.parentNode.appendChild(errorMsg);
                                    }
                                });
                            }
                            
                            // Show error toast
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            Toast.fire({
                                icon: 'error',
                                title: data.message || 'There were errors in your submission'
                            });
                        }
                    })
                    .catch(error => {
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem updating the user. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
                });
            }
        });
        
        // These general modal functions are kept for reference but not used directly
        // We're using the specific openUserModal and closeUserModal functions instead
        
        // Password visibility toggle
        function togglePasswordVisibility(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
        
        // Open the edit user modal and fetch user data
        function openEditUserModal() {
            const modal = document.getElementById('editUserModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            } else {
                console.error('Edit modal element not found!');
            }
            return false;
        }
        
        // Close the edit user modal
        function closeEditUserModal() {
            const modal = document.getElementById('editUserModal');
            const modalContent = modal.querySelector('div'); // Get the modal content container
            
            // Add fadeOut animation
            if (modalContent) {
                modalContent.classList.add('animate-fadeOut');
                
                // Wait for animation to finish before hiding modal
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    modalContent.classList.remove('animate-fadeOut');
                    document.body.style.overflow = ''; // Restore scrolling
                }, 200);
            } else {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scrolling
            }
            
            // Reset form
            document.getElementById('editUserForm').reset();
            
            // Clear validation errors
            document.querySelectorAll('#editUserForm .error-message').forEach(el => el.remove());
            document.querySelectorAll('#editUserForm .border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.remove('focus:ring-red-500');
                el.classList.remove('focus:border-red-500');
            });
            
            return false;
        }
        
        // Edit user function - fetch user data and show modal
        function editUser(userId) {
            // Show loading indicator
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching user data',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Fetch user data from the server
            fetch(`/admin/users/${userId}/edit`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Close loading indicator
                Swal.close();
                
                if (data.success) {
                    // Populate form with user data
                    const user = data.user;
                    document.getElementById('edit_user_id').value = user.id;
                    document.getElementById('edit_name').value = user.name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_username').value = user.username;
                    document.getElementById('edit_user_type_id').value = user.user_type_id;
                    
                    // Open modal
                    openEditUserModal();
                } else {
                    // Show error message
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Could not retrieve user data',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                Swal.fire({
                    title: 'Error!',
                    text: 'There was a problem fetching user data. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            });
        }
        
        // Delete user function
        function deleteUser(userId, userName) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete ${userName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete user
                    fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                data.message,
                                'success'
                            ).then(() => {
                                // Reload page to refresh user list
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'There was a problem deleting the user. Please try again.',
                            'error'
                        );
                        console.error('Error:', error);
                    });
                }
            });
        }
    </script>
</body>
</html>