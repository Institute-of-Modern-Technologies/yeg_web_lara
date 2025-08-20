@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit School</h1>
        <a href="{{ route('admin.schools.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to Schools</span>
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-school mr-2 text-primary"></i>
                <span>Edit School: {{ $school->name }}</span>
            </h2>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.schools.update', $school->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- School Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">School Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('name') border-red-500 @enderror" value="{{ old('name', $school->name) }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('phone') border-red-500 @enderror" value="{{ old('phone', $school->phone) }}" required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email (Optional) -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-gray-400">(Optional)</span></label>
                        <input type="email" name="email" id="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('email') border-red-500 @enderror" value="{{ old('email', $school->email) }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                        <input type="text" name="location" id="location" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('location') border-red-500 @enderror" value="{{ old('location', $school->location) }}" required>
                        @error('location')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- GPS Coordinates (Optional) -->
                    <div>
                        <label for="gps_coordinates" class="block text-sm font-medium text-gray-700 mb-1">GPS Coordinates <span class="text-gray-400">(Optional)</span></label>
                        <input type="text" name="gps_coordinates" id="gps_coordinates" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('gps_coordinates') border-red-500 @enderror" value="{{ old('gps_coordinates', $school->gps_coordinates) }}" placeholder="e.g., 5.6037, -0.1870">
                        @error('gps_coordinates')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Owner's Name -->
                    <div>
                        <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">Owner's Name <span class="text-red-500">*</span></label>
                        <input type="text" name="owner_name" id="owner_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('owner_name') border-red-500 @enderror" value="{{ old('owner_name', $school->owner_name) }}" required>
                        @error('owner_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Average Number of Students (Optional) -->
                    <div>
                        <label for="avg_students" class="block text-sm font-medium text-gray-700 mb-1">Average Number of Students <span class="text-gray-400">(Optional)</span></label>
                        <input type="number" name="avg_students" id="avg_students" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('avg_students') border-red-500 @enderror" value="{{ old('avg_students', $school->avg_students) }}">
                        @error('avg_students')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('status') border-red-500 @enderror" required>
                            <option value="pending" {{ old('status', $school->status) == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="approved" {{ old('status', $school->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status', $school->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- School Logo (Optional) -->
                    <div class="md:col-span-2">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">School Logo <span class="text-gray-400">(Optional)</span></label>
                        <div class="mt-2">
                            <!-- Simplified upload area -->
                            <div class="max-w-xl border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
                                <div class="flex justify-center mb-4">
                                    <!-- Image Preview -->
                                    <x-image id="image-preview" class="w-32 h-32 object-cover border rounded-md {{ $school->logo ? '' : 'hidden' }}" src="{{ $school->logo ? 'storage/' . $school->logo : '' }}" alt="{{ $school->name }} Logo" />
                                    
                                    <!-- Default Icon (Shown when no image) -->
                                    <div id="default-preview" class="w-32 h-32 flex items-center justify-center bg-white border border-gray-300 rounded-md {{ $school->logo ? 'hidden' : '' }}">
                                        <i class="fas fa-school text-gray-400 text-4xl"></i>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <!-- File Input With Label -->
                                    <label class="inline-block px-4 py-2 bg-white border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <span class="text-sm text-gray-700">{{ $school->logo ? 'Change Logo' : 'Choose Logo File' }}</span>
                                        <input type="file" name="logo" id="logo" class="hidden" accept="image/*" onchange="showPreview(this)">
                                    </label>
                                    
                                    <p class="text-xs text-gray-500 mt-2">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                    
                                    <!-- Selected Filename -->
                                    <p id="selected-file" class="mt-2 text-sm text-gray-600 hidden"></p>
                                </div>
                            </div>
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end">
                    <button type="button" onclick="window.location.href='{{ route('admin.schools.index') }}'" class="mr-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                        Update School
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Very simple, direct image preview function
    function showPreview(input) {
        const preview = document.getElementById('image-preview');
        const defaultPreview = document.getElementById('default-preview');
        const selectedFile = document.getElementById('selected-file');
        
        if (input.files && input.files[0]) {
            // Update selected filename display
            selectedFile.textContent = input.files[0].name;
            selectedFile.classList.remove('hidden');
            
            // Create a URL for the file
            const objectUrl = URL.createObjectURL(input.files[0]);
            
            // Set the image source
            preview.src = objectUrl;
            
            // Show image preview, hide default icon
            preview.classList.remove('hidden');
            defaultPreview.classList.add('hidden');
            
            // Log success for debugging
            console.log('Preview image set to:', objectUrl);
            
            // Add event listeners for image loading success/failure
            preview.onload = function() {
                console.log('Image loaded successfully');
            };
            
            preview.onerror = function() {
                console.error('Failed to load image');
                preview.classList.add('hidden');
                defaultPreview.classList.remove('hidden');
                
                // Show error message
                selectedFile.textContent = 'Error loading image. Please try another file.';
                selectedFile.classList.add('text-red-500');
            };
        } else {
            // Reset to default state if no file selected
            preview.classList.add('hidden');
            defaultPreview.classList.remove('hidden');
            selectedFile.classList.add('hidden');
        }
    }
</script>
@endsection
