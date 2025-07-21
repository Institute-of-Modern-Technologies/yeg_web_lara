<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Registration - Young Experts Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#950713',
                        secondary: '#ffcb05'
                    }
                }
            }
        }
    </script>
    <script>
        // Function to toggle between existing and new school inputs
        function toggleSchoolSelection() {
            const existingSchoolSelected = document.getElementById('existing_school').checked;
            const existingSchoolDiv = document.getElementById('existing_school_div');
            const newSchoolDiv = document.getElementById('new_school_div');
            const schoolIdSelect = document.getElementById('school_id');
            const schoolNameInput = document.getElementById('school_name');
            
            if (existingSchoolSelected) {
                existingSchoolDiv.classList.remove('hidden');
                newSchoolDiv.classList.add('hidden');
                schoolIdSelect.setAttribute('required', '');
                schoolNameInput.removeAttribute('required');
            } else {
                existingSchoolDiv.classList.add('hidden');
                newSchoolDiv.classList.remove('hidden');
                schoolIdSelect.removeAttribute('required');
                schoolNameInput.setAttribute('required', '');
            }
        }
        
        // Function to validate date of birth
        function validateDateOfBirth() {
            const dobInput = document.getElementById('date_of_birth');
            const dobError = document.getElementById('dob-error');
            const dobValue = new Date(dobInput.value);
            const today = new Date();
            
            // Calculate minimum allowed date (4 years ago from today)
            const minDate = new Date();
            minDate.setFullYear(today.getFullYear() - 4);
            
            // Check if date is in the future or less than 4 years old
            if (dobValue > today || dobValue > minDate) {
                dobError.classList.remove('hidden');
                dobInput.classList.add('border-red-500');
                return false;
            } else {
                dobError.classList.add('hidden');
                dobInput.classList.remove('border-red-500');
                return true;
            }
        }
        
        // Initialize form state and validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleSchoolSelection();
            
            // Add event listener to date of birth field
            const dobInput = document.getElementById('date_of_birth');
            dobInput.addEventListener('change', validateDateOfBirth);
            
            // Add form submission validation
            const form = document.getElementById('student-registration-form');
            form.addEventListener('submit', function(event) {
                if (!validateDateOfBirth()) {
                    event.preventDefault();
                }
            });
        });
    </script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        
        /* Hover effects */
        .hover-grow {
            transition: all 0.2s ease;
        }
        .hover-grow:hover {
            transform: scale(1.02);
        }
    </style>
</head>

