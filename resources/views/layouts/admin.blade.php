<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IMT') }} | @yield('title', 'Admin Dashboard')</title>
    
    <!-- Resource Hints for Performance Optimization -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    <!-- Preload Critical Assets -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    
    <!-- Fonts with Display Swap for Faster Rendering -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                        'admin-purple': '#6B21A8',
                        'admin-purple-light': '#8B5CF6',
                        'admin-purple-dark': '#4C1D95',
                        'admin-gray': '#F3F4F6'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'custom': '0 4px 20px rgba(0, 0, 0, 0.1)',
                        'card': '0 2px 10px rgba(0, 0, 0, 0.08)'
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
        
        [x-cloak] { display: none !important; }
        
        :root {
            --color-primary: #950713;
            --color-admin-purple: #6B21A8;
            --color-admin-purple-light: #9333EA;
            --color-admin-purple-dark: #4C1D95;
            --color-admin-gray: #f5f7fa;
        }
        
        /* IMT Branding Specific Styles */
        .imt-primary {
            color: var(--color-primary);
        }
        
        .imt-bg-primary {
            background-color: var(--color-primary);
        }
        
        .bg-admin-gray {
            background-color: var(--color-admin-gray);
        }
        
        .bg-admin-purple {
            background-color: var(--color-admin-purple);
        }
        
        .text-primary {
            color: var(--color-primary);
        }
        
        .text-admin-purple {
            color: var(--color-admin-purple);
        }
        
        /* Gradient backgrounds for cards */
        .bg-purple-gradient {
            background: linear-gradient(135deg, #6B21A8 0%, #9333EA 100%);
            color: white;
        }
        
        .bg-purple-gradient-light {
            background: linear-gradient(135deg, #8B5CF6 0%, #C084FC 100%);
            color: white;
        }
        
        /* Sidebar styling */
        .admin-sidebar {
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }
        
        .active-nav-link {
            background-color: #6B21A8;
            color: white;
            border-radius: 8px;
        }
        
        .sidebar-link {
            transition: all 0.3s;
            border-radius: 8px;
            margin-bottom: 5px;
            color: #64748B;
            font-size: 14px;
        }
        
        .sidebar-link:hover {
            background-color: #F3E8FF;
            color: #6B21A8;
        }
        
        .sidebar-link:hover i {
            color: #6B21A8 !important;
        }
        
        /* Card styling */
        .card {
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background-color: white;
            overflow: hidden;
        }
        
        .card-purple {
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(107, 33, 168, 0.15);
            background: linear-gradient(135deg, #6B21A8 0%, #9333EA 100%);
            color: white;
        }
        
        .card:hover {
            box-shadow: 0 4px 20px rgba(107, 33, 168, 0.15);
            transform: translateY(-2px);
        }
        
        /* Button styling */
        .btn-primary {
            background-color: var(--color-admin-purple);
            color: white;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background-color: var(--color-admin-purple-dark);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background-color: white;
            color: var(--color-admin-purple);
            border: 1px solid #E9D5FF;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }
        
        .btn-secondary:hover {
            background-color: #F3E8FF;
            transform: translateY(-1px);
        }
        
        /* Table styling */
        table {
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        th {
            background-color: #F9FAFB;
            color: #6B7280;
            font-weight: 500;
            padding: 12px 16px;
            text-align: left;
            font-size: 14px;
        }
        
        td {
            padding: 12px 16px;
            border-top: 1px solid #F3F4F6;
        }
        
        tr:hover td {
            background-color: #F9F6FE;
        }
        
        /* Pagination */
        .pagination a {
            border-radius: 8px;
            padding: 8px 12px;
            margin: 0 2px;
            color: #6B21A8;
            border: 1px solid #E9D5FF;
        }
        
        .pagination a:hover {
            background-color: #F3E8FF;
        }
        
        .pagination .active {
            background-color: #6B21A8;
            color: white;
            border-color: #6B21A8;
        }
        
        /* Form elements */
        input, select, textarea {
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            padding: 8px 12px;
            transition: all 0.2s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #6B21A8;
            box-shadow: 0 0 0 3px rgba(107, 33, 168, 0.1);
            outline: none;
        }
        
        /* Stats counter */
        .stat-card {
            border-radius: 16px;
            overflow: hidden;
            padding: 20px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.8;
        }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased bg-admin-gray">
    <div x-data="{ sidebarOpen: false }">
        <!-- Mobile Navigation -->
        <div class="block md:hidden bg-white shadow-sm sticky top-0 z-10 rounded-b-xl">
            <div class="flex items-center justify-between h-16 px-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none focus:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="/admin" class="flex items-center">
                    <div class="w-9 h-9 bg-admin-purple rounded-lg flex items-center justify-center text-white mr-2">
                        <span class="text-sm font-bold">IMT</span>
                    </div>
                    <span class="text-lg font-medium text-gray-800">Admin</span>
                </a>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <div class="w-8 h-8 bg-admin-purple rounded-full flex items-center justify-center text-white">
                            <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                            <i class="fas fa-user-circle mr-2 text-admin-purple"></i> Profile
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-admin-purple">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" 
             class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transition-transform duration-300 ease-in-out md:translate-x-0 md:relative md:inset-auto md:top-auto md:left-auto rounded-xl admin-sidebar">
            
            <!-- Logo -->
            <div class="flex items-center justify-between h-20 px-5 border-b border-gray-100">
                <a href="/admin" class="flex items-center">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-admin-purple rounded-lg flex items-center justify-center text-white mr-3">
                            <span class="text-lg font-bold">IMT</span>
                        </div>
                        <span class="text-lg font-semibold text-gray-800">Admin</span>
                    </div>
                </a>
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-800 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <nav class="mt-5 px-4">
                <div class="mb-4">
                    <p class="text-xs uppercase text-gray-400 tracking-wider ml-2 mb-2">Main</p>
                    <a href="/admin" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->is('admin') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-tachometer-alt mr-3 {{ request()->is('admin') ? 'text-white' : 'text-gray-400' }}"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.students.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.students.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-user-graduate mr-3 {{ request()->routeIs('admin.students.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Students
                    </a>
                </div>
                <div class="mb-6">
                    <p class="text-xs uppercase text-gray-400 tracking-wider ml-2 mb-3">Management</p>
                    <a href="{{ route('admin.trainers.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.trainers.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-chalkboard-teacher mr-3 {{ request()->routeIs('admin.trainers.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Trainers
                    </a>
                    <a href="{{ route('admin.testimonials.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.testimonials.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-quote-left mr-3 {{ request()->routeIs('admin.testimonials.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Testimonials
                    </a>
                    <a href="{{ route('admin.schools.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.schools.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-school mr-3 {{ request()->routeIs('admin.schools.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Schools
                    </a>
                    <a href="{{ route('admin.activities.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.activities.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-clipboard-list mr-3 {{ request()->routeIs('admin.activities.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Activities Setup
                    </a>
                    <a href="{{ route('admin.billing.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.billing.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-file-invoice-dollar mr-3 {{ request()->routeIs('admin.billing.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Billing
                    </a>
                </div>

                <div class="mb-6">
                    <p class="text-xs uppercase text-gray-400 tracking-wider ml-2 mb-3">Content</p>
                    <!-- School Logos -->
                    <a href="{{ route('admin.school-logos.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.school-logos.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-images mr-3 {{ request()->routeIs('admin.school-logos.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        School Logos
                    </a>
                    <!-- Partner Schools -->
                    <a href="{{ route('admin.partner-schools.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.partner-schools.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-handshake mr-3 {{ request()->routeIs('admin.partner-schools.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Partner Schools
                    </a>
                    
                    <!-- Hero Sections -->
                    <a href="{{ route('admin.hero-sections.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.hero-sections.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-image mr-3 {{ request()->routeIs('admin.hero-sections.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Hero Sections
                    </a>
                    
                    <!-- Events -->
                    <a href="{{ route('admin.events.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.events.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-calendar-alt mr-3 {{ request()->routeIs('admin.events.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Events
                    </a>
                    
                    <!-- Happenings -->
                    <a href="{{ route('admin.happenings.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.happenings.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-newspaper mr-3 {{ request()->routeIs('admin.happenings.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Happenings
                    </a>
                    
                    <!-- Fees -->
                    <a href="{{ route('admin.fees.index') }}" 
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg mb-2 sidebar-link {{ request()->routeIs('admin.fees.*') ? 'active-nav-link' : 'text-gray-600' }}">
                        <i class="fas fa-money-bill-wave mr-3 {{ request()->routeIs('admin.fees.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        Fees
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div :class="{'md:pl-64': sidebarOpen}" class="flex-1 min-h-screen md:pl-64 transition-all duration-300 ease-in-out">
            <!-- Top Navigation -->
            <div class="hidden md:block bg-white shadow-sm sticky top-0 z-10 rounded-b-xl mb-6">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <i class="fas 
                            @if(request()->routeIs('admin.trainers.*'))
                                fa-chalkboard-teacher text-admin-purple
                            @elseif(request()->routeIs('admin.students.*'))
                                fa-user-graduate text-admin-purple
                            @elseif(request()->routeIs('admin.testimonials.*'))
                                fa-quote-right text-admin-purple
                            @elseif(request()->routeIs('admin.partner-schools.*'))
                                fa-handshake text-admin-purple
                            @elseif(request()->routeIs('admin.hero-sections.*'))
                                fa-image text-admin-purple
                            @elseif(request()->routeIs('admin.events.*'))
                                fa-calendar-alt text-admin-purple
                            @elseif(request()->routeIs('admin.happenings.*'))
                                fa-newspaper text-admin-purple
                            @elseif(request()->routeIs('admin.school-logos.*'))
                                fa-images text-admin-purple
                            @elseif(request()->routeIs('admin.fees.*'))
                                fa-money-bill-wave text-admin-purple
                            @else
                                fa-tachometer-alt text-admin-purple
                            @endif
                            "></i>
                        </div>
                        <h1 class="text-lg font-medium text-gray-800">
                            @if(request()->routeIs('admin.trainers.*'))
                                Trainers Management
                            @elseif(request()->routeIs('admin.students.*'))
                                Students Management
                            @elseif(request()->routeIs('admin.testimonials.*'))
                                Testimonials Management
                            @elseif(request()->routeIs('admin.partner-schools.*'))
                                Partner Schools Management
                            @elseif(request()->routeIs('admin.hero-sections.*'))
                                Hero Sections
                            @elseif(request()->routeIs('admin.events.*'))
                                Events Management
                            @elseif(request()->routeIs('admin.happenings.*'))
                                Happenings Management
                            @elseif(request()->routeIs('admin.school-logos.*'))
                                School Logos
                            @elseif(request()->routeIs('admin.fees.*'))
                                Fees Management
                            @else
                                Dashboard
                            @endif
                        </h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Search Bar -->
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="bg-gray-50 border border-gray-200 text-sm rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200 w-48">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        
                        <!-- Notification Icon -->
                        <div class="relative">
                            <button class="p-1 rounded-full hover:bg-purple-50 focus:outline-none">
                                <div class="relative">
                                    <i class="fas fa-bell text-gray-500"></i>
                                    <span class="absolute -top-1 -right-1 bg-admin-purple text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">2</span>
                                </div>
                            </button>
                        </div>
                        
                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-admin-purple rounded-full flex items-center justify-center text-white">
                                        <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <span class="hidden md:inline-block font-medium text-sm text-gray-700 ml-2">{{ Auth::user()->name }}</span>
                                    <svg class="h-4 w-4 text-gray-400 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50">
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                    <i class="fas fa-user-circle mr-2 text-admin-purple"></i> Profile
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                    <i class="fas fa-cog mr-2 text-admin-purple"></i> Settings
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-admin-purple">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            <main class="py-6 px-4 md:px-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="bg-green-50 text-green-800 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-50 text-red-800 border-l-4 border-primary p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-primary"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Dashboard Stats (Only show on dashboard page) -->
                @if(request()->is('admin'))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Total Students Card -->
                    <div class="card-purple p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Total Students</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ \App\Models\Student::count() }}</h3>
                            </div>
                            <div class="bg-white/20 p-3 rounded-lg">
                                <i class="fas fa-user-graduate text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-3 text-purple-100 text-xs">
                            <span class="font-medium">+5%</span> from last month
                        </div>
                    </div>
                    
                    <!-- Total Trainers Card -->
                    <div class="bg-purple-gradient-light p-5 rounded-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Total Trainers</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ \App\Models\Trainer::count() }}</h3>
                            </div>
                            <div class="bg-white/20 p-3 rounded-lg">
                                <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-3 text-purple-100 text-xs">
                            <span class="font-medium">+2</span> new this month
                        </div>
                    </div>
                    
                    <!-- Partner Schools -->
                    <div class="card-purple p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Partner Schools</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ \App\Models\PartnerSchool::count() }}</h3>
                            </div>
                            <div class="bg-white/20 p-3 rounded-lg">
                                <i class="fas fa-handshake text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-3 text-purple-100 text-xs">
                            <span class="font-medium">+3</span> since last year
                        </div>
                    </div>
                    
                    <!-- Testimonials Count -->
                    <div class="bg-purple-gradient-light p-5 rounded-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Testimonials</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ \App\Models\Testimonial::count() }}</h3>
                            </div>
                            <div class="bg-white/20 p-3 rounded-lg">
                                <i class="fas fa-quote-left text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-3 text-purple-100 text-xs">
                            <span class="font-medium">4.8</span> average rating
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Recent Students -->
                    <div class="card p-5 lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-800">Recent Students</h3>
                            <a href="{{ route('admin.students.index') }}" class="text-admin-purple text-sm hover:underline">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>School</th>
                                        <th>Registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Student::latest()->take(5)->get() as $student)
                                    <tr>
                                        <td class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                                <span class="text-admin-purple font-medium">{{ substr($student->name, 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium">{{ $student->name }}</span>
                                        </td>
                                        <td>{{ $student->school_id ? $student->school->name : $student->school_name }}</td>
                                        <td>{{ $student->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="card p-5">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.students.create') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <div class="p-2 bg-admin-purple rounded-lg mr-3">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                                <span class="font-medium text-gray-800">Add New Student</span>
                            </a>
                            
                            <a href="{{ route('admin.trainers.create') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <div class="p-2 bg-admin-purple rounded-lg mr-3">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                                <span class="font-medium text-gray-800">Add New Trainer</span>
                            </a>
                            
                            <a href="{{ route('admin.testimonials.create') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <div class="p-2 bg-admin-purple rounded-lg mr-3">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                                <span class="font-medium text-gray-800">Add New Testimonial</span>
                            </a>
                            
                            <a href="{{ route('admin.events.create') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <div class="p-2 bg-admin-purple rounded-lg mr-3">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                                <span class="font-medium text-gray-800">Add New Event</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Main Content Card -->
                <div class="card p-5 md:p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Page-specific scripts -->
    @yield('scripts')
    
    <!-- CSRF Token Setup for AJAX -->
    <script src="{{ asset('js/csrf-setup.js') }}"></script>
    
    <!-- Extra Scripts -->
    @yield('scripts')
</body>
</html>
