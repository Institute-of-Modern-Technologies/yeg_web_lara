/**
 * Stage Modal Level Display Fix
 * 
 * This script ensures that stage modals display the correct level field value from the database,
 * not the order value.
 */

// Wait until the document is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Ensure window.stages is initialized
    window.stages = window.stages || [];
    // Override the openPromoteModal function to display correct level
    window.openPromoteModal = function(studentId) {
        console.log('Opening promote modal for student ID:', studentId);
        
        // Get the student information using AJAX
        fetch(`/admin/students/${studentId}/get-stage-info`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Student stage info:', data);
                    
                    // Set the form action
                    document.getElementById('promoteForm').action = `/admin/students/${studentId}/promote-stage`;
                    
                    // Get the promote stage select element
                    const promoteSelect = document.getElementById('promote_stage_id');
                    promoteSelect.innerHTML = '';

                    // Current stage order
                    const currentStageOrder = data.current_stage_order || 0;

                    // Filter stages with higher order than the current stage
                    const nextStages = window.stages.filter(stage => stage.order > currentStageOrder);
                    console.log('Available next stages:', nextStages);
                    
                    if (nextStages.length > 0) {
                        // Add options for next stages
                        nextStages.forEach(stage => {
                            const option = document.createElement('option');
                            option.value = stage.id;
                            
                            // Display level from database (not order), with fallback if null/empty
                            const levelText = stage.level ? stage.level : 'No Level';
                            console.log(`Stage ${stage.id} level:`, stage.level);
                            option.textContent = `${stage.name} - ${levelText}`;
                            
                            promoteSelect.appendChild(option);
                        });
                    } else {
                        // If no higher stages available
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'No higher stages available';
                        promoteSelect.appendChild(option);
                    }

                    // Show the modal
                    document.getElementById('promote-stage-modal').classList.remove('hidden');
                } else {
                    console.error('Error getting student stage info:', data.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error getting student stage information',
                        confirmButtonColor: '#950713'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error fetching student information.',
                    confirmButtonColor: '#950713'
                });
            });
    };

    // Override the openRepeatModal function to display correct level
    window.openRepeatModal = function(studentId) {
        console.log('Opening repeat modal for student ID:', studentId);
        
        // Get the student information using AJAX
        fetch(`/admin/students/${studentId}/get-stage-info`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Student stage info:', data);
                    
                    // Set the form action
                    document.getElementById('repeatForm').action = `/admin/students/${studentId}/repeat-stage`;
                    
                    // Get the repeat stage select element
                    const repeatSelect = document.getElementById('repeat_stage_id');
                    repeatSelect.innerHTML = '';

                    // Current stage ID
                    const currentStageId = data.current_stage_id;

                    if (window.stages.length > 0) {
                        // Add options for all stages
                        window.stages.forEach(stage => {
                            const option = document.createElement('option');
                            option.value = stage.id;
                            
                            // Display level from database (not order), with fallback if null/empty
                            const levelText = stage.level ? stage.level : 'No Level';
                            console.log(`Stage ${stage.id} level:`, stage.level);
                            option.textContent = `${stage.name} - ${levelText}`;
                            
                            // Mark the current stage as selected
                            if (stage.id == currentStageId) {
                                option.selected = true;
                                option.textContent += ' (Current)';
                            }
                            
                            repeatSelect.appendChild(option);
                        });
                    } else {
                        // If no stages available
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'No stages available';
                        repeatSelect.appendChild(option);
                    }

                    // Show the modal
                    document.getElementById('repeat-stage-modal').classList.remove('hidden');
                } else {
                    console.error('Error getting student stage info:', data.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error getting student stage information',
                        confirmButtonColor: '#950713'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error fetching student information.',
                    confirmButtonColor: '#950713'
                });
            });
    };

    // Log stages data to verify level is present
    if (window.stages && window.stages.length > 0) {
        console.log('Stage level verification:');
        window.stages.forEach(stage => {
            console.log(`Stage ${stage.id} (${stage.name}): level=${stage.level}, order=${stage.order}`);
        });
    }
});
