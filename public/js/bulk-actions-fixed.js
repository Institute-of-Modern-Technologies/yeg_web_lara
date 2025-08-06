/**
 * Bulk Actions JS - Handles bulk promote and repeat actions
 */

console.log('Bulk Actions JS loaded');

// Wait for document ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing bulk actions');
    // Initialize once DOM is loaded
    initBulkActions();
});

function initBulkActions() {
    // Get buttons by ID for more reliable selection
    const bulkPromoteBtn = document.querySelector('#bulk-promote-stage-modal button:not([onclick*="close"])');
    const bulkRepeatBtn = document.querySelector('#bulk-repeat-stage-modal button:not([onclick*="close"])');
    
    console.log('Promote button found:', bulkPromoteBtn !== null);
    console.log('Repeat button found:', bulkRepeatBtn !== null);

    if (bulkPromoteBtn) {
        bulkPromoteBtn.onclick = function(e) {
            e.preventDefault();
            console.log('Promote button clicked - using custom handler');
            submitBulkPromote();
            return false;
        };
    }

    if (bulkRepeatBtn) {
        bulkRepeatBtn.onclick = function(e) {
            e.preventDefault();
            console.log('Repeat button clicked - using custom handler');
            submitBulkRepeat();
            return false;
        };
    }
}

function submitBulkPromote() {
    console.log('Submitting bulk promote - simple version');
    const form = document.getElementById('bulkPromoteForm');
    if (!form) {
        console.error('Bulk promote form not found');
        return;
    }

    // Find the button more reliably
    const submitButton = document.querySelector('#bulk-promote-stage-modal button:not([onclick*="close"])');
    console.log('Found promote button:', submitButton !== null);
    
    // Show loading state
    if (submitButton) {
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        submitButton.disabled = true;
    }
    
    // Get form data and add PUT method
    const formData = new FormData(form);
    formData.append('_method', 'PUT');
    
    // Log form data for debugging
    console.log('Form action:', form.action);
    console.log('Selected stage:', formData.get('stage_id'));
    
    // Use hardcoded URL with dynamic base path
    const baseUrl = window.location.pathname.split('/admin/')[0] || '';
    const url = baseUrl + '/admin/students/bulk-promote';
    console.log('Base URL detected:', baseUrl);
    console.log('Using hardcoded URL:', url);
    
    // Simple fetch-based AJAX call
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Success response:', data);
        
        // Reset button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Assign to Stage';
        }
        
        // Close modal
        document.getElementById('bulk-promote-stage-modal').classList.add('hidden');
        
        // Show success message
        Swal.fire({
            title: 'Success!',
            text: data.message || 'Students promoted successfully',
            icon: 'success',
            confirmButtonColor: '#950713'
        }).then(() => {
            // Refresh page
            window.location.reload();
        });
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Reset button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Assign to Stage';
        }
        
        // Show error message
        Swal.fire({
            title: 'Error',
            text: 'Failed to process request: ' + error.message,
            icon: 'error',
            confirmButtonColor: '#950713'
        });
    });
}

function submitBulkRepeat() {
    console.log('Submitting bulk repeat - simple version');
    const form = document.getElementById('bulkRepeatForm');
    if (!form) {
        console.error('Bulk repeat form not found');
        return;
    }

    // Find the button more reliably
    const submitButton = document.querySelector('#bulk-repeat-stage-modal button:not([onclick*="close"])');
    console.log('Found repeat button:', submitButton !== null);
    
    // Show loading state
    if (submitButton) {
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        submitButton.disabled = true;
    }
    
    // Get form data and add PUT method
    const formData = new FormData(form);
    formData.append('_method', 'PUT');
    
    // Log form data for debugging
    console.log('Form action:', form.action);
    console.log('Selected stage:', formData.get('stage_id'));
    
    // Use hardcoded URL with dynamic base path
    const baseUrl = window.location.pathname.split('/admin/')[0] || '';
    const url = baseUrl + '/admin/students/bulk-repeat';
    console.log('Base URL detected:', baseUrl);
    console.log('Using hardcoded URL:', url);
    
    // Simple fetch-based AJAX call
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Success response:', data);
        
        // Reset button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Assign to Repeat';
        }
        
        // Close modal
        document.getElementById('bulk-repeat-stage-modal').classList.add('hidden');
        
        // Show success message
        Swal.fire({
            title: 'Success!',
            text: data.message || 'Students assigned to repeat successfully',
            icon: 'success',
            confirmButtonColor: '#950713'
        }).then(() => {
            // Refresh page
            window.location.reload();
        });
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Reset button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Assign to Repeat';
        }
        
        // Show error message
        Swal.fire({
            title: 'Error',
            text: 'Failed to process request: ' + error.message,
            icon: 'error',
            confirmButtonColor: '#950713'
        });
    });
}
