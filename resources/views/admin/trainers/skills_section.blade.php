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
