<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Dashboard - Young Experts Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#950713',
                        'primary-dark': '#7a0610',
                        'primary-light': '#b31a26'
                    },
                    animation: {
                        'bounce-slow': 'bounce 3s infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite'
                    }
                }
            }
        }
    </script>
    <style>
        .activity-card {
            transition: all 0.3s ease;
        }
        .activity-card:hover {
            transform: translateY(-5px);
        }
        .completion-badge {
            transition: all 0.5s ease;
        }
        .completion-badge.completed {
            transform: scale(1.05);
        }
        .card-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50px;
            height: 100%;
            background: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { left: -100%; }
            20% { left: 100%; }
            100% { left: 100%; }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
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
                            <a href="#" class="text-white hover:bg-white hover:bg-opacity-10 px-4 py-2 rounded-md text-sm font-medium flex items-center transition-all duration-200">
                                <i class="fas fa-home mr-2"></i> Home
                            </a>
                            <a href="#" class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                            <a href="{{ route('student.mywork') }}" class="text-white hover:bg-white hover:bg-opacity-10 px-4 py-2 rounded-md text-sm font-medium flex items-center transition-all duration-200">
                                <i class="fas fa-briefcase mr-2"></i> My Work
                            </a>
                            <a href="#" class="text-white hover:bg-white hover:bg-opacity-10 px-4 py-2 rounded-md text-sm font-medium flex items-center transition-all duration-200">
                                <i class="fas fa-cog mr-2"></i> Solutions
                            </a>
                            <a href="#" class="text-white hover:bg-white hover:bg-opacity-10 px-4 py-2 rounded-md text-sm font-medium flex items-center transition-all duration-200">
                                <i class="fas fa-eye mr-2"></i> Observed
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
                        
                        <!-- User Dropdown -->
                        <div class="relative" id="user-dropdown-container">
                            <button id="user-dropdown-button" class="flex items-center space-x-3 text-white hover:text-teal-200 p-2 rounded-md">
                                <div class="w-8 h-8 rounded-full bg-teal-700 flex items-center justify-center text-sm font-medium">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="dropdown-arrow"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2 text-teal-600"></i> My Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2 text-teal-600"></i> Settings
                                </a>
                                <div class="border-t"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2 text-teal-600"></i> Logout
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
                    <a href="#" class="text-white hover:text-teal-200 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-home mr-2"></i> Home
                    </a>
                    <a href="#" class="bg-teal-800 text-white block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="#" class="text-white hover:text-teal-200 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-clipboard-list mr-2"></i> My Activities
                    </a>
                    <a href="#" class="text-white hover:text-teal-200 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-book mr-2"></i> Resources
                    </a>
                    <a href="#" class="text-white hover:text-teal-200 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-chart-line mr-2"></i> Progress
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 pt-16">
            <!-- Main Content Area -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Dashboard Header with Welcome Message -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold">Welcome, {{ $student->first_name }}!</h1>
                        <p class="text-gray-600 mt-2">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                    
                    <!-- Current Stage Badge -->
                    @if($stage)
                    <div class="mt-4 md:mt-0 bg-white px-6 py-3 rounded-xl shadow-md border-l-4 border-[#950713] flex items-center">
                        <div class="mr-4 p-2 bg-[#950713] bg-opacity-10 rounded-full">
                            <i class="fas fa-flag text-[#950713] text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Current Stage</p>
                            <h3 class="font-bold text-gray-800">{{ $stage->name }}</h3>
                        </div>
                    </div>
                    @else
                    <div class="mt-4 md:mt-0 bg-white px-6 py-3 rounded-xl shadow-md border-l-4 border-gray-400 flex items-center">
                        <div class="mr-4 p-2 bg-gray-100 rounded-full">
                            <i class="fas fa-exclamation-triangle text-gray-400 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Current Stage</p>
                            <h3 class="font-bold text-gray-800">No Stage Assigned</h3>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Full Hero Section with Background Image - Exact Match to Early Bird Design -->
                <div class="relative mb-8 w-full overflow-hidden">
                    <!-- Background Image with Overlay -->
                    <div class="bg-teal-600 w-full" style="height: 240px;">
                        <img src="{{ asset('images/dashboard-bg.jpg') }}" alt="Dashboard Background" class="absolute inset-0 w-full h-full object-cover" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1607703703520-bb638e84caf2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'; this.alt='School Supplies';">
                        <div class="absolute inset-0 bg-teal-600 bg-opacity-80"></div>
                    </div>
                    
                    <!-- Content Layer -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center px-4">
                        <div class="text-center w-full max-w-4xl mx-auto">
                            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-white">Need help finding what you're looking for?</h2>
                            
                            <!-- Full Width Search Box -->
                            <div class="relative w-full max-w-4xl mx-auto">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" class="bg-white text-gray-800 w-full pl-10 pr-4 py-3 rounded-md focus:outline-none shadow-lg" placeholder="Find help, services and solutions">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Your Progress Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4">Your Progress</h2>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Current Stage</h3>
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-teal-600 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium">{{ $stage ? $stage->name : 'Not assigned yet' }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $stage ? 'Level ' . ($stage->level ?? 'N/A') : 'No level assigned' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            @php
                                // Default value if variable is not set
                                $completionPercentage = $completionPercentage ?? 0;
                                
                                // Calculate completion if we have student activities data
                                if (!isset($completionPercentage) && isset($student) && isset($stageActivities)) {
                                    $totalActivities = $stageActivities->count();
                                    $completedActivities = $stageActivities->filter(function($activity) use ($student) {
                                        // Check if this activity is completed by the student
                                        return $activity->studentActivities->where('student_id', $student->id)
                                            ->where('completed_at', '!=', null)
                                            ->count() > 0;
                                    })->count();
                                    
                                    $completionPercentage = $totalActivities > 0 ? 
                                        round(($completedActivities / $totalActivities) * 100) : 0;
                                }
                            @endphp
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Progress</h3>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                <div class="bg-teal-600 h-2.5 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500">{{ $completionPercentage }}% complete</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Upcoming Activities</h3>
                            <div class="space-y-2">
                                @if($stageActivities && $stageActivities->count() > 0)
                                    @foreach($stageActivities->take(2) as $activity)
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-circle text-yellow-500 mr-2 text-xs"></i>
                                        <span>{{ $activity->name }}</span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-circle text-gray-400 mr-2 text-xs"></i>
                                        <span>No upcoming activities</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activity Cards - Will be dynamically populated based on student's stage -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @if ($stageActivities && $stageActivities->count() > 0)
                        @foreach ($stageActivities as $activity)
                            <!-- Dynamic Activity Card -->
                            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow cursor-pointer" 
                                 onclick="showActivityCompletionModal({{ $activity->id }}, '{{ $activity->name }}')">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-[#950713] bg-opacity-10 rounded-lg p-3">
                                        <i class="fas fa-tasks text-[#950713] text-xl"></i>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-medium">{{ $activity->name }}</h3>
                                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($activity->description, 100) }}</p>
                                        
                                        <!-- Activity Completion Badge -->
                                        <div id="completion-badge-{{ $activity->id }}" class="mt-3">
                                            @if($activity->completed)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Completed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-3 bg-white rounded-lg shadow p-8 text-center">
                            <div class="p-4 mb-4 bg-[#950713] bg-opacity-10 rounded-full inline-block">
                                <i class="fas fa-info-circle text-[#950713] text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium mb-2">No Activities Available</h3>
                            <p class="text-gray-600">There are currently no activities associated with your stage.</p>
                        </div>
                    @endif
                </div>
                
                <!-- Quick Links Section Removed -->
                
                <!-- Stage Details Modal (Hidden by default) -->
                <div id="stageDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="border-b p-4 flex justify-between items-center">
                            <h3 class="text-xl font-bold">{{ $stage ? $stage->name : 'Stage Details' }}</h3>
                            <button onclick="hideStageDetails()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6">
                            @if($stage)
                                <div class="mb-4">
                                    <h4 class="text-lg font-semibold mb-2">Description</h4>
                                    <p class="text-gray-600">{{ $stage->description }}</p>
                                </div>
                                <div class="mb-4">
                                    <h4 class="text-lg font-semibold mb-2">Stage Level</h4>
                                    <p class="text-gray-600">{{ $stage->level ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold mb-2">Associated Activities</h4>
                                    @if($stageActivities && $stageActivities->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($stageActivities as $activity)
                                                <div class="bg-gray-50 p-3 rounded-md">
                                                    <h5 class="font-medium">{{ $activity->name }}</h5>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">No activities associated with this stage.</p>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-500 italic">No stage information available.</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Activities Modal (Hidden by default) -->
                <div id="activitiesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="border-b p-4 flex justify-between items-center">
                            <h3 class="text-xl font-bold">Your Activities</h3>
                            <button onclick="hideActivities()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6">
                            @if($stageActivities && $stageActivities->count() > 0)
                                <div class="space-y-4">
                                    @foreach($stageActivities as $activity)
                                        <div class="bg-gray-50 p-4 rounded-md">
                                            <h5 class="font-medium text-lg mb-1">{{ $activity->name }}</h5>
                                            <p class="text-sm text-gray-600">{{ $activity->slug }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">No activities available for your current stage.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <script>
                    // Document ready function
                    $(document).ready(function() {
                        // Initialize tooltips
                        $('[data-tooltip]').hover(function(){
                            $(this).addClass('tooltip-visible');
                        }, function(){
                            $(this).removeClass('tooltip-visible');
                        });

                        // Add animation to cards
                        $('.hover-shadow-lg').hover(function(){
                            $(this).addClass('shadow-lg');
                            $(this).find('.text-[#950713]').addClass('scale-110');
                            $(this).find('.text-[#950713]').css('transition', 'transform 0.3s ease');
                        }, function(){
                            $(this).removeClass('shadow-lg');
                            $(this).find('.text-[#950713]').removeClass('scale-110');
                        });
                    });

                    // Toggle mobile menu
                    function toggleMobileMenu() {
                        const mobileMenu = document.getElementById('mobile-menu');
                        mobileMenu.classList.toggle('hidden');
                    }

                    // Toggle user dropdown with animation
                    function toggleUserDropdown() {
                        const dropdown = $('#user-dropdown');
                        dropdown.toggleClass('hidden');
                        if(!dropdown.hasClass('hidden')) {
                            dropdown.css('opacity', 0).animate({opacity: 1}, 200);
                        }
                    }

                    // Toggle notifications dropdown with animation
                    function toggleNotifications() {
                        const dropdown = $('#notifications-dropdown');
                        dropdown.toggleClass('hidden');
                        if(!dropdown.hasClass('hidden')) {
                            dropdown.css('opacity', 0).animate({opacity: 1}, 200);
                        }
                    }

                    // Show stage details modal with AJAX content loading
                    function showStageDetails() {
                        $('#stageDetailsModal').removeClass('hidden').css('opacity', 0).animate({opacity: 1}, 300);
                        
                        // Optional: Load content via AJAX for fresh data
                        // $.ajax({
                        //     url: '/student/stage-details',
                        //     type: 'GET',
                        //     success: function(response) {
                        //         $('#stageDetailsContent').html(response);
                        //     },
                        //     error: function(xhr) {
                        //         console.error('Error loading stage details:', xhr.responseText);
                        //     }
                        // });
                    }

                    // Hide stage details modal with animation
                    function hideStageDetails() {
                        $('#stageDetailsModal').animate({opacity: 0}, 300, function() {
                            $(this).addClass('hidden');
                        });
                    }

                    // Show activities modal with AJAX content loading
                    function showActivities() {
                        $('#activitiesModal').removeClass('hidden').css('opacity', 0).animate({opacity: 1}, 300);
                        
                        // Optional: Load content via AJAX for fresh data
                        // $.ajax({
                        //     url: '/student/activities',
                        //     type: 'GET',
                        //     success: function(response) {
                        //         $('#activitiesContent').html(response);
                        //     },
                        //     error: function(xhr) {
                        //         console.error('Error loading activities:', xhr.responseText);
                        //     }
                        // });
                    }

                    // Hide activities modal with animation
                    function hideActivities() {
                        $('#activitiesModal').animate({opacity: 0}, 300, function() {
                            $(this).addClass('hidden');
                        });
                    }
                </script>

                <!-- Your Activities Section -->
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold mb-4 text-[#950713]">Activities for {{ $stage ? $stage->name : 'Your Current Stage' }}</h2>
                    <p class="text-gray-600 mb-6">Complete these activities to progress in your education journey.</p>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Stage Details Modal (Hidden by default) -->
    <div id="stageDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="border-b p-4 flex justify-between items-center">
                <h3 class="text-xl font-bold">{{ $stage ? $stage->name : 'Stage Details' }}</h3>
                <button onclick="hideStageDetails()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                @if($stage)
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold mb-2">Description</h4>
                        <p class="text-gray-600">{{ $stage->description }}</p>
                    </div>
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold mb-2">Stage Level</h4>
                        <p class="text-gray-600">{{ $stage->level ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-2">Associated Activities</h4>
                        @if($stageActivities && $stageActivities->count() > 0)
                            <div class="space-y-3">
                                @foreach($stageActivities as $activity)
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <h5 class="font-medium">{{ $activity->name }}</h5>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">No activities associated with this stage.</p>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 italic">No stage information available.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Activities Modal (Hidden by default) -->
    <div id="activitiesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="border-b p-4 flex justify-between items-center">
                <h3 class="text-xl font-bold">Your Activities</h3>
                <button onclick="hideActivities()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                @if($stageActivities && $stageActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($stageActivities as $activity)
                            <div class="bg-gray-50 p-4 rounded-md">
                                <h5 class="font-medium text-lg mb-1">{{ $activity->name }}</h5>
                                <p class="text-sm text-gray-600">{{ $activity->slug }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">No activities available for your current stage.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Activity Completion Modal -->
    <div id="activityCompletionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-[#950713] text-white px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold" id="activityCompletionModalTitle">Activity Name</h3>
                <button onclick="hideActivityCompletionModal()" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <input type="hidden" id="activityId" value="">
                
                <p class="text-gray-700 mb-6">Would you like to mark this activity as complete?</p>
                
                <!-- Loading Spinner -->
                <div id="activityModalLoadingSpinner" class="hidden text-center py-4">
                    <i class="fas fa-spinner fa-spin text-[#950713] text-3xl"></i>
                </div>
                
                <!-- Success Message -->
                <div id="activitySuccessMessage" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                    Activity marked as complete!
                </div>
                
                <!-- Error Message -->
                <div id="activityErrorMessage" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    An error occurred. Please try again.
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button onclick="hideActivityCompletionModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                        Close
                    </button>
                    <button id="completeActivityBtn" onclick="completeActivity()" class="px-4 py-2 bg-[#950713] text-white rounded hover:bg-[#7a0510] transition-colors">
                        <i class="fas fa-check-circle mr-1"></i> Mark Complete
                    </button>
                    <button id="revertActivityBtn" onclick="revertActivityCompletion()" class="hidden px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-undo mr-1"></i> Revert Completion
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery if not already included -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Current activity ID for modal operations
        let currentActivityId = null;
        let activityStatus = {};
        
        // Function to show activity completion modal
        function showActivityCompletionModal(activityId, activityName) {
            currentActivityId = activityId;
            
            // Update modal title
            document.getElementById('activityModalTitle').textContent = activityName;
            
            // Check if we have a status for this activity already
            const isCompleted = activityStatus[activityId] === 'completed';
            
            // Update status badge in modal
            const statusBadge = document.getElementById('activityStatusBadge');
            if (isCompleted) {
                statusBadge.innerHTML = `
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i> Completed
                    </span>
                `;
                document.getElementById('completeActivityBtn').disabled = true;
                document.getElementById('completeActivityBtn').classList.add('opacity-50', 'cursor-not-allowed');
                document.getElementById('revertActivityBtn').disabled = false;
                document.getElementById('revertActivityBtn').classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                statusBadge.innerHTML = `
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-clock mr-2"></i> Pending
                    </span>
                `;
                document.getElementById('completeActivityBtn').disabled = false;
                document.getElementById('completeActivityBtn').classList.remove('opacity-50', 'cursor-not-allowed');
                document.getElementById('revertActivityBtn').disabled = true;
                document.getElementById('revertActivityBtn').classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            // Show modal
            document.getElementById('activityCompletionModal').classList.remove('hidden');
        }
        
        // Hide activity completion modal
        function hideActivityCompletionModal() {
            document.getElementById('activityCompletionModal').classList.add('hidden');
            currentActivityId = null;
        }
        
        // Mark activity as complete
        function markActivityComplete() {
            if (!currentActivityId) return;
            
            // Show loading state
            document.getElementById('completeActivityBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            
            // Send AJAX request to mark activity as complete
            $.ajax({
                url: '/student/activities/' + currentActivityId + '/complete',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Update status in our local state
                    activityStatus[currentActivityId] = 'completed';
                    
                    // Update UI in the modal
                    document.getElementById('activityStatusBadge').innerHTML = `
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i> Completed
                        </span>
                    `;
                    
                    // Update UI in the card
                    const badgeElement = document.getElementById('completion-badge-' + currentActivityId);
                    if (badgeElement) {
                        badgeElement.innerHTML = `
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Completed
                            </span>
                        `;
                        badgeElement.classList.add('completed');
                    }
                    
                    // Enable/disable buttons
                    document.getElementById('completeActivityBtn').disabled = true;
                    document.getElementById('completeActivityBtn').classList.add('opacity-50', 'cursor-not-allowed');
                    document.getElementById('completeActivityBtn').innerHTML = '<i class="fas fa-check mr-2"></i> Mark Complete';
                    document.getElementById('revertActivityBtn').disabled = false;
                    document.getElementById('revertActivityBtn').classList.remove('opacity-50', 'cursor-not-allowed');
                    
                    // Show success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Activity Completed',
                        text: 'The activity has been marked as complete!',
                        confirmButtonColor: '#950713'
                    });
                },
                error: function(xhr) {
                    document.getElementById('completeActivityBtn').innerHTML = '<i class="fas fa-check mr-2"></i> Mark Complete';
                    
                    // Show error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Failed to complete activity. Please try again.',
                        confirmButtonColor: '#950713'
                    });
                }
            });
        }
        
        // Revert activity completion
        function revertActivityCompletion() {
            if (!currentActivityId) return;
            
            // Show loading state
            document.getElementById('revertActivityBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            
            // Send AJAX request to revert activity completion
            $.ajax({
                url: '/student/activities/' + currentActivityId + '/revert',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Update status in our local state
                    activityStatus[currentActivityId] = 'pending';
                    
                    // Update UI in the modal
                    document.getElementById('activityStatusBadge').innerHTML = `
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-clock mr-2"></i> Pending
                        </span>
                    `;
                    
                    // Update UI in the card
                    const badgeElement = document.getElementById('completion-badge-' + currentActivityId);
                    if (badgeElement) {
                        badgeElement.innerHTML = `
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        `;
                        badgeElement.classList.remove('completed');
                    }
                    
                    // Enable/disable buttons
                    document.getElementById('revertActivityBtn').disabled = true;
                    document.getElementById('revertActivityBtn').classList.add('opacity-50', 'cursor-not-allowed');
                    document.getElementById('revertActivityBtn').innerHTML = '<i class="fas fa-undo mr-2"></i> Revert';
                    document.getElementById('completeActivityBtn').disabled = false;
                    document.getElementById('completeActivityBtn').classList.remove('opacity-50', 'cursor-not-allowed');
                    
                    // Show success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Activity Reverted',
                        text: 'The activity status has been reset to pending.',
                        confirmButtonColor: '#950713'
                    });
                },
                error: function(xhr) {
                    document.getElementById('revertActivityBtn').innerHTML = '<i class="fas fa-undo mr-2"></i> Revert';
                    
                    // Show error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Failed to revert activity completion. Please try again.',
                        confirmButtonColor: '#950713'
                    });
                }
            });
        }
        
        // Initialize on document ready
        $(document).ready(function() {
            // Mobile menu toggle
            $('#mobile-menu-button').on('click', function() {
                $('#mobile-sidebar').removeClass('hidden');
                $('#mobile-sidebar div:last-child').removeClass('-translate-x-full');
            });
            
            $('#close-sidebar-button, #mobile-sidebar-backdrop').on('click', function() {
                $('#mobile-sidebar div:last-child').addClass('-translate-x-full');
                setTimeout(function() {
                    $('#mobile-sidebar').addClass('hidden');
                }, 300);
            });
            
            // User dropdown toggle - click-based implementation
            const userDropdownButton = document.getElementById('user-dropdown-button');
            const userDropdownMenu = document.getElementById('user-dropdown-menu');
            const dropdownArrow = document.getElementById('dropdown-arrow');
            
            // Function to close dropdown when clicking outside
            function handleClickOutside(event) {
                const container = document.getElementById('user-dropdown-container');
                if (container && !container.contains(event.target)) {
                    userDropdownMenu.classList.add('hidden');
                    dropdownArrow.classList.remove('rotate-180');
                }
            }
            
            // Toggle dropdown on button click
            userDropdownButton.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent event bubbling
                userDropdownMenu.classList.toggle('hidden');
                dropdownArrow.classList.toggle('rotate-180');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', handleClickOutside);
            
            // Set up activity completion buttons
            document.getElementById('completeActivityBtn').addEventListener('click', markActivityComplete);
            document.getElementById('revertActivityBtn').addEventListener('click', revertActivityCompletion);
        });
    </script>
    
    <!-- Activity completion script -->
    <script src="{{ asset('js/activity-completion.js') }}"></script>
</body>
</html>
