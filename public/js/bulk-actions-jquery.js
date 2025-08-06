/**
 * Bulk Actions JS (jQuery version) - Handles bulk promote and repeat actions
 */

console.log('Bulk Actions jQuery version loaded');

// Wait for document ready
$(document).ready(function() {
    console.log('DOM loaded, initializing bulk actions (jQuery)');
    
    // We're now using the inline onclick handlers instead of jQuery click events
    // This ensures we don't have conflicts between the handlers
    console.log('Using inline onclick handlers for bulk actions');
});

function submitBulkPromote() {
    console.log('Submitting bulk promote - jQuery version');
    
    // Get the form
    var $form = $('#bulkPromoteForm');
    var formAction = $form.attr('action');
    console.log('Form action:', formAction);
    
    // Find the button
    var $submitButton = $('#bulk-promote-stage-modal button[onclick*="submitBulkPromote"]');
    console.log('Found promote button:', $submitButton.length > 0);
    
    // Store original button text
    var originalButtonText = $submitButton.html();
    
    // Show loading state
    $submitButton.html('<i class="fas fa-spinner fa-spin mr-2"></i> Processing...');
    $submitButton.prop('disabled', true);
    
    // Get selected stage
    var stageId = $form.find('select[name="stage_id"]').val();
    console.log('Selected stage:', stageId);
    
    // Get selected student IDs
    var studentIds = [];
    $('.student-checkbox:checked').each(function() {
        studentIds.push($(this).val());
    });
    console.log('Selected students:', studentIds);
    
    // Make the AJAX request with jQuery
    $.ajax({
        url: formAction,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            '_method': 'PUT',
            'stage_id': stageId,
            'student_ids': studentIds
        },
        dataType: 'json',
        success: function(data) {
            console.log('Success response:', data);
            
            // Reset button
            $submitButton.prop('disabled', false);
            $submitButton.html(originalButtonText);
            
            // Close modal
            $('#bulk-promote-stage-modal').addClass('hidden');
            
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
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);
            
            // Reset button
            $submitButton.prop('disabled', false);
            $submitButton.html(originalButtonText);
            
            // Show error message
            Swal.fire({
                title: 'Error',
                text: 'Failed to process request: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error),
                icon: 'error',
                confirmButtonColor: '#950713'
            });
        }
    });
}

function submitBulkRepeat() {
    console.log('Submitting bulk repeat - jQuery version');
    
    // Get the form
    var $form = $('#bulkRepeatForm');
    var formAction = $form.attr('action');
    console.log('Form action:', formAction);
    
    // Find the button
    var $submitButton = $('#bulk-repeat-stage-modal button[onclick*="submitBulkRepeat"]');
    console.log('Found repeat button:', $submitButton.length > 0);
    
    // Store original button text
    var originalButtonText = $submitButton.html();
    
    // Show loading state
    $submitButton.html('<i class="fas fa-spinner fa-spin mr-2"></i> Processing...');
    $submitButton.prop('disabled', true);
    
    // Get selected stage
    var stageId = $form.find('select[name="stage_id"]').val();
    console.log('Selected stage:', stageId);
    
    // Get selected student IDs
    var studentIds = [];
    $('.student-checkbox:checked').each(function() {
        studentIds.push($(this).val());
    });
    console.log('Selected students:', studentIds);
    
    // Make the AJAX request with jQuery
    $.ajax({
        url: formAction,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            '_method': 'PUT',
            'stage_id': stageId,
            'student_ids': studentIds
        },
        dataType: 'json',
        success: function(data) {
            console.log('Success response:', data);
            
            // Reset button
            $submitButton.prop('disabled', false);
            $submitButton.html(originalButtonText);
            
            // Close modal
            $('#bulk-repeat-stage-modal').addClass('hidden');
            
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
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);
            
            // Reset button
            $submitButton.prop('disabled', false);
            $submitButton.html(originalButtonText);
            
            // Show error message
            Swal.fire({
                title: 'Error',
                text: 'Failed to process request: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error),
                icon: 'error',
                confirmButtonColor: '#950713'
            });
        }
    });
}
