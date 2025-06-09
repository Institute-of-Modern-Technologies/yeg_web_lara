// Sticky Header functionality
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.sticky-header');
    if (!header) return; // Exit if no header is found

    const headerHeight = header.offsetHeight;
    
    // Update header class on scroll
    function handleScroll() {
        if (window.scrollY > 20) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    // Initial call to set correct state
    handleScroll();
    
    // Add scroll event listener
    window.addEventListener('scroll', handleScroll);

    // For pages with mobile menus, ensure proper scrolling
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('block');
            
            // Add 'mobile-menu-open' class to the body when menu is open
            document.body.classList.toggle('mobile-menu-open');
        });
    }
});
