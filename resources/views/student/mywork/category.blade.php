@extends('layouts.student-clean')

@section('title', $typeData[$type]['title'] . ' - My Work - YEG Student Portal')

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

    <!-- Hero Section -->
    <div class="relative bg-gray-900 text-white">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80" 
                 alt="Creative workspace" 
                 class="w-full h-full object-cover opacity-30">
        </div>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex items-center mb-4">
                <a href="{{ route('student.mywork') }}" class="mr-4 text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex items-center space-x-3">
                    <i class="{{ $typeData[$type]['icon'] }} text-2xl"></i>
                    <span class="text-lg opacity-80 capitalize">{{ $type }}</span>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $typeData[$type]['title'] }}</h1>
            <p class="text-xl opacity-90 max-w-3xl">{{ $typeData[$type]['description'] }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Content Header -->
            <div class="py-8 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $works->total() }} {{ ucfirst($type) }}{{ $works->total() !== 1 ? 's' : '' }}</h2>
                    <p class="text-gray-600 mt-1">Showing {{ $works->firstItem() ?? 0 }} to {{ $works->lastItem() ?? 0 }} of {{ $works->total() }} results</p>
                </div>
                <a href="{{ route('student.mywork.create') }}" 
                   class="bg-[#950713] hover:bg-red-800 text-white px-4 py-2 rounded-md flex items-center">
                    <i class="fas fa-plus mr-2"></i> Upload New Work
                </a>
            </div>

            <!-- Works Grid -->
            @if($works->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($works as $work)
                        @if($type === 'image')
                            <!-- Image Card -->
                            <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
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
                        @elseif($type === 'video')
                            <!-- Video Card -->
                            <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                                <div class="h-48 overflow-hidden bg-gray-200 relative group">
                                    <!-- Video Preview -->
                                    <video class="w-full h-full object-cover" muted preload="metadata" onmouseover="this.play()" onmouseout="this.pause(); this.currentTime = 0;">
                                        <source src="{{ $work->getViewUrl() }}#t=0.5" type="video/mp4">
                                        <!-- Fallback thumbnail if video fails to load -->
                                        <img src="{{ $work->getThumbnailUrl() }}" alt="{{ $work->title }}" class="w-full h-full object-cover">
                                    </video>
                                    
                                    <!-- Play Button Overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 group-hover:bg-opacity-20 transition-all duration-200">
                                        <div class="w-16 h-16 bg-[#950713] bg-opacity-90 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-play text-white text-2xl ml-1"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Click overlay to navigate to detail view -->
                                    <a href="{{ route('student.mywork.show', $work->id) }}" class="absolute inset-0 z-10"></a>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">{{ $work->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($work->created_at)->format('M d, Y') }}</p>
                                    <div class="flex items-center mt-2">
                                        <i class="fas fa-video text-[#950713] mr-2"></i>
                                        <span class="text-xs text-gray-600">Video • Hover to preview</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($type === 'website')
                            <!-- Website Card -->
                            <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
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
                        @elseif($type === 'book')
                            <!-- Book Card -->
                            <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
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
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $works->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <i class="{{ $typeData[$type]['icon'] }} text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No {{ ucfirst($type) }}s Yet</h3>
                        <p class="text-gray-600 mb-6">You haven't uploaded any {{ $type }}s yet. Start building your portfolio!</p>
                        <a href="{{ route('student.mywork.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-[#950713] text-white font-semibold rounded-lg hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Upload Your First {{ ucfirst($type) }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // User dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('user-dropdown-button');
        const dropdownMenu = document.getElementById('user-dropdown-menu');
        const dropdownArrow = document.getElementById('dropdown-arrow');
        
        if (dropdownButton && dropdownMenu) {
            dropdownButton.addEventListener('click', function(e) {
                e.preventDefault();
                dropdownMenu.classList.toggle('hidden');
                dropdownArrow.classList.toggle('rotate-180');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                    dropdownArrow.classList.remove('rotate-180');
                }
            });
        }
        
        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>
@endsection
