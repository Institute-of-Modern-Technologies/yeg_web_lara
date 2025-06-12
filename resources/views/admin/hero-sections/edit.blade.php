@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.hero-sections.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to Hero Sections</span>
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Hero Section</h1>
        <p class="mt-1 text-sm text-gray-500">Update this hero section's details and appearance</p>
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
                <i class="fas fa-edit mr-2 text-primary"></i>
                <span>Hero Section Details</span>
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.hero-sections.update', $heroSection->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Info Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="col-span-1">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $heroSection->title) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>

                        <!-- Display Order -->
                        <div class="col-span-1">
                            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                            <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $heroSection->display_order) }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <p class="mt-1 text-xs text-gray-500">Determines the display order on the website (lower numbers appear first)</p>
                        </div>
                    </div>

                    <!-- Subtitle -->
                    <div class="mt-4">
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <textarea name="subtitle" id="subtitle" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('subtitle', $heroSection->subtitle) }}</textarea>
                    </div>
                    
                    <!-- Brand Text -->
                    <div class="mt-4">
                        <label for="brand_text" class="block text-sm font-medium text-gray-700 mb-1">Brand Text</label>
                        <input type="text" name="brand_text" id="brand_text" value="{{ old('brand_text', $heroSection->brand_text ?? 'Young Experts Group') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <p class="mt-1 text-xs text-gray-500">Optional branded text displayed in highlighted color. Default: "Young Experts Group"</p>
                    </div>

                    <!-- Status -->
                    <div class="mt-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $heroSection->is_active) ? 'checked' : '' }} class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active (will be displayed on the website)</label>
                        </div>
                    </div>
                </div>

                <!-- Image Upload Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Hero Image</h3>
                    
                    <!-- Current Image -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <div class="w-full max-w-md mx-auto overflow-hidden rounded-lg border border-gray-200">
                            <img src="{{ asset('storage/' . $heroSection->image_path) }}" alt="{{ $heroSection->title }}" class="w-full h-48 object-cover">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Change Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden mb-3">
                                    <img src="#" alt="Preview" class="mx-auto h-32 object-cover rounded">
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
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB (leave empty to keep current image)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Button and Styling Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Button & Styling</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Button Text -->
                        <div>
                            <label for="button_text" class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                            <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $heroSection->button_text) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <p class="mt-1 text-xs text-gray-500">Leave empty for no button</p>
                        </div>

                        <!-- Button Link -->
                        <div>
                            <label for="button_link" class="block text-sm font-medium text-gray-700 mb-1">Button Link</label>
                            <input type="text" name="button_link" id="button_link" value="{{ old('button_link', $heroSection->button_link) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                        <h3 class="font-medium text-gray-800 mb-4">Text Styling</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                            <!-- Text Position -->
                            <div>
                                <label for="text_position" class="block text-sm font-medium text-gray-700 mb-1">Text Position</label>
                                <select name="text_position" id="text_position" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    <option value="top" {{ old('text_position', $heroSection->text_position) == 'top' ? 'selected' : '' }}>Top</option>
                                    <option value="middle" {{ old('text_position', $heroSection->text_position) == 'middle' ? 'selected' : '' }}>Middle</option>
                                    <option value="bottom" {{ old('text_position', $heroSection->text_position) == 'bottom' || old('text_position', $heroSection->text_position) == null ? 'selected' : '' }}>Bottom</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Colors Section -->
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Text Colors</h4>
                            
                            <!-- Title Color -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                <div>
                                    <label for="title_color" class="block text-xs text-gray-500 mb-1">Title Color</label>
                                    <div class="flex items-center">
                                        <input type="color" name="title_color" id="title_color" value="{{ old('title_color', $heroSection->title_color ?? '#ffffff') }}" class="h-8 w-8 mr-2 border-0 rounded p-0">
                                        <input type="text" value="{{ old('title_color', $heroSection->title_color ?? '#ffffff') }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" id="title_color_hex" oninput="document.getElementById('title_color').value = this.value">
                                    </div>
                                </div>
                                
                                <!-- Subtitle Color -->
                                <div>
                                    <label for="subtitle_color" class="block text-xs text-gray-500 mb-1">Subtitle Color</label>
                                    <div class="flex items-center">
                                        <input type="color" name="subtitle_color" id="subtitle_color" value="{{ old('subtitle_color', $heroSection->subtitle_color ?? '#ffffff') }}" class="h-8 w-8 mr-2 border-0 rounded p-0">
                                        <input type="text" value="{{ old('subtitle_color', $heroSection->subtitle_color ?? '#ffffff') }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" id="subtitle_color_hex" oninput="document.getElementById('subtitle_color').value = this.value">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Brand Text Color & Default Text Color -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="brand_text_color" class="block text-xs text-gray-500 mb-1">Brand Text Color</label>
                                    <div class="flex items-center">
                                        <input type="color" name="brand_text_color" id="brand_text_color" value="{{ old('brand_text_color', $heroSection->brand_text_color ?? '#ffcb05') }}" class="h-8 w-8 mr-2 border-0 rounded p-0">
                                        <input type="text" value="{{ old('brand_text_color', $heroSection->brand_text_color ?? '#ffcb05') }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" id="brand_text_color_hex" oninput="document.getElementById('brand_text_color').value = this.value">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Default: Yellow (#ffcb05)</p>
                                </div>
                                
                                <!-- Default Text Color -->
                                <div>
                                    <label for="text_color" class="block text-xs text-gray-500 mb-1">Default Text Color</label>
                                    <div class="flex items-center">
                                        <input type="color" name="text_color" id="text_color" value="{{ old('text_color', $heroSection->text_color ?? '#ffffff') }}" class="h-8 w-8 mr-2 border-0 rounded p-0">
                                        <input type="text" value="{{ old('text_color', $heroSection->text_color ?? '#ffffff') }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" id="text_color_hex" oninput="document.getElementById('text_color').value = this.value">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Overlay Color -->
                        <div>
                            <label for="overlay_color" class="block text-sm font-medium text-gray-700 mb-1">Overlay Color</label>
                            <div class="flex items-center">
                                <input type="color" name="overlay_color" id="overlay_color" value="{{ old('overlay_color', $heroSection->overlay_color ?? '#000000') }}" class="h-8 w-8 mr-2 border-0 rounded p-0">
                                <input type="text" value="{{ old('overlay_color', $heroSection->overlay_color ?? '#000000') }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" id="overlay_color_hex" oninput="document.getElementById('overlay_color').value = this.value">
                            </div>
                        </div>

                        <!-- Overlay Opacity -->
                        <div>
                            <label for="overlay_opacity" class="block text-sm font-medium text-gray-700 mb-1">Overlay Opacity</label>
                            <div class="flex items-center">
                                <input type="range" name="overlay_opacity" id="overlay_opacity" min="0" max="1" step="0.1" value="{{ old('overlay_opacity', $heroSection->overlay_opacity) }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <span class="ml-2 text-sm text-gray-500" id="opacity-value">{{ $heroSection->overlay_opacity }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.hero-sections.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                        Update Hero Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.querySelector('img').src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Update overlay opacity value display
        const opacityRange = document.getElementById('overlay_opacity');
        const opacityValue = document.getElementById('opacity-value');
        
        opacityRange.addEventListener('input', function() {
            opacityValue.textContent = this.value;
        });
        
        // Setup color picker syncing
        function setupColorPicker(colorId, hexId) {
            const colorPicker = document.getElementById(colorId);
            const hexInput = document.getElementById(hexId);
            
            if (colorPicker && hexInput) {
                colorPicker.addEventListener('input', function() {
                    hexInput.value = this.value;
                });
                
                hexInput.addEventListener('input', function() {
                    colorPicker.value = this.value;
                });
            }
        }
        
        // Setup all color pickers
        setupColorPicker('text_color', 'text_color_hex');
        setupColorPicker('title_color', 'title_color_hex');
        setupColorPicker('subtitle_color', 'subtitle_color_hex');
        setupColorPicker('brand_text_color', 'brand_text_color_hex');
        setupColorPicker('overlay_color', 'overlay_color_hex');
        });
    });
</script>
@endpush
@endsection
