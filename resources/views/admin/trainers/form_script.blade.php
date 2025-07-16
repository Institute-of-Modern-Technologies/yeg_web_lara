<!-- Form Script Section -->
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