<body class="has-sticky-header">
    <header class="bg-white text-gray-800 shadow-md sticky-header">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span class="text-primary text-xl font-medium">Young</span>
                    <span class="text-secondary mx-1 text-xl font-medium">Experts</span>
                    <span class="text-primary text-xl font-medium">Group</span>
                </a>
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="text-primary hover:text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                <!-- Desktop navigation -->
                <nav class="hidden md:flex space-x-6 items-center">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('school.register') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-school mr-1"></i> School Registration
                    </a>
                    <a href="{{ url('/students/register') }}" class="text-primary border-b-2 border-secondary flex items-center">
                        <i class="fas fa-user-graduate mr-1"></i> Student Registration
                    </a>
                    <a href="{{ url('/about') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-info-circle mr-1"></i> About Us
                    </a>
                    <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-envelope mr-1"></i> Contact
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </nav>
            </div>

            <!-- Mobile navigation menu (hidden by default) -->
            <div id="mobile-menu" class="hidden md:hidden mt-3 pb-2 bg-white border-t border-gray-200">
                <div class="flex flex-col space-y-2">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-home mr-2 w-5 text-center"></i> Home
                    </a>
                    <a href="{{ route('school.register') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-school mr-2 w-5 text-center"></i> School Registration
                    </a>
                    <a href="{{ url('/students/register') }}" class="text-primary py-2 bg-gray-100 px-2 rounded flex items-center">
                        <i class="fas fa-user-graduate mr-2 w-5 text-center"></i> Student Registration
                    </a>
                    <a href="{{ url('/about') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-info-circle mr-2 w-5 text-center"></i> About Us
                    </a>
                    <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-envelope mr-2 w-5 text-center"></i> Contact
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center mt-2">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <main>
        <div class="bg-gray-50 min-h-screen py-8">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Student Registration</h1>
                <p class="text-gray-600">Complete your registration with Young Experts Group</p>
            </div>
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="py-6 px-8" style="background-color: #950713;">
                <h1 class="text-2xl font-bold text-white">Student Registration</h1>
                <p class="text-white text-opacity-80 mt-1">Final Step: Student Details</p>
            </div>
            
            <div class="p-8">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="text-white rounded-full w-8 h-8 flex items-center justify-center mr-3" style="background-color: #950713;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Student Information</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Please provide your personal details to complete your registration.</p>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Registration Information</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p><strong>Program:</strong> {{ $programType->name }}</p>
                                @if(session('registration.fee_amount'))
                                    <p><strong>Fee Amount:</strong> GHC {{ number_format(session('registration.fee_amount'), 2) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <form action="/students/register/details" method="POST" class="space-y-6" id="student-registration-form">
                    @csrf
                    
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            @error('first_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            @error('last_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            @error('phone')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="parent_contact" class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Contact <span class="text-red-500">*</span></label>
                            <input type="text" id="parent_contact" name="parent_contact" value="{{ old('parent_contact') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            <p class="text-xs text-gray-500 mt-1">Emergency contact number for parent or guardian</p>
                            @error('parent_contact')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" max="{{ now()->format('Y-m-d') }}" required>
                            <p class="text-xs text-gray-500 mt-1">Student must be at least 4 years old</p>
                            <div id="dob-error" class="text-red-500 text-sm mt-1 hidden">Student must be at least 4 years old and date cannot be in the future.</div>
                            @error('date_of_birth')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                            <select id="gender" name="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class <span class="text-red-500">*</span></label>
                            <input type="text" id="class" name="class" value="{{ old('class') }}" placeholder="e.g. Grade 3, JHS 1, Form 2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            @error('class')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- School Selection -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">School Information</h3>
                        
                        <!-- School Selection Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">School Selection <span class="text-red-500">*</span></label>
                            <div class="flex gap-4 mb-3">
                                <div class="flex items-center">
                                    <input type="radio" id="existing_school" name="school_selection" value="existing" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary" {{ old('school_selection', 'existing') == 'existing' ? 'checked' : '' }} onchange="toggleSchoolSelection()">
                                    <label for="existing_school" class="ml-2 block text-sm text-gray-700">Select from existing schools</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="new_school" name="school_selection" value="new" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary" {{ old('school_selection') == 'new' ? 'checked' : '' }} onchange="toggleSchoolSelection()">
                                    <label for="new_school" class="ml-2 block text-sm text-gray-700">Enter a new school</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- School Dropdown (for existing schools) -->
                        <div id="existing_school_div" class="mb-4">
                            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">Select School <span class="text-red-500">*</span></label>
                            <select id="school_id" name="school_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                <option value="">-- Select School --</option>
                                @foreach(\App\Models\School::all() as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- New School Name (for manual entry) -->
                        <div id="new_school_div" class="mb-4 hidden">
                            <label for="school_name" class="block text-sm font-medium text-gray-700 mb-1">School Name <span class="text-red-500">*</span></label>
                            <input type="text" id="school_name" name="school_name" value="{{ old('school_name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" placeholder="Enter school name">
                            <p class="text-sm text-gray-500 mt-1">If your school is not in our list, please enter the name above.</p>
                            @error('school_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                    <!-- Address Information -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                                @error('address')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Town <span class="text-red-500">*</span></label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                                @error('city')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region <span class="text-red-500">*</span></label>
                                <select id="region" name="region" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                                    <option value="">Select Region</option>
                                    <option value="Greater Accra" {{ old('region') == 'Greater Accra' ? 'selected' : '' }}>Greater Accra</option>
                                    <option value="Ashanti" {{ old('region') == 'Ashanti' ? 'selected' : '' }}>Ashanti</option>
                                    <option value="Western" {{ old('region') == 'Western' ? 'selected' : '' }}>Western</option>
                                    <option value="Eastern" {{ old('region') == 'Eastern' ? 'selected' : '' }}>Eastern</option>
                                    <option value="Central" {{ old('region') == 'Central' ? 'selected' : '' }}>Central</option>
                                    <option value="Volta" {{ old('region') == 'Volta' ? 'selected' : '' }}>Volta</option>
                                    <option value="Northern" {{ old('region') == 'Northern' ? 'selected' : '' }}>Northern</option>
                                    <option value="Upper East" {{ old('region') == 'Upper East' ? 'selected' : '' }}>Upper East</option>
                                    <option value="Upper West" {{ old('region') == 'Upper West' ? 'selected' : '' }}>Upper West</option>
                                    <option value="Bono" {{ old('region') == 'Bono' ? 'selected' : '' }}>Bono</option>
                                    <option value="Other" {{ old('region') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('region')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200" style="background-color: #950713;">
                            Complete Registration
                            <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                        
                        @if(session('error'))
                            <div class="mt-4 p-4 mb-4 bg-red-100 border border-red-200 text-red-700 rounded">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </main>

    <footer class="bg-gray-800 text-white mt-10">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p>© {{ date('Y') }} Young Experts Group. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Initialize the school selection toggle
            toggleSchoolSelection();
        });
        
        function toggleSchoolSelection() {
            const existingSchoolRadio = document.getElementById('existing_school');
            const existingSchoolDiv = document.getElementById('existing_school_div');
            const newSchoolDiv = document.getElementById('new_school_div');
            const schoolIdSelect = document.getElementById('school_id');
            const schoolNameInput = document.getElementById('school_name');
            
            if (existingSchoolRadio && existingSchoolRadio.checked) {
                existingSchoolDiv.classList.remove('hidden');
                newSchoolDiv.classList.add('hidden');
                schoolIdSelect.setAttribute('required', '');
                schoolNameInput.removeAttribute('required');
            } else {
                existingSchoolDiv.classList.add('hidden');
                newSchoolDiv.classList.remove('hidden');
                schoolIdSelect.removeAttribute('required');
                schoolNameInput.setAttribute('required', '');
            }
        }
    </script>
</body>
</html>
