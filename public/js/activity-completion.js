/**
 * Activity Completion JS
 * Handles activity completion and revert modal functionality
 */

// Activity completion modal handling
function showActivityCompletionModal(activityId, activityName) {
    // Get modal elements
    const modal = document.getElementById('activityCompletionModal');
    const modalTitle = document.getElementById('activityCompletionModalTitle');
    const completeBtn = document.getElementById('completeActivityBtn');
    const revertBtn = document.getElementById('revertActivityBtn');
    const activityIdField = document.getElementById('activityId');
    const loadingSpinner = document.getElementById('activityModalLoadingSpinner');
    const successMessage = document.getElementById('activitySuccessMessage');
    const errorMessage = document.getElementById('activityErrorMessage');
    
    // Reset modal state
    hideElement(loadingSpinner);
    hideElement(successMessage);
    hideElement(errorMessage);
    
    // Set modal title and activity ID
    modalTitle.textContent = activityName;
    activityIdField.value = activityId;
    
    // Check if activity is already completed
    const completionBadge = document.getElementById(`completion-badge-${activityId}`);
    const isCompleted = completionBadge.querySelector('.bg-green-100') !== null;
    
    // Show/hide buttons based on completion status
    if (isCompleted) {
        hideElement(completeBtn);
        showElement(revertBtn);
    } else {
        showElement(completeBtn);
        hideElement(revertBtn);
    }
    
    // Show the modal
    modal.classList.remove('hidden');
}

function hideActivityCompletionModal() {
    // Hide the modal
    document.getElementById('activityCompletionModal').classList.add('hidden');
}

function completeActivity() {
    const activityId = document.getElementById('activityId').value;
    const loadingSpinner = document.getElementById('activityModalLoadingSpinner');
    const successMessage = document.getElementById('activitySuccessMessage');
    const errorMessage = document.getElementById('activityErrorMessage');
    
    // Show loading spinner
    showElement(loadingSpinner);
    hideElement(successMessage);
    hideElement(errorMessage);
    
    // Send AJAX request to mark activity as complete
    fetch(`/student/activities/${activityId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading spinner
        hideElement(loadingSpinner);
        
        if (data.success) {
            // Show success message
            successMessage.textContent = 'Activity marked as complete!';
            showElement(successMessage);
            
            // Update UI badge
            const completionBadge = document.getElementById(`completion-badge-${activityId}`);
            completionBadge.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Completed
                </span>
            `;
            
            // Update modal buttons
            hideElement(document.getElementById('completeActivityBtn'));
            showElement(document.getElementById('revertActivityBtn'));
            
            // Close modal after delay
            setTimeout(hideActivityCompletionModal, 2000);
        } else {
            // Show error message
            errorMessage.textContent = data.message || 'An error occurred';
            showElement(errorMessage);
        }
    })
    .catch(error => {
        // Hide loading spinner
        hideElement(loadingSpinner);
        
        // Show error message
        errorMessage.textContent = 'An error occurred while processing your request';
        showElement(errorMessage);
        console.error('Error:', error);
    });
}

function revertActivityCompletion() {
    const activityId = document.getElementById('activityId').value;
    const loadingSpinner = document.getElementById('activityModalLoadingSpinner');
    const successMessage = document.getElementById('activitySuccessMessage');
    const errorMessage = document.getElementById('activityErrorMessage');
    
    // Show loading spinner
    showElement(loadingSpinner);
    hideElement(successMessage);
    hideElement(errorMessage);
    
    // Send AJAX request to revert activity completion
    fetch(`/student/activities/${activityId}/revert`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading spinner
        hideElement(loadingSpinner);
        
        if (data.success) {
            // Show success message
            successMessage.textContent = 'Activity status reverted!';
            showElement(successMessage);
            
            // Update UI badge
            const completionBadge = document.getElementById(`completion-badge-${activityId}`);
            completionBadge.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-clock mr-1"></i> Pending
                </span>
            `;
            
            // Update modal buttons
            showElement(document.getElementById('completeActivityBtn'));
            hideElement(document.getElementById('revertActivityBtn'));
            
            // Close modal after delay
            setTimeout(hideActivityCompletionModal, 2000);
        } else {
            // Show error message
            errorMessage.textContent = data.message || 'An error occurred';
            showElement(errorMessage);
        }
    })
    .catch(error => {
        // Hide loading spinner
        hideElement(loadingSpinner);
        
        // Show error message
        errorMessage.textContent = 'An error occurred while processing your request';
        showElement(errorMessage);
        console.error('Error:', error);
    });
}

// Helper functions
function showElement(element) {
    if (element) element.classList.remove('hidden');
}

function hideElement(element) {
    if (element) element.classList.add('hidden');
}

function showStageDetails() {
    document.getElementById('stageDetailsModal').classList.remove('hidden');
}

function hideStageDetails() {
    document.getElementById('stageDetailsModal').classList.add('hidden');
}

function showActivities() {
    document.getElementById('activitiesModal').classList.remove('hidden');
}

function hideActivities() {
    document.getElementById('activitiesModal').classList.add('hidden');
}
