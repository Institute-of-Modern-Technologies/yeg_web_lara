@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
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
                        <a href="{{ route('student.dashboard') }}" class="text-white hover:bg-white hover:bg-opacity-10 px-4 py-2 rounded-md text-sm font-medium flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('student.mywork') }}" class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center transition-all duration-200">
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
                <a href="{{ route('student.dashboard') }}" class="text-white hover:text-teal-200 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('student.mywork') }}" class="bg-teal-800 text-white block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-briefcase mr-2"></i> My Work
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
    <div class="pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header with Upload Button -->
            <div class="py-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">My Work</h1>
                <a href="{{ route('student.mywork.create') }}" class="bg-[#950713] hover:bg-red-800 text-white px-4 py-2 rounded-md flex items-center">
                    <i class="fas fa-plus mr-2"></i> Upload New Work
                </a>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Categorized Work Sections -->
            <div class="space-y-8">
                <!-- Images Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-image text-[#950713] mr-2"></i> Images
                    </h2>
                    
                    @if(count($works['image']) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($works['image'] as $work)
                                <div class="bg-gray-50 rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                                    <a href="{{ route('student.mywork.show', $work->id) }}" class="block">
                                        <div class="h-48 overflow-hidden">
                                            <img src="{{ $work->getThumbnailUrl() }}" alt="{{ $work->title }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900">{{ $work->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($work->created_at)->format('M d, Y') }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No images uploaded yet.</p>
                    @endif
                </div>
                
                <!-- Videos Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-video text-[#950713] mr-2"></i> Videos
                    </h2>
                    
                    @if(count($works['video']) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($works['video'] as $work)
                                <div class="bg-gray-50 rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                                    <a href="{{ route('student.mywork.show', $work->id) }}" class="block">
                                        <div class="h-48 overflow-hidden bg-gray-200 relative">
                                            <img src="{{ $work->getThumbnailUrl() }}" alt="{{ $work->title }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-16 h-16 bg-[#950713] bg-opacity-75 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-play text-white text-2xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900">{{ $work->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($work->created_at)->format('M d, Y') }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No videos uploaded yet.</p>
                    @endif
                </div>
                
                <!-- Websites Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-globe text-[#950713] mr-2"></i> Websites
                    </h2>
                    
                    @if(count($works['website']) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($works['website'] as $work)
                                <div class="bg-gray-50 rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                                    <a href="{{ $work->website_url }}" target="_blank" class="block">
                                        <div class="h-48 overflow-hidden bg-gray-200 relative">
                                            @if($work->thumbnail)
                                                <img src="{{ $work->getThumbnailUrl() }}" alt="{{ $work->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <i class="fas fa-globe text-gray-400 text-5xl"></i>
                                                </div>
                                            @endif
                                            <div class="absolute top-2 right-2">
                                                <div class="bg-white bg-opacity-75 rounded-full w-8 h-8 flex items-center justify-center">
                                                    <i class="fas fa-external-link-alt text-[#950713]"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900">{{ $work->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-1 truncate">{{ $work->website_url }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No websites shared yet.</p>
                    @endif
                </div>
                
                <!-- Books Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-book text-[#950713] mr-2"></i> Books
                    </h2>
                    
                    @if(count($works['book']) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @foreach($works['book'] as $work)
                                <div class="bg-gray-50 rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                                    <a href="{{ route('student.mywork.show', $work->id) }}" class="block">
                                        <div class="h-64 overflow-hidden relative">
                                            @if($work->thumbnail)
                                                <img src="{{ $work->getThumbnailUrl() }}" alt="{{ $work->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center bg-[#950713]">
                                                    <i class="fas fa-book text-white text-4xl mb-2"></i>
                                                    <div class="px-4 text-center">
                                                        <p class="text-white font-bold">{{ $work->title }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900">{{ $work->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($work->created_at)->format('M d, Y') }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No books uploaded yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Document ready function
    $(document).ready(function() {
        // User dropdown menu toggle
        $('#user-dropdown-button').on('click', function() {
            $('#user-dropdown-menu').toggleClass('hidden');
            $('#dropdown-arrow').toggleClass('rotate-180');
        });
        
        // Close dropdown when clicking elsewhere
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#user-dropdown-container').length) {
                $('#user-dropdown-menu').addClass('hidden');
                $('#dropdown-arrow').removeClass('rotate-180');
            }
        });
        
        // Mobile menu toggle
        $('#mobile-menu-button').on('click', function() {
            $('#mobile-menu').toggleClass('hidden');
        });
    });
</script>
@endsection
