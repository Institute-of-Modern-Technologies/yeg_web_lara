@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
        <p class="text-gray-600">Manage your account settings and profile information</p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="md:flex">
                <!-- Profile Photo Section -->
                <div class="md:w-1/3 bg-gray-50 p-6 border-r border-gray-200">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-lg">
                                @if($user->profile_photo)
                                    <x-image id="profile-preview" 
                                         src="uploads/profile-photos/{{ rawurlencode($user->profile_photo) }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML = '<div class=\'w-full h-full flex items-center justify-center bg-primary/10 text-primary text-4xl font-bold\'>{{ substr($user->name, 0, 1) }}</div>'"
                                         :attributes="['data-timestamp' => time()]" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary text-4xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div id="photo-selected-message" class="hidden absolute bottom-0 left-0 right-0 text-xs text-green-600 text-center bg-white/80 py-1"></div>
                            </div>
                            <label for="profile_photo" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 text-white rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-all duration-200">
                                <span><i class="fas fa-camera mr-2"></i> Change</span>
                                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">{{ $user->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                        </div>
                        <p class="text-xs text-gray-400 text-center">Click on the image to change your profile photo</p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">Account Information</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500">Member since</span>
                                <p class="text-sm font-medium">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Last updated</span>
                                <p class="text-sm font-medium">{{ $user->updated_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Role</span>
                                <p class="text-sm font-medium">Administrator</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details Section -->
                <div class="md:w-2/3 p-6">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Personal Information</h3>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ $user->name }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ $user->email }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Change Password</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    @error('current_password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                        <input type="password" name="password" id="password" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                        @error('password')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    </div>
                                </div>
                                
                                <p class="text-sm text-gray-500 italic">Leave password fields empty if you don't want to change it.</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Enhanced profile photo preview with better handling
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('profile_photo');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('Please select an image file');
                        return;
                    }
                    
                    // Show file selected message
                    const messageEl = document.getElementById('photo-selected-message');
                    if (messageEl) {
                        messageEl.textContent = 'New photo selected: ' + file.name;
                        messageEl.classList.remove('hidden');
                    }
                    
                    // Update preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewImg = document.getElementById('profile-preview');
                        const previewContainer = previewImg.parentElement;
                        
                        // Remove any fallback initials if present
                        const fallbackInitials = previewContainer.querySelector('div.flex.items-center.justify-center');
                        if (fallbackInitials) {
                            fallbackInitials.remove();
                        }
                        
                        // Ensure image is displayed
                        if (previewImg) {
                            previewImg.style.display = 'block';
                            previewImg.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
