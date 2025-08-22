@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to Testimonials</span>
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Testimonial</h1>
        <p class="mt-1 text-sm text-gray-500">Update details for "{{ $testimonial->name }}"</p>
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
                <i class="fas fa-quote-left mr-2 text-primary"></i>
                <span>Testimonial Details</span>
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Info Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="col-span-1">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                        </div>

                        <!-- Role -->
                        <div class="col-span-1">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <input type="text" name="role" id="role" value="{{ old('role', $testimonial->role) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="e.g. Student, Parent, Teacher">
                        </div>
                    </div>

                    <!-- Institution -->
                    <div class="mt-4">
                        <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                        <input type="text" name="institution" id="institution" value="{{ old('institution', $testimonial->institution) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="e.g. School name, Organization">
                    </div>
                </div>

                <!-- Testimonial Content -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Testimonial Content</h3>
                    
                    <!-- Content -->
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                        <textarea name="content" id="content" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>{{ old('content', $testimonial->content) }}</textarea>
                    </div>

                    <!-- Rating -->
                    <div class="mb-4">
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating <span class="text-red-500">*</span></label>
                        <div class="flex items-center space-x-2">
                            <div id="star-rating" class="flex text-yellow-400 text-2xl cursor-pointer">
                                <span class="star" data-value="1"><i class="fas fa-star"></i></span>
                                <span class="star" data-value="2"><i class="fas fa-star"></i></span>
                                <span class="star" data-value="3"><i class="fas fa-star"></i></span>
                                <span class="star" data-value="4"><i class="fas fa-star"></i></span>
                                <span class="star" data-value="5"><i class="fas fa-star"></i></span>
                            </div>
                            <input type="hidden" name="rating" id="rating" value="{{ old('rating', $testimonial->rating) }}">
                            <span id="rating-text" class="text-sm text-gray-600">{{ $testimonial->rating }} {{ $testimonial->rating == 1 ? 'star' : 'stars' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Image Upload Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Profile Image</h3>
                    
                    <!-- Current Image -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <div class="w-32 h-32 rounded-full overflow-hidden border border-gray-300">
                            <img src="{{ asset($testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload New Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden mb-3">
                                    <!-- The image will be inserted here dynamically -->
                                </div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4h-12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-700 focus-within:outline-none">
                                        <span>Upload a new image</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Leave blank to keep current image</p>
                    </div>
                </div>

                <!-- Additional Settings -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Display Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Display Order -->
                        <div class="col-span-1">
                            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                            <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $testimonial->display_order) }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <p class="mt-1 text-xs text-gray-500">Determines the display order on the website (lower numbers appear first)</p>
                        </div>

                        <!-- Status -->
                        <div class="col-span-1">
                            <div class="flex items-center h-full">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }} class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active (will be displayed on the website)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                        Update Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview functionality with debugging
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        
        console.log('Image input element:', imageInput);
        console.log('Image preview container:', imagePreview);
        
        imageInput.addEventListener('change', function() {
            console.log('File selected:', this.files[0]);
            
            if (this.files && this.files[0]) {
                // Create a new image element
                // First, clear any existing content
                imagePreview.innerHTML = '';
                
                // Create new image element
                const img = document.createElement('img');
                img.alt = 'Preview';
                img.className = 'mx-auto h-32 w-32 object-cover rounded-full';
                
                // Append it to the preview container
                imagePreview.appendChild(img);
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    console.log('Image loaded via FileReader');
                    img.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    console.log('Preview should now be visible');
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Star rating functionality
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        const ratingText = document.getElementById('rating-text');
        
        // Set initial stars
        updateStars(parseInt(ratingInput.value));
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                ratingInput.value = value;
                updateStars(value);
                ratingText.textContent = value + (value === 1 ? ' star' : ' stars');
            });
            
            star.addEventListener('mouseover', function() {
                const value = parseInt(this.dataset.value);
                highlightStars(value);
            });
            
            star.addEventListener('mouseout', function() {
                updateStars(parseInt(ratingInput.value));
            });
        });
        
        function updateStars(count) {
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                if (starValue <= count) {
                    star.innerHTML = '<i class="fas fa-star"></i>';
                } else {
                    star.innerHTML = '<i class="far fa-star"></i>';
                }
            });
        }
        
        function highlightStars(count) {
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                if (starValue <= count) {
                    star.innerHTML = '<i class="fas fa-star"></i>';
                } else {
                    star.innerHTML = '<i class="far fa-star"></i>';
                }
            });
        }
    });
</script>
@endpush
@endsection
