/**
 * Direct Bulk Actions JS - Clean implementation for bulk promote and repeat
 */

console.log('Direct Bulk Actions script loaded');

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - Direct Bulk Actions');
    
    // Override existing bulk promote function
    window.submitBulkPromote = function() {
        console.log('Direct submitBulkPromote called');
        
        // Get the selected students
        var selectedStudents = [];
        document.querySelectorAll('.student-checkbox:checked').forEach(function(checkbox) {
            selectedStudents.push(checkbox.value);
        });
        
        // Get the selected stage
        var stageId = document.getElementById('bulk_promote_stage_id').value;
        
        // Get CSRF token
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Use absolute URL to avoid any routing issues
        var baseUrl = window.location.origin;
        var formAction = baseUrl + '/admin/students/bulk-promote';
        
        // Get the button
        var button = document.querySelector('#bulk-promote-stage-modal button[onclick*="submitBulkPromote"]');
        var originalButtonText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        button.disabled = true;
        
        // Create form data
        var formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('_method', 'PUT');
        formData.append('stage_id', stageId);
        
        // Add all selected students
        selectedStudents.forEach(function(studentId) {
            formData.append('student_ids[]', studentId);
        });
        
        // Log the data being sent
        console.log('Sending bulk promote request to absolute URL:', formAction);
        console.log('Stage ID:', stageId);
        console.log('Student IDs:', selectedStudents);
        
        // Send AJAX request using XMLHttpRequest for maximum compatibility
        var xhr = new XMLHttpRequest();
        xhr.open('POST', formAction, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.responseType = 'json';
        
        xhr.onload = function() {
            console.log('XHR Status:', xhr.status);
            console.log('XHR Response:', xhr.response);
            
            // Reset button
            button.disabled = false;
            button.innerHTML = originalButtonText;
            
            if (xhr.status >= 200 && xhr.status < 300) {
                // Success
                var data = xhr.response;
                
                // Close modal
                document.getElementById('bulk-promote-stage-modal').classList.add('hidden');
                
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Students promoted successfully',
                    icon: 'success',
                    confirmButtonColor: '#950713'
                }).then(function() {
                    // Refresh page
                    window.location.reload();
                });
            } else {
                // Error
                console.error('Error response:', xhr.response);
                
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to process request: ' + xhr.status,
                    icon: 'error',
                    confirmButtonColor: '#950713'
                });
            }
        };
        
        xhr.onerror = function() {
            console.error('XHR Error');
            
            // Reset button
            button.disabled = false;
            button.innerHTML = originalButtonText;
            
            // Show error message
            Swal.fire({
                title: 'Error',
                text: 'Network error occurred',
                icon: 'error',
                confirmButtonColor: '#950713'
            });
        };
        
        // Send the request
        xhr.send(formData);
    };
    
    // Override existing bulk repeat function
    window.submitBulkRepeat = function() {
        console.log('Direct submitBulkRepeat called');
        
        // Get the selected students
        var selectedStudents = [];
        document.querySelectorAll('.student-checkbox:checked').forEach(function(checkbox) {
            selectedStudents.push(checkbox.value);
        });
        
        // Get the selected stage
        var stageId = document.getElementById('bulk_repeat_stage_id').value;
        
        // Get CSRF token
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Use absolute URL to avoid any routing issues
        var baseUrl = window.location.origin;
        var formAction = baseUrl + '/admin/students/bulk-repeat';
        
        // Get the button
        var button = document.querySelector('#bulk-repeat-stage-modal button[onclick*="submitBulkRepeat"]');
        var originalButtonText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        button.disabled = true;
        
        // Create form data
        var formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('_method', 'PUT');
        formData.append('stage_id', stageId);
        
        // Add all selected students
        selectedStudents.forEach(function(studentId) {
            formData.append('student_ids[]', studentId);
        });
        
        // Log the data being sent
        console.log('Sending bulk repeat request to absolute URL:', formAction);
        console.log('Stage ID:', stageId);
        console.log('Student IDs:', selectedStudents);
        
        // Send AJAX request using XMLHttpRequest for consistency with promote
        var xhr = new XMLHttpRequest();
        xhr.open('POST', formAction, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.responseType = 'json';
        
        xhr.onload = function() {
            console.log('XHR Status:', xhr.status);
            console.log('XHR Response:', xhr.response);
            
            // Reset button
            button.disabled = false;
            button.innerHTML = originalButtonText;
            
            if (xhr.status >= 200 && xhr.status < 300) {
                // Success
                var data = xhr.response;
                
                // Close modal
                document.getElementById('bulk-repeat-stage-modal').classList.add('hidden');
                
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Students assigned to repeat successfully',
                    icon: 'success',
                    confirmButtonColor: '#950713'
                }).then(function() {
                    // Refresh page
                    window.location.reload();
                });
            } else {
                // Error
                console.error('Error response:', xhr.response);
                
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to process request: ' + xhr.status,
                    icon: 'error',
                    confirmButtonColor: '#950713'
                });
            }
        };
        
        xhr.onerror = function() {
            console.error('XHR Error');
            
            // Reset button
            button.disabled = false;
            button.innerHTML = originalButtonText;
            
            // Show error message
            Swal.fire({
                title: 'Error',
                text: 'Network error occurred',
                icon: 'error',
                confirmButtonColor: '#950713'
            });
        };
        
        // Send the request
        xhr.send(formData);
    };
});
