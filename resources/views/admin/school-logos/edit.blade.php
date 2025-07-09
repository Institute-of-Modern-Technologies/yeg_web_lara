@extends('admin.dashboard')

@section('title', 'Edit School Logo')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit School Logo</h1>
        <a href="{{ route('admin.school-logos.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to List</span>
        </a>
    </div>
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center">
            <i class="fas fa-edit mr-2 text-primary"></i>
            <h2 class="font-semibold text-gray-800">Edit School Logo: {{ $schoolLogo->name }}</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.school-logos.update', $schoolLogo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">School Name <span class="text-red-500">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <input type="text" class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('name') border-red-300 @enderror" 
                                id="name" name="name" value="{{ old('name', $schoolLogo->name) }}" placeholder="Enter school name" required>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-sort-numeric-down text-gray-400"></i>
                            </div>
                            <input type="number" class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('display_order') border-red-300 @enderror" 
                                id="display_order" name="display_order" value="{{ old('display_order', $schoolLogo->display_order) }}" min="0" placeholder="0">
                        </div>
                        @error('display_order')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i>Lower numbers appear first.</p>
                    </div>
                    
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-primary transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="logo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none">
                                        <span class="px-1">Change logo</span>
                                        <input id="logo" name="logo" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Recommended: 200×200px (Max 2MB)<br>
                                    PNG, JPG, GIF, SVG
                                </p>
                            </div>
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        
                        <div id="current-logo-container" class="mt-3">
                            <p class="text-sm font-medium text-gray-700 mb-1"><i class="fas fa-image mr-1"></i>Current Logo:</p>
                            <div class="h-32 w-auto border rounded p-1 bg-gray-50 inline-block">
                                <img src="{{ $schoolLogo->logo_url }}" alt="{{ $schoolLogo->name }}" class="h-full w-auto object-contain">
                            </div>
                        </div>
                        
                        <div id="new-logo-preview-container" class="mt-3 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-1"><i class="fas fa-eye mr-1"></i>New Logo Preview:</p>
                            <div class="h-32 w-auto border rounded p-1 bg-gray-50 inline-block">
                                <img id="new-logo-preview" class="h-full w-auto object-contain" alt="New logo preview">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center h-full pt-8">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" 
                                id="is_active" name="is_active" value="1" {{ old('is_active', $schoolLogo->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/25 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Active (show in marquee)</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex items-center justify-start pt-4 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Update School Logo</span>
                    </button>
                    <a href="{{ route('admin.school-logos.index') }}" class="ml-4 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logo preview
    document.addEventListener('DOMContentLoaded', function() {
        const logoInput = document.getElementById('logo');
        const previewContainer = document.getElementById('new-logo-preview-container');
        const previewImage = document.getElementById('new-logo-preview');
        
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.classList.remove('hidden');
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.classList.add('hidden');
            }
        });
    });
</script>
@endpush
