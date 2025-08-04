/**
 * Global CSRF token setup for all AJAX requests
 * This ensures all jQuery AJAX requests automatically include the CSRF token
 */
$(document).ready(function() {
    // Get the CSRF token from the meta tag
    var token = $('meta[name="csrf-token"]').attr('content');
    
    if (token) {
        // Set up jQuery AJAX to always send the CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        
        console.log('CSRF token configured for AJAX requests');
    } else {
        console.error('CSRF token not found - AJAX requests requiring CSRF protection will fail');
    }
});
