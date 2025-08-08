@extends('layouts.student-unified')

@section('title', 'My Work Portfolio - YEG Student Portal')

@section('content')

    <!-- Hero Section -->
    <div class="relative bg-gray-900 text-white">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80" 
                 alt="Students working" 
                 class="w-full h-full object-cover opacity-30">
        </div>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">My Work Portfolio</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
                    Showcase your creativity and achievements. Upload, organize, and share your best work.
                </p>
                <a href="{{ route('student.mywork.create') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-[#950713] font-semibold rounded-lg hover:bg-gray-100 transition-colors duration-200 shadow-lg">
                    <i class="fas fa-plus mr-3"></i> Upload New Work
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Section -->
            <div class="py-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#950713]">{{ count($works['image']) }}</div>
                        <div class="text-gray-600 mt-1">Images</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#950713]">{{ count($works['video']) }}</div>
                        <div class="text-gray-600 mt-1">Videos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#950713]">{{ count($works['website']) }}</div>
                        <div class="text-gray-600 mt-1">Websites</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#950713]">{{ count($works['book']) }}</div>
                        <div class="text-gray-600 mt-1">Books</div>
                    </div>
                </div>
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
                            @foreach(array_slice($works['image'], 0, 4) as $work)
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
                        
                        @if(count($works['image']) > 4)
                            <div class="mt-6 text-center">
                                <a href="{{ route('student.mywork.category', 'image') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-[#950713] text-white font-semibold rounded-lg hover:bg-red-800 transition-colors duration-200">
                                    <i class="fas fa-images mr-2"></i>
                                    View All Images ({{ count($works['image']) }})
                                </a>
                            </div>
                        @endif
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
                            @foreach(array_slice($works['video'], 0, 4) as $work)
                                <div class="bg-gray-50 rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
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
                            @endforeach
                        </div>
                        
                        @if(count($works['video']) > 4)
                            <div class="mt-6 text-center">
                                <a href="{{ route('student.mywork.category', 'video') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-[#950713] text-white font-semibold rounded-lg hover:bg-red-800 transition-colors duration-200">
                                    <i class="fas fa-video mr-2"></i>
                                    View All Videos ({{ count($works['video']) }})
                                </a>
                            </div>
                        @endif
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
                            @foreach(array_slice($works['website'], 0, 4) as $work)
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
                        
                        @if(count($works['website']) > 4)
                            <div class="mt-6 text-center">
                                <a href="{{ route('student.mywork.category', 'website') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-[#950713] text-white font-semibold rounded-lg hover:bg-red-800 transition-colors duration-200">
                                    <i class="fas fa-globe mr-2"></i>
                                    View All Websites ({{ count($works['website']) }})
                                </a>
                            </div>
                        @endif
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
                            @foreach(array_slice($works['book'], 0, 4) as $work)
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
                        
                        @if(count($works['book']) > 4)
                            <div class="mt-6 text-center">
                                <a href="{{ route('student.mywork.category', 'book') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-[#950713] text-white font-semibold rounded-lg hover:bg-red-800 transition-colors duration-200">
                                    <i class="fas fa-book mr-2"></i>
                                    View All Books ({{ count($works['book']) }})
                                </a>
                            </div>
                        @endif
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
