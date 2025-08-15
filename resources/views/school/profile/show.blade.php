@extends('layouts.school')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">My Profile</h1>
        <p class="text-gray-400">Manage your account settings and school information</p>
    </div>

    @if (session('success'))
        <div class="bg-green-900/50 border-l-4 border-green-500 text-green-300 p-4 mb-6 rounded-r-lg" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 mb-6 rounded-r-lg" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl overflow-hidden shadow-2xl">
        <form action="{{ route('school.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="md:flex">
                <!-- Profile Photo Section -->
                <div class="md:w-1/3 bg-gray-900/50 p-6 border-r border-gray-700">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-700 border-4 border-gray-600 shadow-lg">
                                @if($user->profile_photo)
                                    <img id="profile-preview" 
                                         src="{{ asset('uploads/profile-photos/' . rawurlencode($user->profile_photo)) }}?v={{ time() }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML = '<div class=\'w-full h-full flex items-center justify-center bg-gradient-to-br from-green-500 to-blue-600 text-white text-4xl font-bold\'>{{ substr($user->name, 0, 1) }}</div>'">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-500 to-blue-600 text-white text-4xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div id="photo-selected-message" class="hidden absolute bottom-0 left-0 right-0 text-xs text-green-400 text-center bg-gray-800/90 py-1"></div>
                            </div>
                            <label for="profile_photo" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-60 text-white rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-all duration-200">
                                <span><i class="fas fa-camera mr-2"></i> Change</span>
                                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-medium text-white">{{ $user->name }}</h3>
                            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                            <p class="text-blue-400 text-sm font-medium">{{ $school->name }}</p>
                        </div>
                        <p class="text-xs text-gray-500 text-center">Click on the image to change your profile photo</p>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="md:w-2/3 p-6">
                    <div class="space-y-6">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <i class="fas fa-user mr-2 text-green-400"></i>
                                Personal Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                                           class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                    @error('username')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- School Information -->
                        <div class="border-t border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <i class="fas fa-school mr-2 text-blue-400"></i>
                                School Information
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="school_name" class="block text-sm font-medium text-gray-300 mb-2">School Name</label>
                                    <input type="text" name="school_name" id="school_name" value="{{ old('school_name', $school->name) }}" required
                                           class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    @error('school_name')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="school_phone" class="block text-sm font-medium text-gray-300 mb-2">School Phone</label>
                                        <input type="tel" name="school_phone" id="school_phone" value="{{ old('school_phone', $school->phone) }}"
                                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Enter school phone number">
                                        @error('school_phone')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="school_address" class="block text-sm font-medium text-gray-300 mb-2">School Address</label>
                                        <textarea name="school_address" id="school_address" rows="3"
                                                  class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                  placeholder="Enter school address">{{ old('school_address', $school->address) }}</textarea>
                                        @error('school_address')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Change -->
                        <div class="border-t border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-white mb-4 flex items-center">
                                <i class="fas fa-lock mr-2 text-purple-400"></i>
                                Change Password
                            </h3>
                            <p class="text-sm text-gray-400 mb-4">Leave blank if you don't want to change your password</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">Current Password</label>
                                    <input type="password" name="current_password" id="current_password"
                                           class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Enter current password">
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                                        <input type="password" name="password" id="password"
                                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Enter new password">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="border-t border-gray-700 pt-6">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    All changes will be saved immediately
                                </div>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile photo preview
    const profilePhotoInput = document.getElementById('profile_photo');
    const profilePreview = document.getElementById('profile-preview');
    const photoSelectedMessage = document.getElementById('photo-selected-message');

    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (profilePreview) {
                        profilePreview.src = e.target.result;
                        profilePreview.style.display = 'block';
                    } else {
                        // If no existing image, create one
                        const container = document.querySelector('.w-32.h-32.rounded-full');
                        container.innerHTML = `<img id="profile-preview" src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover">`;
                    }
                    
                    // Show selected message
                    photoSelectedMessage.textContent = `Selected: ${file.name}`;
                    photoSelectedMessage.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Password confirmation validation
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const currentPasswordInput = document.getElementById('current_password');

    function validatePasswords() {
        if (passwordInput.value || passwordConfirmInput.value) {
            if (passwordInput.value !== passwordConfirmInput.value) {
                passwordConfirmInput.setCustomValidity('Passwords do not match');
            } else {
                passwordConfirmInput.setCustomValidity('');
            }
        } else {
            passwordConfirmInput.setCustomValidity('');
        }
    }

    if (passwordInput && passwordConfirmInput) {
        passwordInput.addEventListener('input', validatePasswords);
        passwordConfirmInput.addEventListener('input', validatePasswords);
    }

    // Require current password if new password is entered
    if (passwordInput && currentPasswordInput) {
        passwordInput.addEventListener('input', function() {
            if (this.value) {
                currentPasswordInput.required = true;
                currentPasswordInput.parentElement.querySelector('label').innerHTML = 'Current Password <span class="text-red-400">*</span>';
            } else {
                currentPasswordInput.required = false;
                currentPasswordInput.parentElement.querySelector('label').innerHTML = 'Current Password';
            }
        });
    }

    // Form submission with loading state
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    if (form && submitButton) {
        form.addEventListener('submit', function() {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
        });
    }
});
</script>
@endsection
