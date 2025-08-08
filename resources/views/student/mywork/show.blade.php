@extends('layouts.student-unified')

@section('title', '{{ $work->title }} - My Work - YEG Student Portal')

@section('content')

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
                    @if($work->type === 'image')
                        <i class="fas fa-image text-2xl"></i>
                    @elseif($work->type === 'video')
                        <i class="fas fa-video text-2xl"></i>
                    @elseif($work->type === 'website')
                        <i class="fas fa-globe text-2xl"></i>
                    @elseif($work->type === 'book')
                        <i class="fas fa-book text-2xl"></i>
                    @endif
                    <span class="text-lg opacity-80 capitalize">{{ $work->type }}</span>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $work->title }}</h1>
            @if($work->description)
                <p class="text-xl opacity-90 max-w-3xl">{{ $work->description }}</p>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
