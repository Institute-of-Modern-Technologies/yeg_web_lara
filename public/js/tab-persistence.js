/**
 * Tab Persistence Script for Student Stage Management
 * This script ensures that the active tab is preserved after promote/repeat actions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Fix for blank tab issue - check if we need to set a tab from URL params
    const urlParams = new URLSearchParams(window.location.search);
    const tabFromUrl = urlParams.get('active_tab');
    
    // Get Alpine.js instance
    const alpineContainer = document.querySelector('[x-data]');
    const alpine = alpineContainer?.__x;
    
    if (alpine) {
        // If we have an active_tab in the URL, force it to be selected
        if (tabFromUrl && ['personal', 'program', 'payment'].includes(tabFromUrl)) {
            console.log('Setting active tab from URL parameter:', tabFromUrl);
            alpine.$data.activeTab = tabFromUrl;
        }
        
        // Also check for active_tab in session flash data
        // This may be stored in a meta tag or other element by Laravel
        const sessionTab = document.querySelector('meta[name="active-tab"]')?.getAttribute('content');
        if (sessionTab && ['personal', 'program', 'payment'].includes(sessionTab)) {
            console.log('Setting active tab from session data:', sessionTab);
            alpine.$data.activeTab = sessionTab;
        }
    } else {
        console.error('Alpine.js instance not found');
    }
    
    // Set up event listeners for modal openings
    document.addEventListener('click', function(e) {
        // Find the button that might be triggering the modals
        const button = e.target.closest('button, a[href*="promote"], a[href*="repeat"]');
        if (!button) return;
        
        // Get the current active tab
        let currentTab = 'program'; // Default
        if (alpine) {
            currentTab = alpine.$data.activeTab || 'program';
        }
        
        // Update the promote modal form if this is a promote action
        if (button.textContent.toLowerCase().includes('promote') || 
            button.classList.contains('promote') || 
            button.getAttribute('href')?.includes('promote')) {
            
            setTimeout(() => {
                const modal = document.getElementById('promote-stage-modal');
                if (!modal) return;
                
                // Update the hidden input with the current tab
                const activeTabInput = modal.querySelector('input[name="active_tab"]');
                if (activeTabInput) {
                    console.log('Setting promote form active tab to:', currentTab);
                    activeTabInput.value = currentTab;
                }
            }, 100);
        }
        
        // Update the repeat modal form if this is a repeat action
        if (button.textContent.toLowerCase().includes('repeat') || 
            button.classList.contains('repeat') || 
            button.getAttribute('href')?.includes('repeat')) {
            
            setTimeout(() => {
                const modal = document.getElementById('repeat-stage-modal');
                if (!modal) return;
                
                // Update the hidden input with the current tab
                const activeTabInput = modal.querySelector('input[name="active_tab"]');
                if (activeTabInput) {
                    console.log('Setting repeat form active tab to:', currentTab);
                    activeTabInput.value = currentTab;
                }
            }, 100);
        }
    });
    
    // For forms without proper event listeners - also monitor DOM changes
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const target = mutation.target;
                
                if ((target.id === 'promote-stage-modal' || target.id === 'repeat-stage-modal') && 
                    !target.classList.contains('hidden')) {
                    
                    // Get current active tab from Alpine data
                    let currentTab = 'program'; // Default
                    if (alpine) {
                        currentTab = alpine.$data.activeTab || 'program';
                    }
                    
                    // Update the hidden input
                    const activeTabInput = target.querySelector('input[name="active_tab"]');
                    if (activeTabInput) {
                        console.log(`Setting ${target.id} active tab to:`, currentTab);
                        activeTabInput.value = currentTab;
                    }
                }
            }
        });
    });
    
    // Observe both modals for visibility changes
    const promoteModal = document.getElementById('promote-stage-modal');
    const repeatModal = document.getElementById('repeat-stage-modal');
    
    if (promoteModal) observer.observe(promoteModal, { attributes: true });
    if (repeatModal) observer.observe(repeatModal, { attributes: true });
    
    // As a fallback, add active tab as a query parameter to form actions
    document.querySelectorAll('#promote-stage-modal form, #repeat-stage-modal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Get the current active tab
            let currentTab = 'program';
            if (alpine) {
                currentTab = alpine.$data.activeTab || 'program';
            }
            
            // Make sure hidden input field is set
            let activeTabInput = this.querySelector('input[name="active_tab"]');
            if (!activeTabInput) {
                activeTabInput = document.createElement('input');
                activeTabInput.type = 'hidden';
                activeTabInput.name = 'active_tab';
                this.appendChild(activeTabInput);
            }
            activeTabInput.value = currentTab;
            
            // Also modify the form action to include the active tab
            // BUT DON'T modify the form action as this can break the route/endpoint
            // Instead, just make sure the hidden field is properly set
            console.log('Form submitting with active_tab:', activeTabInput.value);
        });
    });
});
