@extends('layouts.student-unified')

@section('content')

    <!-- Main Content -->
    <div class="pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header with Back Button -->
            <div class="py-8 flex items-center">
                <a href="{{ route('student.mywork') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Upload New Work</h1>
            </div>

            <!-- Upload Form -->
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-3xl mx-auto">
                <form action="{{ route('student.mywork.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#950713] focus:border-[#950713]">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 font-medium mb-2">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#950713] focus:border-[#950713]">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Work Type Selection -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Type of Work</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="cursor-pointer bg-white border border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition-colors work-type-option">
                                <input type="radio" name="type" value="image" class="sr-only work-type-radio" data-target="image" checked>
                                <i class="fas fa-image text-3xl mb-2 text-gray-400 transition-colors"></i>
                                <div class="font-medium">Image</div>
                                <div class="text-xs text-gray-500">JPG, PNG, GIF</div>
                            </label>
                            
                            <label class="cursor-pointer bg-white border border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition-colors work-type-option">
                                <input type="radio" name="type" value="video" class="sr-only work-type-radio" data-target="video">
                                <i class="fas fa-video text-3xl mb-2 text-gray-400 transition-colors"></i>
                                <div class="font-medium">Video</div>
                                <div class="text-xs text-gray-500">MP4, WebM</div>
                            </label>
                            
                            <label class="cursor-pointer bg-white border border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition-colors work-type-option">
                                <input type="radio" name="type" value="website" class="sr-only work-type-radio" data-target="website">
                                <i class="fas fa-globe text-3xl mb-2 text-gray-400 transition-colors"></i>
                                <div class="font-medium">Website</div>
                                <div class="text-xs text-gray-500">URL Link</div>
                            </label>
                            
                            <label class="cursor-pointer bg-white border border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition-colors work-type-option">
                                <input type="radio" name="type" value="book" class="sr-only work-type-radio" data-target="book">
                                <i class="fas fa-book text-3xl mb-2 text-gray-400 transition-colors"></i>
                                <div class="font-medium">Book</div>
                                <div class="text-xs text-gray-500">PDF</div>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- File Upload - Image, Video, Book -->
                    <div id="file-upload" class="mb-6">
                        <label for="file" class="block text-gray-700 font-medium mb-2">Upload File</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-md p-6 flex flex-col items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-500 mb-2">Drag and drop your file here, or click to select</p>
                            <p class="text-xs text-gray-400">Maximum file size: 10MB</p>
                            <input type="file" id="file" name="file" class="hidden">
                            <button type="button" id="browse-button" class="mt-4 px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium">
                                Browse Files
                            </button>
                            <div id="file-preview" class="hidden w-full mt-4">
                                <div class="flex items-center p-2 bg-gray-50 border rounded-md">
                                    <i class="fas fa-file mr-2 text-[#950713]"></i>
                                    <span id="file-name" class="text-sm truncate flex-grow"></span>
                                    <button type="button" id="remove-file" class="text-gray-500 hover:text-red-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('file')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Website URL Input -->
                    <div id="website-input" class="mb-6 hidden">
                        <label for="website_url" class="block text-gray-700 font-medium mb-2">Website URL</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-link"></i>
                            </span>
                            <input type="url" id="website_url" name="website_url" value="{{ old('website_url') }}" placeholder="https://example.com"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-[#950713] focus:border-[#950713] border border-gray-300">
                        </div>
                        @error('website_url')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-[#950713] text-white rounded-md hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#950713]">
                            Upload Work
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Work type selection
        $('.work-type-option').on('click', function() {
            // Remove active class from all options
            $('.work-type-option').removeClass('border-[#950713] bg-red-50');
            $('.work-type-option i').removeClass('text-[#950713]').addClass('text-gray-400');
            
            // Add active class to selected option
            $(this).addClass('border-[#950713] bg-red-50');
            $(this).find('i').removeClass('text-gray-400').addClass('text-[#950713]');
            
            // Show/hide appropriate inputs based on type
            const type = $(this).find('input').data('target');
            
            if (type === 'website') {
                $('#file-upload').addClass('hidden');
                $('#website-input').removeClass('hidden');
            } else {
                $('#file-upload').removeClass('hidden');
                $('#website-input').addClass('hidden');
            }
        });
        
        // File input handling
        $('#browse-button').on('click', function() {
            $('#file').click();
        });
        
        $('#file').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                $('#file-name').text(file.name);
                $('#file-preview').removeClass('hidden');
            }
        });
        
        $('#remove-file').on('click', function() {
            $('#file').val('');
            $('#file-preview').addClass('hidden');
        });
        
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
    });
</script>
@endsection
