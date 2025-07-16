@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Trainer</h1>
        <a href="{{ route('admin.trainers.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Trainers
        </a>
    </div>
    
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Please fix the following errors:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('admin.trainers.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Personal Information Section -->
            <div class="p-6 sm:p-8 form-section border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                        <i class="fas fa-user"></i>
                    </span>
                    Personal Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:border-[#950713] @error('name') border-red-500 @enderror">
                            <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $trainer->name) }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Full name" required>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:border-[#950713] @error('phone') border-red-500 @enderror">
                            <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $trainer->phone) }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="e.g., 024 123 4567" required>
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:border-[#950713] @error('email') border-red-500 @enderror">
                            <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email', $trainer->email) }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="email@example.com" required>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:border-[#950713] @error('location') border-red-500 @enderror">
                            <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <input type="text" name="location" id="location" value="{{ old('location', $trainer->location) }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Residential area" required>
                        </div>
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Surrounding Areas -->
                    <div class="md:col-span-2">
                        <label for="surrounding_areas" class="block text-sm font-medium text-gray-700 mb-1">Surrounding Areas <span class="text-gray-400">(Optional)</span></label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:border-[#950713] @error('surrounding_areas') border-red-500 @enderror">
                            <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-map text-gray-400"></i>
                            </div>
                            <input type="text" name="surrounding_areas" id="surrounding_areas" value="{{ old('surrounding_areas', $trainer->surrounding_areas ?? '') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Other areas you can reach easily">
                        </div>
                        @error('surrounding_areas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Education & Experience Section -->
            <div class="p-6 sm:p-8 form-section bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                        <i class="fas fa-graduation-cap"></i>
                    </span>
                    Education & Experience
                </h2>
                
                <div class="space-y-6">
                    <!-- Educational Background -->
                    <div>
                        <label for="educational_background" class="block text-sm font-medium text-gray-700 mb-1">Educational Background <span class="text-red-500">*</span></label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:ring-opacity-50 @error('educational_background') border-red-500 @enderror">
                            <div class="bg-white flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-book text-gray-400"></i>
                            </div>
                            <textarea name="educational_background" id="educational_background" rows="4" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Educational qualifications, degrees, certifications, etc." required>{{ old('educational_background', $trainer->educational_background) }}</textarea>
                        </div>
                        @error('educational_background')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Relevant Experience -->
                    <div>
                        <label for="relevant_experience" class="block text-sm font-medium text-gray-700 mb-1">Relevant Experience (Tech/Teaching) <span class="text-red-500">*</span></label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:ring-opacity-50 @error('relevant_experience') border-red-500 @enderror">
                            <div class="bg-white flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-briefcase text-gray-400"></i>
                            </div>
                            <textarea name="relevant_experience" id="relevant_experience" rows="4" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Experience in technology or teaching related roles" required>{{ old('relevant_experience', $trainer->relevant_experience) }}</textarea>
                        </div>
                        @error('relevant_experience')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Skills & Areas of Expertise Section -->
            <div class="p-6 sm:p-8 form-section border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
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
                                <input type="checkbox" name="expertise_areas[]" id="digital_literacy" value="digital_literacy" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('digital_literacy', old('expertise_areas', $trainer->expertise_areas ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="digital_literacy" class="text-gray-700">Digital Literacy</label>
                                <p class="text-gray-500 text-xs">Basic computer skills, internet research, office software</p>
                            </div>
                        </div>
                        
                        <!-- Graphic Design -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="expertise_areas[]" id="graphic_design" value="graphic_design" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('graphic_design', old('expertise_areas', $trainer->expertise_areas ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="graphic_design" class="text-gray-700">Graphic Design</label>
                                <p class="text-gray-500 text-xs">Visual design, image editing, illustration</p>
                            </div>
                        </div>
                        
                        <!-- Coding & Software Development -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="expertise_areas[]" id="coding" value="coding" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('coding', old('expertise_areas', $trainer->expertise_areas ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="coding" class="text-gray-700">Coding & Software Development</label>
                                <p class="text-gray-500 text-xs">Programming, app development, web development</p>
                            </div>
                        </div>
                        
                        <!-- Robotics -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="expertise_areas[]" id="robotics" value="robotics" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('robotics', old('expertise_areas', $trainer->expertise_areas ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="robotics" class="text-gray-700">Robotics</label>
                                <p class="text-gray-500 text-xs">Physical computing, electronics, automation</p>
                            </div>
                        </div>
                        
                        <!-- Entrepreneurship & Business -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="expertise_areas[]" id="entrepreneurship" value="entrepreneurship" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('entrepreneurship', old('expertise_areas', $trainer->expertise_areas ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="entrepreneurship" class="text-gray-700">Entrepreneurship & Business</label>
                                <p class="text-gray-500 text-xs">Business planning, innovation, startups</p>
                            </div>
                        </div>
                        
                        <!-- Other -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="expertise_areas[]" id="other_expertise_checkbox" value="other" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('other', old('expertise_areas', $trainer->expertise_areas ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="other_expertise_checkbox" class="text-gray-700">Other</label>
                                <p class="text-gray-500 text-xs">Please specify below</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Other Expertise Text Input -->
                    <div id="other_expertise_container" class="mt-4 {{ in_array('other', old('expertise_areas', $trainer->expertise_areas ?? [])) ? '' : 'hidden' }}">
                        <label for="other_expertise" class="block text-sm font-medium text-gray-700 mb-1">Please specify your other expertise areas</label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:ring-opacity-50">
                            <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-plus-circle text-gray-400"></i>
                            </div>
                            <input type="text" name="other_expertise" id="other_expertise" value="{{ old('other_expertise', $trainer->other_expertise ?? '') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="E.g., Artificial Intelligence, Game Design, etc.">
                        </div>
                    </div>
                    
                    @error('expertise_areas')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    
                    <!-- Specialization -->
                    <div class="mt-6">
                        <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:ring-opacity-50 @error('specialization') border-red-500 @enderror">
                            <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-star text-gray-400"></i>
                            </div>
                            <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $trainer->specialization) }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Main area of expertise">
                        </div>
                        @error('specialization')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Availability Section -->
            <div class="p-6 sm:p-8 form-section bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    Availability
                </h2>
                
                <!-- Program Applied For -->
                <div class="mb-8">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Which program(s) are you applying for? <span class="text-red-500">*</span></h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Regular Tech Classes -->
                        <label class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start">
                            <input type="radio" name="program_applied" value="regular_tech_classes" class="h-5 w-5 text-[#950713] focus:ring-[#950713] border-gray-300" {{ old('program_applied', $trainer->program_applied) == 'regular_tech_classes' ? 'checked' : '' }} required>
                            <div class="ml-3">
                                <span class="block font-medium text-gray-700">Regular Tech Classes</span>
                                <span class="text-gray-500 text-sm">Scheduled weekly technology classes for students</span>
                            </div>
                        </label>
                        
                        <!-- Holiday Programs -->
                        <label class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start">
                            <input type="radio" name="program_applied" value="holiday_programs" class="h-5 w-5 text-[#950713] focus:ring-[#950713] border-gray-300" {{ old('program_applied', $trainer->program_applied) == 'holiday_programs' ? 'checked' : '' }} required>
                            <div class="ml-3">
                                <span class="block font-medium text-gray-700">Holiday Programs</span>
                                <span class="text-gray-500 text-sm">Intensive tech workshops during school holidays</span>
                            </div>
                        </label>
                        
                        <!-- Both Programs -->
                        <label class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start md:col-span-2">
                            <input type="radio" name="program_applied" value="both_programs" class="h-5 w-5 text-[#950713] focus:ring-[#950713] border-gray-300" {{ old('program_applied', $trainer->program_applied) == 'both_programs' ? 'checked' : '' }} required>
                            <div class="ml-3">
                                <span class="block font-medium text-gray-700">Both Programs</span>
                                <span class="text-gray-500 text-sm">Available for regular classes and holiday workshops</span>
                            </div>
                        </label>
                    </div>
                    
                    @error('program_applied')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Preferred Teaching Locations -->
                <div>
                    <h3 class="text-md font-medium text-gray-800 mb-3">Preferred teaching locations <span class="text-red-500">*</span></h3>
                    <p class="text-sm text-gray-600 mb-4">Select all that apply</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- East Legon -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="preferred_locations[]" id="east_legon" value="east_legon" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('east_legon', old('preferred_locations', $trainer->preferred_locations ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="east_legon" class="text-gray-700">East Legon</label>
                            </div>
                        </div>
                        
                        <!-- Cantonments -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="preferred_locations[]" id="cantonments" value="cantonments" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('cantonments', old('preferred_locations', $trainer->preferred_locations ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="cantonments" class="text-gray-700">Cantonments</label>
                            </div>
                        </div>
                        
                        <!-- Airport Residential -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="preferred_locations[]" id="airport_residential" value="airport_residential" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('airport_residential', old('preferred_locations', $trainer->preferred_locations ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="airport_residential" class="text-gray-700">Airport Residential</label>
                            </div>
                        </div>
                        
                        <!-- Other Location -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="preferred_locations[]" id="other_location_checkbox" value="other" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('other', old('preferred_locations', $trainer->preferred_locations ?? [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="other_location_checkbox" class="text-gray-700">Other</label>
                                <p class="text-gray-500 text-xs">Please specify below</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Other Location Text Input -->
                    <div id="other_location_container" class="mt-4 {{ in_array('other', old('preferred_locations', $trainer->preferred_locations ?? [])) ? '' : 'hidden' }}">
                        <label for="other_location" class="block text-sm font-medium text-gray-700 mb-1">Please specify other preferred location(s)</label>
                        <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:ring-opacity-50">
                            <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <input type="text" name="other_location" id="other_location" value="{{ old('other_location', $trainer->other_location ?? '') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="E.g., Tema, Dansoman, etc.">
                        </div>
                    </div>
                    
                    @error('preferred_locations')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Additional Information Section -->
            <div class="p-6 sm:p-8 form-section border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    Additional Information
                </h2>
                
                <!-- Experience Teaching Kids -->
                <div class="mb-8">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Experience Teaching Kids <span class="text-red-500">*</span></h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Yes -->
                        <label class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start">
                            <input type="radio" name="experience_teaching_kids" value="1" class="h-5 w-5 text-[#950713] focus:ring-[#950713] border-gray-300" {{ old('experience_teaching_kids', $trainer->experience_teaching_kids) == 1 ? 'checked' : '' }} required>
                            <div class="ml-3">
                                <span class="block font-medium text-gray-700">Yes</span>
                                <span class="text-gray-500 text-sm">I have experience teaching children</span>
                            </div>
                        </label>
                        
                        <!-- No -->
                        <label class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start">
                            <input type="radio" name="experience_teaching_kids" value="0" class="h-5 w-5 text-[#950713] focus:ring-[#950713] border-gray-300" {{ old('experience_teaching_kids', $trainer->experience_teaching_kids) == 0 ? 'checked' : '' }} required>
                            <div class="ml-3">
                                <span class="block font-medium text-gray-700">No</span>
                                <span class="text-gray-500 text-sm">I'm new to teaching children</span>
                            </div>
                        </label>
                    </div>
                    
                    @error('experience_teaching_kids')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- CV Status -->
                <div class="mb-8">
                    <h3 class="text-md font-medium text-gray-800 mb-3">CV Status <span class="text-red-500">*</span></h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Yes (Have CV) -->
                        <label class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start">
                            <input type="radio" name="cv_status" value="yes" class="h-5 w-5 text-[#950713] focus:ring-[#950713] border-gray-300" {{ old('cv_status', $trainer->cv_status) == 'yes' ? 'checked' : '' }} required>
                            <div class="ml-3">
                                <span class="block font-medium text-gray-700">Yes</span>
                                <span class="text-gray-500 text-sm">I have an up-to-date CV</span>
                            </div>
                        </label>
                        
                        <!-- No (Don't have CV) -->
                        <label class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start">
                            <input type="radio" name="cv_status" value="no" class="h-5 w-5 text-[#950713] focus:ring-[#950713] border-gray-300" {{ old('cv_status', $trainer->cv_status) == 'no' ? 'checked' : '' }} required>
                            <div class="ml-3">
                                <span class="block font-medium text-gray-700">No</span>
                                <span class="text-gray-500 text-sm">I don't have a CV yet</span>
                            </div>
                        </label>
                        
                        <!-- Will Send (Later) -->
                        <label class="relative border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start">
                            <input type="radio" name="cv_status" value="will_send" class="h-5 w-5 text-[#950713] focus:ring-[#950713] border-gray-300" {{ old('cv_status', $trainer->cv_status) == 'will_send' ? 'checked' : '' }} required>
                            <div class="ml-3">
                                <span class="block font-medium text-gray-700">Will Send</span>
                                <span class="text-gray-500 text-sm">I'll send it later</span>
                            </div>
                        </label>
                    </div>
                    
                    @error('cv_status')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Motivation -->
                <div class="mb-8">
                    <label for="why_instructor" class="block text-sm font-medium text-gray-700 mb-1">Why do you want to be a trainer with us? <span class="text-red-500">*</span></label>
                    <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:ring-opacity-50 @error('why_instructor') border-red-500 @enderror">
                        <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                            <i class="fas fa-comment text-gray-400"></i>
                        </div>
                        <textarea name="why_instructor" id="why_instructor" rows="4" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Your interest and motivation for teaching technology" required>{{ old('why_instructor', $trainer->why_instructor) }}</textarea>
                    </div>
                    @error('why_instructor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Video Introduction -->
                <div class="mb-4">
                    <h3 class="text-md font-medium text-gray-800 mb-2">Video Introduction <span class="text-gray-400">(Optional)</span></h3>
                    <p class="text-sm text-gray-600 mb-4">Upload a short video (max 2 minutes) introducing yourself</p>
                    
                    <!-- Video Upload Area -->
                    <div class="mt-2">
                        <input type="file" name="video_introduction" id="video_introduction" class="hidden" accept="video/*">
                        
                        <!-- Upload UI -->
                        <div id="video_upload_container" class="border-2 border-dashed border-gray-300 rounded-lg p-6 flex flex-col items-center justify-center text-center {{ $trainer->video_introduction ? 'hidden' : '' }}">
                            <span class="text-[#950713] text-4xl mb-2">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </span>
                            <p class="text-gray-700 mb-2">Drag and drop your video file here</p>
                            <p class="text-gray-500 text-sm mb-4">or</p>
                            <button type="button" id="browse_video_btn" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#950713]">
                                Browse files
                            </button>
                            <p class="text-gray-500 text-xs mt-2">Maximum file size: 100MB</p>
                        </div>
                        
                        <!-- Video Preview -->
                        <div id="video_preview" class="{{ $trainer->video_introduction ? '' : 'hidden' }} border border-gray-300 rounded-lg overflow-hidden">
                            @if($trainer->video_introduction)
                                <div class="aspect-w-16 aspect-h-9">
                                    <video controls class="w-full h-full object-cover">
                                        <source src="{{ asset('storage/' . $trainer->video_introduction) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <div class="p-3 bg-gray-50 flex items-center justify-between">
                                    <span id="video_name" class="text-sm text-gray-700 truncate">{{ basename($trainer->video_introduction) }}</span>
                                    <button type="button" id="remove_video_btn" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        <i class="fas fa-trash mr-1"></i> Remove
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Hidden input to track video removal -->
                        <input type="hidden" name="existing_video" value="{{ $trainer->video_introduction }}">
                        
                        @error('video_introduction')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Confirmation Agreement Section -->
            <div class="p-6 sm:p-8 form-section bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
                        <i class="fas fa-check"></i>
                    </span>
                    Confirmation
                </h2>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="confirmation_agreement" id="confirmation_agreement" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ old('confirmation_agreement', $trainer->confirmation_agreement) ? 'checked' : '' }} required>
                        </div>
                        <div class="ml-3">
                            <label for="confirmation_agreement" class="text-gray-700">I confirm that the information provided is accurate and complete</label>
                            <p class="text-gray-500 text-xs mt-1">By checking this box, you acknowledge that all details provided can be used for trainer evaluation and communication purposes.</p>
                        </div>
                    </div>
                    @error('confirmation_agreement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Form Submit Buttons -->
            <div class="p-6 sm:p-8 flex justify-end space-x-3">
                <a href="{{ route('admin.trainers.index') }}" class="px-5 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-[#950713] text-white rounded-md hover:bg-red-900 transition-colors">
                    Update Trainer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle "Other" expertise toggle
        const otherExpertiseCheckbox = document.getElementById('other_expertise_checkbox');
        const otherExpertiseContainer = document.getElementById('other_expertise_container');
        
        if (otherExpertiseCheckbox) {
            otherExpertiseCheckbox.addEventListener('change', function() {
                otherExpertiseContainer.classList.toggle('hidden', !this.checked);
            });
        }
        
        // Handle "Other" location toggle
        const otherLocationCheckbox = document.getElementById('other_location_checkbox');
        const otherLocationContainer = document.getElementById('other_location_container');
        
        if (otherLocationCheckbox) {
            otherLocationCheckbox.addEventListener('change', function() {
                otherLocationContainer.classList.toggle('hidden', !this.checked);
            });
        }
        
        // Video upload functionality
        const videoInput = document.getElementById('video_introduction');
        const videoUploadContainer = document.getElementById('video_upload_container');
        const videoPreview = document.getElementById('video_preview');
        const browseBtn = document.getElementById('browse_video_btn');
        const removeVideoBtn = document.getElementById('remove_video_btn');
        const videoName = document.getElementById('video_name');
        
        if (browseBtn && videoInput) {
            browseBtn.addEventListener('click', function() {
                videoInput.click();
            });
        }
        
        if (videoInput) {
            videoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    // Check file size (max 100MB)
                    if (file.size > 100 * 1024 * 1024) {
                        alert('File is too large. Please select a file smaller than 100MB.');
                        return;
                    }
                    
                    // Create video preview
                    const video = videoPreview.querySelector('video') || document.createElement('video');
                    video.classList.add('w-full', 'h-full', 'object-cover');
                    video.controls = true;
                    
                    const source = document.createElement('source');
                    source.src = URL.createObjectURL(file);
                    source.type = file.type;
                    
                    video.innerHTML = '';
                    video.appendChild(source);
                    
                    if (!videoPreview.querySelector('video')) {
                        const videoContainer = videoPreview.querySelector('.aspect-w-16') || document.createElement('div');
                        videoContainer.className = 'aspect-w-16 aspect-h-9';
                        videoContainer.appendChild(video);
                        
                        if (!videoPreview.querySelector('.aspect-w-16')) {
                            videoPreview.insertBefore(videoContainer, videoPreview.firstChild);
                        }
                    }
                    
                    // Update file name display
                    if (videoName) {
                        videoName.textContent = file.name;
                    }
                    
                    // Show preview, hide upload container
                    videoPreview.classList.remove('hidden');
                    videoUploadContainer.classList.add('hidden');
                }
            });
        }
        
        if (removeVideoBtn) {
            removeVideoBtn.addEventListener('click', function() {
                // Clear the file input
                if (videoInput) {
                    videoInput.value = '';
                }
                
                // Hide preview, show upload container
                videoPreview.classList.add('hidden');
                videoUploadContainer.classList.remove('hidden');
                
                // Remove existing video value if any
                const existingVideoInput = document.querySelector('input[name="existing_video"]');
                if (existingVideoInput) {
                    existingVideoInput.value = '';
                }
            });
        }
        
        // Drag and drop functionality for video upload
        const dropZone = document.getElementById('video_upload_container');
        
        if (dropZone && videoInput) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropZone.classList.add('bg-gray-100');
                dropZone.classList.add('border-[#950713]');
            }
            
            function unhighlight() {
                dropZone.classList.remove('bg-gray-100');
                dropZone.classList.remove('border-[#950713]');
            }
            
            dropZone.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files && files.length) {
                    videoInput.files = files;
                    
                    // Trigger change event
                    const event = new Event('change', { 'bubbles': true });
                    videoInput.dispatchEvent(event);
                }
            }
        }
    });
</script>
@endsection
