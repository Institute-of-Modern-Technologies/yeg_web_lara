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
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header with Back Button -->
            <div class="py-8 flex items-center">
                <a href="{{ route('student.mywork') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $work->title }}</h1>
            </div>

            <!-- Work Display Container -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Media Content -->
                <div class="bg-gray-900">
                    @if($work->type === 'image')
                        <!-- Image Viewer -->
                        <div class="flex justify-center">
                            <img src="{{ $work->getViewUrl() }}" alt="{{ $work->title }}" class="max-h-[80vh] object-contain py-4" id="fullSizeImage">
                        </div>
                    @elseif($work->type === 'video')
                        <!-- Video Player -->
                        <div class="flex justify-center py-4">
                            <video controls class="max-w-full max-h-[80vh]" id="videoPlayer">
                                <source src="{{ $work->getViewUrl() }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @elseif($work->type === 'website')
                        <!-- Website Preview -->
                        <div class="aspect-w-16 aspect-h-9">
                            <iframe src="{{ $work->website_url }}" frameborder="0" class="w-full h-[80vh]" allowfullscreen></iframe>
                        </div>
                    @elseif($work->type === 'book')
                        <!-- PDF Reader -->
                        <div class="flex justify-center bg-gray-900 py-4">
                            <div class="w-full h-[80vh]">
                                <object data="{{ $work->getViewUrl() }}" type="application/pdf" class="w-full h-full">
                                    <p class="text-white text-center py-20">
                                        It appears your browser doesn't support embedded PDFs. 
                                        <a href="{{ $work->getViewUrl() }}" class="text-blue-400 underline" target="_blank">Click here to download the PDF</a>.
                                    </p>
                                </object>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Work Information -->
                <div class="p-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($work->approved) bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                            @if($work->approved) 
                                <i class="fas fa-check-circle mr-1"></i> Approved
                            @else
                                <i class="fas fa-clock mr-1"></i> Pending Approval
                            @endif
                        </span>
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-
                            @if($work->type === 'image') image
                            @elseif($work->type === 'video') video
                            @elseif($work->type === 'website') globe
                            @elseif($work->type === 'book') book
                            @endif
                            mr-1"></i>
                            {{ ucfirst($work->type) }}
                        </span>
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($work->created_at)->format('M d, Y') }}
                        </span>
                    </div>

                    @if($work->description)
                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700">{{ $work->description }}</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-wrap gap-4">
                        @if($work->type === 'image')
                            <a href="{{ $work->getViewUrl() }}" download class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-download mr-2"></i> Download Image
                            </a>
                            <button id="fullscreenBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-expand mr-2"></i> View Fullscreen
                            </button>
                        @elseif($work->type === 'video')
                            <a href="{{ $work->getViewUrl() }}" download class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-download mr-2"></i> Download Video
                            </a>
                            <button id="videoFullscreenBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-expand mr-2"></i> Fullscreen
                            </button>
                        @elseif($work->type === 'website')
                            <a href="{{ $work->website_url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-external-link-alt mr-2"></i> Open in New Tab
                            </a>
                        @elseif($work->type === 'book')
                            <a href="{{ $work->getViewUrl() }}" download class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-download mr-2"></i> Download PDF
                            </a>
                            <a href="{{ $work->getViewUrl() }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-external-link-alt mr-2"></i> Open in New Tab
                            </a>
                        @endif
                        
                        <!-- Delete Button -->
                        <button id="deleteBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-red-600 bg-white hover:bg-red-50">
                            <i class="fas fa-trash-alt mr-2"></i> Delete Work
                        </button>
                    </div>
                    
                    <!-- Delete Confirmation Modal -->
                    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Confirm Delete</h3>
                            <p class="text-gray-700 mb-6">Are you sure you want to delete this work? This action cannot be undone.</p>
                            <div class="flex justify-end gap-4">
                                <button id="cancelDelete" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Cancel
                                </button>
                                <form action="{{ route('student.mywork.delete', $work->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                                        Delete Permanently
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
    <div class="relative w-full max-w-6xl">
        <button id="closeModal" class="absolute top-0 right-0 mt-4 mr-4 text-white hover:text-gray-300">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img src="{{ $work->type === 'image' ? $work->getViewUrl() : '' }}" alt="{{ $work->title }}" class="max-h-screen max-w-full mx-auto" id="modalImage">
    </div>
</div>

@endsection

@section('scripts')
<script>
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
        
        // Image fullscreen modal
        $('#fullscreenBtn').on('click', function() {
            $('#imageModal').removeClass('hidden');
        });
        
        $('#closeModal').on('click', function() {
            $('#imageModal').addClass('hidden');
        });
        
        // Allow clicking on image to open fullscreen for image type
        @if($work->type === 'image')
        $('#fullSizeImage').on('click', function() {
            $('#imageModal').removeClass('hidden');
        });
        @endif
        
        // Close modal with escape key
        $(document).on('keydown', function(e) {
            if (e.key === "Escape") {
                $('#imageModal').addClass('hidden');
                $('#deleteModal').addClass('hidden');
            }
        });
        
        // Delete button functionality
        $('#deleteBtn').on('click', function() {
            $('#deleteModal').removeClass('hidden');
        });
        
        $('#cancelDelete').on('click', function() {
            $('#deleteModal').addClass('hidden');
        });
        
        // Video fullscreen
        @if($work->type === 'video')
        $('#videoFullscreenBtn').on('click', function() {
            if (document.getElementById('videoPlayer').requestFullscreen) {
                document.getElementById('videoPlayer').requestFullscreen();
            } else if (document.getElementById('videoPlayer').webkitRequestFullscreen) {
                document.getElementById('videoPlayer').webkitRequestFullscreen();
            } else if (document.getElementById('videoPlayer').msRequestFullscreen) {
                document.getElementById('videoPlayer').msRequestFullscreen();
            }
        });
        @endif
    });
</script>
@endsection
