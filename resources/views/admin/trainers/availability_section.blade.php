<!-- Availability Section -->
<div class="p-6 sm:p-8 form-section bg-gray-50 border-b border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
        <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
            <i class="fas fa-calendar-alt"></i>
        </span>
        Availability
    </h2>
    
    <div class="space-y-6">
        <!-- Program Applied -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Which program are you applying for? <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Full-Time Program -->
                <div>
                    <input type="radio" name="program_applied" id="full_time" value="full_time" class="peer hidden" {{ old('program_applied', $trainer->program_applied) == 'full_time' ? 'checked' : '' }} required>
                    <label for="full_time" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Full-Time Program</p>
                                    <p class="text-gray-500">Mon-Fri, 9am-3pm</p>
                                </div>
                            </div>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
                        </div>
                    </label>
                </div>
                
                <!-- Part-Time Program -->
                <div>
                    <input type="radio" name="program_applied" id="part_time" value="part_time" class="peer hidden" {{ old('program_applied', $trainer->program_applied) == 'part_time' ? 'checked' : '' }}>
                    <label for="part_time" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Part-Time Program</p>
                                    <p class="text-gray-500">Weekends, flexible hours</p>
                                </div>
                            </div>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
                        </div>
                    </label>
                </div>
                
                <!-- Both Programs -->
                <div>
                    <input type="radio" name="program_applied" id="both_programs" value="both" class="peer hidden" {{ old('program_applied', $trainer->program_applied) == 'both' ? 'checked' : '' }}>
                    <label for="both_programs" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">Both Programs</p>
                                    <p class="text-gray-500">Full flexibility</p>
                                </div>
                            </div>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
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
            <label class="block text-sm font-medium text-gray-700 mb-3">Preferred Teaching Locations <span class="text-red-500">*</span></label>
            <p class="text-gray-500 text-sm mb-4">Select all locations where you would be willing to teach</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                
                <!-- Spintex -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="preferred_locations[]" id="spintex" value="spintex" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('spintex', old('preferred_locations', $trainer->preferred_locations ?? [])) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3">
                        <label for="spintex" class="text-gray-700">Spintex</label>
                    </div>
                </div>
                
                <!-- Osu -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="preferred_locations[]" id="osu" value="osu" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('osu', old('preferred_locations', $trainer->preferred_locations ?? [])) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3">
                        <label for="osu" class="text-gray-700">Osu</label>
                    </div>
                </div>
                
                <!-- Other Location -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="preferred_locations[]" id="other_location_checkbox" value="other" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ in_array('other', old('preferred_locations', $trainer->preferred_locations ?? [])) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3">
                        <label for="other_location_checkbox" class="text-gray-700">Other</label>
                    </div>
                </div>
            </div>
            
            <!-- Other Location Text Input -->
            <div id="other_location_container" class="mt-4 {{ in_array('other', old('preferred_locations', $trainer->preferred_locations ?? [])) ? '' : 'hidden' }}">
                <label for="other_location" class="block text-sm font-medium text-gray-700 mb-1">Please specify other location(s)</label>
                <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:ring-opacity-50">
                    <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                        <i class="fas fa-map-pin text-gray-400"></i>
                    </div>
                    <input type="text" name="other_location" id="other_location" value="{{ old('other_location', $trainer->other_location ?? '') }}" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="E.g., Tema, Adenta, etc.">
                </div>
            </div>
            
            @error('preferred_locations')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
