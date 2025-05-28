@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.happenings.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to Happenings</span>
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Happening</h1>
        <p class="mt-1 text-sm text-gray-500">Update details for "{{ $happening->title }}"</p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
        <div class="font-medium">Please fix the following errors:</div>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-newspaper mr-2 text-primary"></i>
                <span>Happening Details</span>
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.happenings.update', $happening->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Info Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $happening->title) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                        </div>

                        <!-- Author Name -->
                        <div class="col-span-1">
                            <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Author Name</label>
                            <input type="text" name="author_name" id="author_name" value="{{ old('author_name', $happening->author_name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>

                        <!-- Category -->
                        <div class="col-span-1">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" name="category" id="category" value="{{ old('category', $happening->category) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="e.g. News, Event, Announcement">
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mt-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                        <textarea name="content" id="content" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>{{ old('content', $happening->content) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Published Date -->
                        <div class="col-span-1">
                            <label for="published_date" class="block text-sm font-medium text-gray-700 mb-1">Published Date <span class="text-red-500">*</span></label>
                            <input type="date" name="published_date" id="published_date" value="{{ old('published_date', $happening->published_date->format('Y-m-d')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                        </div>

                        <!-- Display Order -->
                        <div class="col-span-1">
                            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                            <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $happening->display_order) }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <p class="mt-1 text-xs text-gray-500">Determines the display order on the website (lower numbers appear first)</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mt-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $happening->is_active) ? 'checked' : '' }} class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active (will be displayed on the website)</label>
                        </div>
                    </div>
                </div>

                <!-- Media Upload Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Media</h3>
                    
                    <!-- Current Media -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Media</label>
                        <div class="rounded-lg overflow-hidden w-full max-w-md border border-gray-300 bg-gray-100">
                            @if($happening->media_type == 'image')
                                <img src="{{ asset('storage/' . $happening->media_path) }}" alt="{{ $happening->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="flex items-center justify-center h-48 bg-gray-900 text-white p-4">
                                    <i class="fas fa-play-circle text-3xl mr-2"></i>
                                    <span>Video file: {{ basename($happening->media_path) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Media Type -->
                    <div class="mb-4">
                        <label for="media_type" class="block text-sm font-medium text-gray-700 mb-1">Media Type <span class="text-red-500">*</span></label>
                        <select name="media_type" id="media_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            <option value="image" {{ old('media_type', $happening->media_type) == 'image' ? 'selected' : '' }}>Image</option>
                            <option value="video" {{ old('media_type', $happening->media_type) == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="media" class="block text-sm font-medium text-gray-700 mb-1">Upload New Media</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div id="media-preview" class="hidden mb-3">
                                    <img src="#" alt="Preview" class="mx-auto h-32 object-cover rounded">
                                </div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4h-12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="media" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-700 focus-within:outline-none">
                                        <span>Upload new media</span>
                                        <input id="media" name="media" type="file" class="sr-only" accept="image/*,video/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF, MP4, WEBM up to 10MB</p>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Leave blank to keep current media</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.happenings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                        Update Happening
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Media preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mediaInput = document.getElementById('media');
        const mediaPreview = document.getElementById('media-preview');
        const mediaTypeSelect = document.getElementById('media_type');
        
        mediaInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const reader = new FileReader();
                
                if (file.type.includes('image')) {
                    // For image files
                    reader.onload = function(e) {
                        mediaPreview.querySelector('img').src = e.target.result;
                        mediaPreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                    mediaTypeSelect.value = 'image';
                } else if (file.type.includes('video')) {
                    // Force media type to video if a video file is selected
                    mediaTypeSelect.value = 'video';
                    
                    // Show a video thumbnail placeholder
                    mediaPreview.querySelector('img').src = 'https://via.placeholder.com/150?text=Video';
                    mediaPreview.classList.remove('hidden');
                }
            }
        });
    });
</script>
@endpush
@endsection
