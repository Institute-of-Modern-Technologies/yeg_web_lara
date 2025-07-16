<!-- Additional Information Section -->
<div class="p-6 sm:p-8 form-section border-b border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
        <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
            <i class="fas fa-info-circle"></i>
        </span>
        Additional Information
    </h2>
    
    <div class="space-y-6">
        <!-- Teaching Experience -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Years of Teaching Experience <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <!-- No Experience -->
                <div>
                    <input type="radio" name="teaching_experience" id="no_experience" value="none" class="peer hidden" {{ old('teaching_experience', $trainer->teaching_experience) == 'none' ? 'checked' : '' }} required>
                    <label for="no_experience" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">No Experience</p>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
                        </div>
                    </label>
                </div>
                
                <!-- 1-2 Years -->
                <div>
                    <input type="radio" name="teaching_experience" id="one_two_years" value="1-2" class="peer hidden" {{ old('teaching_experience', $trainer->teaching_experience) == '1-2' ? 'checked' : '' }}>
                    <label for="one_two_years" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">1-2 Years</p>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
                        </div>
                    </label>
                </div>
                
                <!-- 3-5 Years -->
                <div>
                    <input type="radio" name="teaching_experience" id="three_five_years" value="3-5" class="peer hidden" {{ old('teaching_experience', $trainer->teaching_experience) == '3-5' ? 'checked' : '' }}>
                    <label for="three_five_years" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">3-5 Years</p>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
                        </div>
                    </label>
                </div>
                
                <!-- 5+ Years -->
                <div>
                    <input type="radio" name="teaching_experience" id="five_plus_years" value="5+" class="peer hidden" {{ old('teaching_experience', $trainer->teaching_experience) == '5+' ? 'checked' : '' }}>
                    <label for="five_plus_years" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">5+ Years</p>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
                        </div>
                    </label>
                </div>
            </div>
            @error('teaching_experience')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- CV Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">CV/Resume Status <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Have CV Ready -->
                <div>
                    <input type="radio" name="cv_status" id="cv_ready" value="ready" class="peer hidden" {{ old('cv_status', $trainer->cv_status) == 'ready' ? 'checked' : '' }} required>
                    <label for="cv_ready" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt mr-3 text-gray-500"></i>
                                <p class="font-medium text-gray-900">I have my CV/Resume ready</p>
                            </div>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
                        </div>
                    </label>
                </div>
                
                <!-- Need to Update CV -->
                <div>
                    <input type="radio" name="cv_status" id="cv_needs_update" value="needs_update" class="peer hidden" {{ old('cv_status', $trainer->cv_status) == 'needs_update' ? 'checked' : '' }}>
                    <label for="cv_needs_update" class="block p-4 border rounded-lg cursor-pointer border-gray-300 peer-checked:border-[#950713] peer-checked:ring-2 peer-checked:ring-[#950713] hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-edit mr-3 text-gray-500"></i>
                                <p class="font-medium text-gray-900">I need to update my CV/Resume</p>
                            </div>
                            <i class="fas fa-check-circle text-[#950713] hidden peer-checked:inline-block"></i>
                        </div>
                    </label>
                </div>
            </div>
            @error('cv_status')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Motivation -->
        <div>
            <label for="motivation" class="block text-sm font-medium text-gray-700 mb-1">Why do you want to teach with us? <span class="text-red-500">*</span></label>
            <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#950713] focus-within:ring-opacity-50 @error('motivation') border-red-500 @enderror">
                <div class="bg-gray-50 flex items-center px-3 border-r border-gray-300">
                    <i class="fas fa-heart text-gray-400"></i>
                </div>
                <textarea name="motivation" id="motivation" rows="4" class="flex-1 block w-full px-3 py-2 border-0 outline-none" placeholder="Share your motivation and why you're interested in teaching technology to young learners" required>{{ old('motivation', $trainer->motivation ?? '') }}</textarea>
            </div>
            @error('motivation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Video Introduction Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Video Introduction <span class="text-gray-400">(Optional)</span></label>
            <p class="text-sm text-gray-500 mb-3">Upload a short video (max 2 minutes) introducing yourself</p>
            
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:bg-gray-50 transition duration-150 ease-in-out {{ $trainer->video_url ? 'hidden' : '' }}" id="video_upload_container">
                <div class="text-center">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                    <p class="mt-1 text-sm text-gray-600">Drag and drop a video file here, or click to select</p>
                    <p class="mt-1 text-xs text-gray-500">MP4, MOV, or WebM format (max 100MB)</p>
                </div>
                <input type="file" name="video_introduction" id="video_introduction" class="hidden" accept="video/mp4,video/quicktime,video/webm">
                <div class="mt-3">
                    <button type="button" id="browse_video_btn" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#950713]">
                        Browse files
                    </button>
                </div>
            </div>
            
            <!-- Video Preview (shown if video exists) -->
            <div id="video_preview" class="rounded-lg overflow-hidden {{ $trainer->video_url ? '' : 'hidden' }}">
                <div class="aspect-w-16 aspect-h-9">
                    @if($trainer->video_url)
                        <video controls class="w-full h-full object-cover">
                            <source src="{{ $trainer->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>
                <div class="bg-gray-100 p-3 flex justify-between items-center">
                    <div class="text-sm text-gray-700" id="video_name">
                        {{ $trainer->video_filename ?? 'Video introduction' }}
                    </div>
                    <button type="button" id="remove_video_btn" class="text-gray-500 hover:text-red-600 focus:outline-none">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <input type="hidden" name="existing_video" value="{{ $trainer->video_url ?? '' }}">
            </div>
            
            @error('video_introduction')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
