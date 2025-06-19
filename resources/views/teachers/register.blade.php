<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher Registration - Young Experts Group</title>
    <link href="{{ asset('css/sticky-headers.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e11d48',
                        secondary: '#f59e0b'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6;
        }
        
        /* Form styles */
        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        /* Custom radio and checkbox styles */
        .custom-radio input:checked + span,
        .custom-checkbox input:checked + span {
            border-color: #e11d48;
            background-color: rgba(225, 29, 72, 0.05);
        }
        
        .custom-radio input:checked + span .radio-circle,
        .custom-checkbox input:checked + span .checkbox-icon {
            background-color: #e11d48;
        }
        
        /* Video upload styling */
        .video-upload-container {
            border: 2px dashed #cbd5e0;
            transition: all 0.3s ease;
        }
        
        .video-upload-container:hover,
        .video-upload-container.dragging {
            border-color: #e11d48;
            background-color: rgba(225, 29, 72, 0.05);
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
                    <span class="text-gray-700 text-xl font-medium">Group</span>
                </a>
                
                <div class="hidden md:flex space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-primary transition-colors">Home</a>
                    <a href="#" class="text-gray-600 hover:text-primary transition-colors">Programs</a>
                </div>
                
                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="md:hidden rounded-md p-2 text-gray-600 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-2 py-2 bg-white rounded-lg shadow-lg">
                <a href="{{ url('/') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Home</a>
                <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Programs</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Teacher Registration</h1>
                <p class="text-gray-600">Join the Young Experts Group as an instructor and make a difference</p>
            </div>

            <!-- Teacher Registration Form -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <form action="{{ route('teacher.register.submit') }}" method="POST" enctype="multipart/form-data" id="teacher-registration-form">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="p-6 sm:p-8 form-section">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <span class="bg-primary text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                                <i class="fas fa-user"></i>
                            </span>
                            Personal Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('name') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Your full name" required>
                                </div>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Phone Number -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('phone') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="e.g., 024 123 4567" required>
                                </div>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('email') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="your.email@example.com" required>
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('location') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" name="location" id="location" value="{{ old('location') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Your residential area" required>
                                </div>
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Surrounding Areas (Optional) -->
                            <div class="md:col-span-2">
                                <label for="surrounding_areas" class="block text-sm font-medium text-gray-700 mb-1">Surrounding Areas <span class="text-gray-400">(Optional)</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('surrounding_areas') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-map text-gray-400"></i>
                                    </div>
                                    <input type="text" name="surrounding_areas" id="surrounding_areas" value="{{ old('surrounding_areas') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Other areas close to your location">
                                </div>
                                @error('surrounding_areas')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Education & Experience Section -->
                    <div class="p-6 sm:p-8 form-section bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <span class="bg-primary text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            Education & Experience
                        </h2>
                        
                        <div class="space-y-6">
                            <!-- Educational Background -->
                            <div>
                                <label for="educational_background" class="block text-sm font-medium text-gray-700 mb-1">Educational Background <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('educational_background') border-red-500 @enderror">
                                    <div class="bg-white flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-book text-gray-400"></i>
                                    </div>
                                    <textarea name="educational_background" id="educational_background" rows="4" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Please describe your educational qualifications, degrees, certifications, etc." required>{{ old('educational_background') }}</textarea>
                                </div>
                                @error('educational_background')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Relevant Experience -->
                            <div>
                                <label for="relevant_experience" class="block text-sm font-medium text-gray-700 mb-1">Relevant Experience (Tech/Teaching) <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('relevant_experience') border-red-500 @enderror">
                                    <div class="bg-white flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <textarea name="relevant_experience" id="relevant_experience" rows="4" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Describe your experience in technology or teaching related roles" required>{{ old('relevant_experience') }}</textarea>
                                </div>
                                @error('relevant_experience')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Skills & Areas of Expertise Section -->
                    <div class="p-6 sm:p-8 form-section">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <span class="bg-primary text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                                <i class="fas fa-tools"></i>
                            </span>
                            Skills & Areas of Expertise
                        </h2>
                        
                        <div>
                            <p class="text-sm text-gray-600 mb-4">Select all that apply <span class="text-red-500">*</span></p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- Digital Literacy -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="expertise_areas[]" id="digital_literacy" value="digital_literacy" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('digital_literacy', old('expertise_areas', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="digital_literacy" class="text-gray-700">Digital Literacy</label>
                                        <p class="text-gray-500 text-xs">Basic computer skills, internet research, office software</p>
                                    </div>
                                </div>
                                
                                <!-- Graphic Design -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="expertise_areas[]" id="graphic_design" value="graphic_design" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('graphic_design', old('expertise_areas', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="graphic_design" class="text-gray-700">Graphic Design</label>
                                        <p class="text-gray-500 text-xs">Visual design, image editing, illustration</p>
                                    </div>
                                </div>
                                
                                <!-- Coding & Software Development -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="expertise_areas[]" id="coding" value="coding" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('coding', old('expertise_areas', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="coding" class="text-gray-700">Coding & Software Development</label>
                                        <p class="text-gray-500 text-xs">Programming, app development, web development</p>
                                    </div>
                                </div>
                                
                                <!-- Robotics -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="expertise_areas[]" id="robotics" value="robotics" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('robotics', old('expertise_areas', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="robotics" class="text-gray-700">Robotics</label>
                                        <p class="text-gray-500 text-xs">Physical computing, electronics, automation</p>
                                    </div>
                                </div>
                                
                                <!-- Entrepreneurship & Business -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="expertise_areas[]" id="entrepreneurship" value="entrepreneurship" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('entrepreneurship', old('expertise_areas', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="entrepreneurship" class="text-gray-700">Entrepreneurship & Business</label>
                                        <p class="text-gray-500 text-xs">Business planning, innovation, startups</p>
                                    </div>
                                </div>
                                
                                <!-- Other -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="expertise_areas[]" id="other_expertise_checkbox" value="other" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('other', old('expertise_areas', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="other_expertise_checkbox" class="text-gray-700">Other</label>
                                        <p class="text-gray-500 text-xs">Please specify below</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Other Expertise Text Input (conditionally shown) -->
                            <div id="other_expertise_container" class="mt-4 {{ in_array('other', old('expertise_areas', [])) ? '' : 'hidden' }}">
                                <label for="other_expertise" class="block text-sm font-medium text-gray-700 mb-1">Please specify your other expertise areas</label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-plus-circle text-gray-400"></i>
                                    </div>
                                    <input type="text" name="other_expertise" id="other_expertise" value="{{ old('other_expertise') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="E.g., Artificial Intelligence, Game Design, etc.">
                                </div>
                            </div>
                            
                            @error('expertise_areas')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Availability Section -->
                    <div class="p-6 sm:p-8 form-section bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <span class="bg-primary text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            Availability
                        </h2>
                        
                        <!-- Program Application -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Which program are you applying for? <span class="text-red-500">*</span></label>
                            
                            <div class="space-y-4">
                                <!-- Partnered Schools -->
                                <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                    <input type="radio" name="program_applied" id="program_partnered" value="partnered_schools" class="absolute w-0 h-0 opacity-0" {{ old('program_applied') == 'partnered_schools' ? 'checked' : '' }} required>
                                    <label for="program_partnered" class="flex items-start cursor-pointer">
                                        <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                            <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                        </span>
                                        <div>
                                            <span class="block font-medium text-gray-800">Partnered Schools</span>
                                            <span class="text-sm text-gray-600">Weekdays, 8 AM – 3:30 PM</span>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- After-School YEG -->
                                <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                    <input type="radio" name="program_applied" id="program_after_school" value="after_school" class="absolute w-0 h-0 opacity-0" {{ old('program_applied') == 'after_school' ? 'checked' : '' }}>
                                    <label for="program_after_school" class="flex items-start cursor-pointer">
                                        <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                            <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                        </span>
                                        <div>
                                            <span class="block font-medium text-gray-800">After-School YEG</span>
                                            <span class="text-sm text-gray-600">Weekdays, 3:30 PM – 4:30 PM</span>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Weekend YEG -->
                                <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                    <input type="radio" name="program_applied" id="program_weekend" value="weekend" class="absolute w-0 h-0 opacity-0" {{ old('program_applied') == 'weekend' ? 'checked' : '' }}>
                                    <label for="program_weekend" class="flex items-start cursor-pointer">
                                        <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                            <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                        </span>
                                        <div>
                                            <span class="block font-medium text-gray-800">Weekend YEG</span>
                                            <span class="text-sm text-gray-600">Saturday 9 AM – 3 PM</span>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Flexible -->
                                <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                    <input type="radio" name="program_applied" id="program_flexible" value="flexible" class="absolute w-0 h-0 opacity-0" {{ old('program_applied') == 'flexible' ? 'checked' : '' }}>
                                    <label for="program_flexible" class="flex items-start cursor-pointer">
                                        <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                            <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                        </span>
                                        <div>
                                            <span class="block font-medium text-gray-800">Flexible</span>
                                            <span class="text-sm text-gray-600">Available for multiple options</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            @error('program_applied')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Preferred Locations -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Preferred Location(s) <span class="text-red-500">*</span></label>
                            <p class="text-sm text-gray-600 mb-4">Select all that apply</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                <!-- Lapaz -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="preferred_locations[]" id="location_lapaz" value="lapaz" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('lapaz', old('preferred_locations', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="location_lapaz" class="text-gray-700">Lapaz</label>
                                    </div>
                                </div>
                                
                                <!-- Airport -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="preferred_locations[]" id="location_airport" value="airport" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('airport', old('preferred_locations', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="location_airport" class="text-gray-700">Airport</label>
                                    </div>
                                </div>
                                
                                <!-- Ridge -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="preferred_locations[]" id="location_ridge" value="ridge" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('ridge', old('preferred_locations', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="location_ridge" class="text-gray-700">Ridge</label>
                                    </div>
                                </div>
                                
                                <!-- Abelempke -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="preferred_locations[]" id="location_abelempke" value="abelempke" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('abelempke', old('preferred_locations', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="location_abelempke" class="text-gray-700">Abelempke</label>
                                    </div>
                                </div>
                                
                                <!-- Other -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="preferred_locations[]" id="location_other" value="other" class="w-5 h-5 rounded text-primary focus:ring-primary" {{ in_array('other', old('preferred_locations', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="location_other" class="text-gray-700">Other</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Other Location Text Input (conditionally shown) -->
                            <div id="other_location_container" class="mt-4 {{ in_array('other', old('preferred_locations', [])) ? '' : 'hidden' }}">
                                <label for="other_location" class="block text-sm font-medium text-gray-700 mb-1">Please specify other preferred locations</label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50">
                                    <div class="bg-white flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-map-pin text-gray-400"></i>
                                    </div>
                                    <input type="text" name="other_location" id="other_location" value="{{ old('other_location') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Please specify your preferred locations">
                                </div>
                            </div>
                            
                            @error('preferred_locations')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Additional Information Section -->
                    <div class="p-6 sm:p-8 form-section">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <span class="bg-primary text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle"></i>
                            </span>
                            Additional Information
                        </h2>
                        
                        <div class="space-y-6">
                            <!-- Teaching Experience -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Do you have experience teaching kids? <span class="text-red-500">*</span></label>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Yes -->
                                    <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                        <input type="radio" name="experience_teaching_kids" id="teaching_yes" value="1" class="absolute w-0 h-0 opacity-0" {{ old('experience_teaching_kids') == '1' ? 'checked' : '' }} required>
                                        <label for="teaching_yes" class="flex items-start cursor-pointer">
                                            <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                                <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                            </span>
                                            <span class="block font-medium text-gray-800">Yes</span>
                                        </label>
                                    </div>
                                    
                                    <!-- No -->
                                    <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                        <input type="radio" name="experience_teaching_kids" id="teaching_no" value="0" class="absolute w-0 h-0 opacity-0" {{ old('experience_teaching_kids') == '0' ? 'checked' : '' }}>
                                        <label for="teaching_no" class="flex items-start cursor-pointer">
                                            <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                                <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                            </span>
                                            <span class="block font-medium text-gray-800">No</span>
                                        </label>
                                    </div>
                                </div>
                                
                                @error('experience_teaching_kids')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- CV Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Have you sent your CV? <span class="text-red-500">*</span></label>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Yes -->
                                    <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                        <input type="radio" name="cv_status" id="cv_yes" value="yes" class="absolute w-0 h-0 opacity-0" {{ old('cv_status') == 'yes' ? 'checked' : '' }} required>
                                        <label for="cv_yes" class="flex items-start cursor-pointer">
                                            <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                                <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                            </span>
                                            <span class="block font-medium text-gray-800">Yes</span>
                                        </label>
                                    </div>
                                    
                                    <!-- No -->
                                    <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                        <input type="radio" name="cv_status" id="cv_no" value="no" class="absolute w-0 h-0 opacity-0" {{ old('cv_status') == 'no' ? 'checked' : '' }}>
                                        <label for="cv_no" class="flex items-start cursor-pointer">
                                            <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                                <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                            </span>
                                            <span class="block font-medium text-gray-800">No</span>
                                        </label>
                                    </div>
                                    
                                    <!-- Will Send -->
                                    <div class="relative border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                                        <input type="radio" name="cv_status" id="cv_will_send" value="will_send" class="absolute w-0 h-0 opacity-0" {{ old('cv_status') == 'will_send' ? 'checked' : '' }}>
                                        <label for="cv_will_send" class="flex items-start cursor-pointer">
                                            <span class="h-5 w-5 rounded-full border flex-shrink-0 mr-3 inline-flex items-center justify-center radio-circle">
                                                <span class="h-3 w-3 rounded-full bg-white transform radio-dot"></span>
                                            </span>
                                            <div>
                                                <span class="block font-medium text-gray-800">Not Yet</span>
                                                <span class="text-sm text-gray-600">I will send to imtghanabranch@gmail.com</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                @error('cv_status')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Why Instructor -->
                            <div>
                                <label for="why_instructor" class="block text-sm font-medium text-gray-700 mb-1">Why do you want to be a YEG Instructor? <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50 @error('why_instructor') border-red-500 @enderror">
                                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-200">
                                        <i class="fas fa-comment text-gray-400"></i>
                                    </div>
                                    <textarea name="why_instructor" id="why_instructor" rows="4" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Share your motivation and what makes you a good fit for teaching with YEG..." required>{{ old('why_instructor') }}</textarea>
                                </div>
                                @error('why_instructor')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Video Upload -->
                            <div>
                                <label for="video_introduction" class="block text-sm font-medium text-gray-700 mb-1">Upload a 2-Minute Video Introduction <span class="text-gray-500">(Optional)</span></label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors video-upload-container" id="drop-area">
                                    <div class="space-y-1 text-center">
                                        <div class="flex justify-center">
                                            <i class="fas fa-video text-4xl text-gray-400"></i>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <label for="video_introduction" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark">
                                                <span>Upload a video</span>
                                                <input id="video_introduction" name="video_introduction" type="file" accept="video/*" class="sr-only">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">MP4, AVI, MOV up to 100MB</p>
                                        <div id="video-preview" class="hidden mt-3">
                                            <div class="flex items-center bg-blue-50 p-2 rounded-lg">
                                                <i class="fas fa-check-circle text-blue-500 mr-2"></i>
                                                <span id="video-name" class="text-sm font-medium text-blue-800 truncate"></span>
                                                <button type="button" id="remove-video" class="ml-auto text-blue-500 hover:text-blue-700">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">A brief video introducing yourself and why you'd like to join YEG as an instructor.</p>
                                @error('video_introduction')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirmation Section -->
                    <div class="p-6 sm:p-8 form-section bg-gray-50">
                        <div class="space-y-6">
                            <!-- Confirmation Agreement -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="confirmation_agreement" name="confirmation_agreement" type="checkbox" class="w-5 h-5 rounded text-primary focus:ring-primary" value="1" {{ old('confirmation_agreement') ? 'checked' : '' }} required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="confirmation_agreement" class="font-medium text-gray-700">I understand that this role is commission-based per session and I am committed to punctuality. <span class="text-red-500">*</span></label>
                                </div>
                            </div>
                            @error('confirmation_agreement')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Submit Button -->
                            <div class="flex justify-end pt-4">
                                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Submit Application
                                </button>
                            </div>
                        </div>
                    </div>
                
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white mt-10">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Young Experts Group. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/sticky-header.js') }}"></script>
    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Custom Radio Button Styling
            const radioInputs = document.querySelectorAll('input[type="radio"]');
            
            radioInputs.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Get all radio inputs with the same name
                    const sameNameRadios = document.querySelectorAll(`input[name="${this.name}"]`);
                    
                    // Reset all radio buttons of the same name
                    sameNameRadios.forEach(sameRadio => {
                        const circle = sameRadio.parentElement.querySelector('.radio-circle');
                        const dot = sameRadio.parentElement.querySelector('.radio-dot');
                        
                        if (circle && dot) {
                            circle.classList.remove('bg-primary', 'border-primary');
                            circle.classList.add('border-gray-200');
                            dot.classList.remove('bg-white', 'scale-100');
                            dot.classList.add('bg-transparent', 'scale-0');
                        }
                    });
                    
                    // Style the selected radio button
                    if (this.checked) {
                        const circle = this.parentElement.querySelector('.radio-circle');
                        const dot = this.parentElement.querySelector('.radio-dot');
                        
                        if (circle && dot) {
                            circle.classList.add('bg-primary', 'border-primary');
                            circle.classList.remove('border-gray-200');
                            dot.classList.add('bg-white', 'scale-100');
                            dot.classList.remove('bg-transparent', 'scale-0');
                        }
                    }
                });
                
                // Set initial state if pre-selected
                if (radio.checked) {
                    const circle = radio.parentElement.querySelector('.radio-circle');
                    const dot = radio.parentElement.querySelector('.radio-dot');
                    
                    if (circle && dot) {
                        circle.classList.add('bg-primary', 'border-primary');
                        circle.classList.remove('border-gray-200');
                        dot.classList.add('bg-white', 'scale-100');
                        dot.classList.remove('bg-transparent', 'scale-0');
                    }
                }
            });
            
            // Toggle "Other" expertise field
            const otherExpertiseCheckbox = document.getElementById('other_expertise_checkbox');
            const otherExpertiseContainer = document.getElementById('other_expertise_container');
            
            if (otherExpertiseCheckbox && otherExpertiseContainer) {
                otherExpertiseCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        otherExpertiseContainer.classList.remove('hidden');
                    } else {
                        otherExpertiseContainer.classList.add('hidden');
                    }
                });
            }
            
            // Toggle "Other" location field
            const otherLocationCheckbox = document.getElementById('location_other');
            const otherLocationContainer = document.getElementById('other_location_container');
            
            if (otherLocationCheckbox && otherLocationContainer) {
                otherLocationCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        otherLocationContainer.classList.remove('hidden');
                    } else {
                        otherLocationContainer.classList.add('hidden');
                    }
                });
            }
            
            // Video Upload & Preview Functionality
            const videoInput = document.getElementById('video_introduction');
            const videoPreview = document.getElementById('video-preview');
            const videoName = document.getElementById('video-name');
            const removeVideo = document.getElementById('remove-video');
            const dropArea = document.getElementById('drop-area');
            
            // File input change handler
            if (videoInput && videoPreview && videoName && removeVideo) {
                videoInput.addEventListener('change', handleVideoSelect);
                
                // Remove video button
                removeVideo.addEventListener('click', function() {
                    videoInput.value = '';
                    videoPreview.classList.add('hidden');
                    dropArea.classList.remove('bg-blue-50', 'border-blue-300');
                });
                
                // Drag and drop functionality
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                });
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, highlight, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, unhighlight, false);
                });
                
                dropArea.addEventListener('drop', handleDrop, false);
            }
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            function highlight() {
                dropArea.classList.add('bg-blue-50', 'border-blue-300');
            }
            
            function unhighlight() {
                dropArea.classList.remove('bg-blue-50', 'border-blue-300');
            }
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0 && files[0].type.startsWith('video/')) {
                    videoInput.files = files;
                    handleVideoSelect();
                }
            }
            
            function handleVideoSelect() {
                if (videoInput.files && videoInput.files[0]) {
                    const file = videoInput.files[0];
                    
                    // Only accept video files
                    if (!file.type.startsWith('video/')) {
                        alert('Please select a valid video file.');
                        videoInput.value = '';
                        return;
                    }
                    
                    // Check file size (max 100MB)
                    if (file.size > 102400000) {
                        alert('Video file is too large. Please select a file under 100MB.');
                        videoInput.value = '';
                        return;
                    }
                    
                    // Show file name and preview
                    videoName.textContent = file.name;
                    videoPreview.classList.remove('hidden');
                    dropArea.classList.add('bg-blue-50', 'border-blue-300');
                }
            }
        });
    </script>
</body>
</html>
