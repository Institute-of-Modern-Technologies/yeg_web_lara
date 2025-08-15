@extends('layouts.student-unified')

@section('title', 'My Profile - Student Portal')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">My Profile</h1>
        <p class="text-gray-600">Manage your account settings and personal information</p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="md:flex">
                <!-- Profile Photo Section -->
                <div class="md:w-1/3 bg-gray-50 p-6 border-r border-gray-200">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-lg">
                                @if($user->profile_photo)
                                    <img id="profile-preview" 
                                         src="{{ asset('uploads/profile-photos/' . rawurlencode($user->profile_photo)) }}?v={{ time() }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML = '<div class=\'w-full h-full flex items-center justify-center bg-teal-100 text-teal-600 text-4xl font-bold\'>{{ substr($user->name, 0, 1) }}</div>'">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-teal-100 text-teal-600 text-4xl font-bold">
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
                        <div class="text-center">
                            <h3 class="text-lg font-medium text-gray-800">{{ $user->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                            @if($student->programType)
                                <p class="text-teal-600 text-sm font-medium">{{ $student->programType->name }}</p>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 text-center">Click on the image to change your profile photo</p>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="md:w-2/3 p-6">
                    <div class="space-y-6">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user mr-2 text-teal-600"></i>
                                Personal Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Optional">
                                    @error('username')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $student->phone) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Enter your phone number">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $student->city) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Enter your city">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-graduation-cap mr-2 text-blue-600"></i>
                                Academic Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Program Type</label>
                                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                                        {{ $student->programType->name ?? 'Not assigned' }}
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Contact your school to change your program</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">School</label>
                                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                                        {{ $student->school->name ?? 'Not assigned' }}
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Your enrolled school</p>
                                </div>
                            </div>

                            @if($student->age)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                                <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                                    {{ $student->age }} years old
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Password Change -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-lock mr-2 text-purple-600"></i>
                                Change Password
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">Leave blank if you don't want to change your password</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                    <input type="password" name="current_password" id="current_password"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Enter current password">
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                        <input type="password" name="password" id="password"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Enter new password">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    All changes will be saved immediately
                                </div>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
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
            console.log('File selected:', file);
            
            if (file) {
                // Check file size (max 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size must be less than 10MB');
                    this.value = '';
                    return;
                }
                
                // Check file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('File loaded for preview');
                    if (profilePreview) {
                        profilePreview.src = e.target.result;
                        profilePreview.style.display = 'block';
                    } else {
                        // If no existing image, create one
                        const container = document.querySelector('.w-32.h-32.rounded-full');
                        container.innerHTML = `<img id="profile-preview" src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover">`;
                    }
                    
                    // Show selected message
                    photoSelectedMessage.textContent = `Selected: ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
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
                currentPasswordInput.parentElement.querySelector('label').innerHTML = 'Current Password <span class="text-red-600">*</span>';
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
