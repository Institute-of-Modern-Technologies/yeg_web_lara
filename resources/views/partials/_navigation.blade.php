<!-- Header Navigation -->
<header class="bg-white py-4 px-6 flex justify-between items-center shadow-md fixed top-0 left-0 right-0 w-full z-50">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Left Side - Logo and Desktop Navigation -->
        <div class="flex items-center">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center">
                <span class="logo-young text-xl font-medium">Young</span>
                <span class="logo-experts mx-1 text-xl">Experts</span>
                <span class="logo-group text-xl font-medium">Group</span>
            </a>
            
            <!-- Navigation Links (Desktop Only) -->
            <nav class="hidden md:block ml-10">
                <ul class="flex space-x-8">
                    @php
                        // Check if we're on the home page
                        $isHomePage = request()->path() == '/';
                        // Base URL to prepend to anchors when NOT on home page
                        $homeUrl = $isHomePage ? '' : url('/');
                    @endphp
                    <li><a href="{{ $homeUrl }}#hero-section" class="nav-link font-medium {{ $isHomePage ? 'active' : '' }}" data-section="hero-section">Home</a></li>
                    <li><a href="{{ $homeUrl }}#about" class="nav-link font-medium" data-section="about">About</a></li>
                    <li><a href="{{ $homeUrl }}#our-stages" class="nav-link font-medium" data-section="our-stages">Stages</a></li>
                    <li><a href="{{ $homeUrl }}#about-us" class="nav-link font-medium" data-section="about-us">Programs</a></li>
                    <li><a href="{{ $homeUrl }}#faq" class="nav-link font-medium" data-section="faq">FAQ'S</a></li>
                    <li><a href="{{ route('contact.index') }}" class="nav-link font-medium hover:text-primary transition-colors">Contact</a></li>
                </ul>
            </nav>
        </div>
        
        <!-- Right Side - Login, Enroll Button, Mobile Toggle -->
        <div class="flex items-center space-x-4">
            <!-- Mobile Menu Toggle Button (Only visible on mobile) -->
            <button id="mobile-menu-button" class="md:hidden flex items-center justify-center w-12 h-12 bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 rounded-full shadow-lg focus:outline-none text-white transform transition-all duration-300 hover:scale-105">
                <i class="fas fa-bars text-lg"></i>
            </button>
        @auth
        <a href="/dashboard" class="hidden md:block px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition flex items-center">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>
        @else
        <a href="/login" class="hidden md:block px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition flex items-center">
            <i class="fas fa-user mr-2"></i> Login
        </a>
        @endauth
        
        <!-- Registration Button with Pure CSS Dropdown -->
        <div class="group relative hidden md:block">
            <button id="registerButton" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white font-medium rounded-md shadow-sm transition-all duration-300 flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>Register Now</span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-300 group-hover:rotate-180"></i>
            </button>
            <div class="absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-2xl overflow-hidden border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 ease-in-out" style="z-index: 9999;">
                <div class="py-2">
                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-700">Register as:</p>
                    </div>
                    <a href="/students/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-user-graduate mr-3 text-blue-500"></i>
                        <div>
                            <p class="font-medium">Student</p>
                            <p class="text-xs text-gray-500">Join our learning programs</p>
                        </div>
                    </a>
                    <a href="/teachers/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>
                        <div>
                            <p class="font-medium">Trainer</p>
                            <p class="text-xs text-gray-500">Become a YEG instructor</p>
                        </div>
                    </a>
                    <a href="/schools/register" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-school mr-3 text-purple-500"></i>
                        <div>
                            <p class="font-medium">School</p>
                            <p class="text-xs text-gray-500">Partner with Young Experts</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Social Media Icons -->
        <div class="hidden md:flex space-x-3">
            <a href="mailto:contact@youngexpertsgroup.com" class="social-icon text-gray-600 hover:text-primary">
                <i class="fa-regular fa-envelope"></i>
            </a>
            <a href="https://www.facebook.com/youngexpertsgroup" target="_blank" class="social-icon text-gray-600 hover:text-blue-600">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.youtube.com/shorts/x_kUqKoTZR8" target="_blank" class="social-icon text-gray-600 hover:text-red-600">
                <i class="fab fa-youtube"></i>
            </a>
            <a href="https://www.tiktok.com/@youngexpertsgroup" target="_blank" class="social-icon text-gray-600 hover:text-pink-500">
                <i class="fab fa-tiktok"></i>
            </a>
            <a href="https://www.instagram.com/youngexpertsgroup" target="_blank" class="social-icon text-gray-600 hover:text-purple-600">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-30 hidden transition-opacity duration-300 opacity-0"></div>

<!-- Mobile Menu (positioned correctly with fixed header) -->
<div id="mobile-menu" class="fixed top-0 right-0 bottom-0 w-4/5 max-w-xs bg-gradient-to-br from-white to-gray-50 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto border-l border-gray-100">
    <div class="p-5">
        <!-- Mobile Menu Header -->
        <div class="flex items-center justify-between mb-8 bg-gradient-to-r from-purple-600 to-blue-500 -m-5 p-5 shadow-md relative">
            <div class="flex items-center">
                <span class="text-xl font-medium text-white">Young</span>
                <span class="mx-1 text-xl text-white">Experts</span>
                <span class="text-xl font-medium text-white">Group</span>
            </div>
            <button id="close-mobile-menu" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-white hover:bg-opacity-30 transition-all duration-300 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Mobile Navigation Links -->
        <nav class="mb-8 mt-8">
            <div class="px-4 py-2 mb-4 bg-gray-50 rounded-lg">
                <p class="text-sm uppercase font-bold tracking-wider text-gray-500">Navigation</p>
            </div>
            <ul class="space-y-1">
                @php
                    // Check if we're on the home page
                    $isHomePage = request()->path() == '/';
                    // Base URL to prepend to anchors when NOT on home page
                    $homeUrl = $isHomePage ? '' : url('/');
                @endphp
                <li>
                    <a href="{{ $homeUrl }}#hero-section" class="mobile-nav-link flex items-center py-3 px-4 rounded-lg font-medium {{ $isHomePage ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200" data-section="hero-section">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full text-blue-500 mr-3">
                            <i class="fas fa-home"></i>
                        </div>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ $homeUrl }}#about" class="mobile-nav-link flex items-center py-3 px-4 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200" data-section="about">
                        <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-full text-indigo-500 mr-3">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <span>About</span>
                    </a>
                </li>
                <li>
                    <a href="{{ $homeUrl }}#our-stages" class="mobile-nav-link flex items-center py-3 px-4 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200" data-section="our-stages">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full text-green-500 mr-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span>Stages</span>
                    </a>
                </li>
                <li>
                    <a href="{{ $homeUrl }}#about-us" class="mobile-nav-link flex items-center py-3 px-4 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200" data-section="about-us">
                        <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-full text-purple-500 mr-3">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <span>Programs</span>
                    </a>
                </li>

                <li>
                    <a href="{{ $homeUrl }}#faq" class="mobile-nav-link flex items-center py-3 px-4 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200" data-section="faq">
                        <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full text-yellow-600 mr-3">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <span>FAQ'S</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Mobile Authentication Links -->
        <div class="mb-8">
            <div class="px-4 py-2 mb-4 bg-gray-50 rounded-lg">
                <p class="text-sm uppercase font-bold tracking-wider text-gray-500">Account</p>
            </div>
            
            @auth
                <a href="/dashboard" class="flex items-center py-3 px-4 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 mb-2">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full text-gray-500 mr-3">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
            @else
                <a href="/login" class="flex items-center py-3 px-4 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 mb-2">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full text-gray-500 mr-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <span>Login</span>
                </a>
            @endauth
            
            <div class="px-4 py-2 mt-6 mb-4 bg-gray-50 rounded-lg">
                <p class="text-sm uppercase font-bold tracking-wider text-gray-500">Register</p>
            </div>
            
            <a href="/students/register" class="flex items-center py-3 px-4 rounded-lg mb-2 text-white font-medium bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <div class="flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full text-white mr-3">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <span>Student Registration</span>
            </a>
            
            <a href="/teachers/register" class="flex items-center py-3 px-4 rounded-lg mb-2 text-white font-medium bg-gradient-to-r from-green-500 to-teal-400 hover:from-green-600 hover:to-teal-500 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <div class="flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full text-white mr-3">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <span>Trainer Registration</span>
            </a>
            
            <a href="/schools/register" class="flex items-center py-3 px-4 rounded-lg text-white font-medium bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-600 hover:to-purple-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <div class="flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full text-white mr-3">
                    <i class="fas fa-school"></i>
                </div>
                <span>School Registration</span>
            </a>
        </div>
        
        <!-- Mobile Social Icons -->
        <div class="pt-4 border-t border-gray-100 mt-6">
            <div class="px-4 py-2 mb-4 bg-gray-50 rounded-lg">
                <p class="text-sm uppercase font-bold tracking-wider text-gray-500">Connect With Us</p>
            </div>
            <div class="flex justify-center space-x-3">
                <a href="mailto:contact@youngexpertsgroup.com" class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-purple-600 to-blue-500 text-white hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1">
                    <i class="fa-regular fa-envelope"></i>
                </a>
                <a href="https://www.facebook.com/youngexpertsgroup" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.youtube.com/shorts/x_kUqKoTZR8" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-600 text-white hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://www.tiktok.com/@youngexpertsgroup" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-pink-600 text-white hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://www.instagram.com/youngexpertsgroup" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 text-white hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Navigation CSS -->
<style>
    /* Style for active navigation links */
    .nav-link.active {
        color: #ff6b6b; /* Using neon-pink/primary color for active state */
        position: relative;
    }
    
    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #ff6b6b;
        border-radius: 2px;
    }
    
    /* Mobile menu styles */
    .social-icon-mobile {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .social-icon-mobile:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Body class when menu is open */
    .menu-open {
        overflow: hidden;
    }
</style>

<!-- Mobile Menu JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeMenuButton = document.getElementById('close-mobile-menu');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
        const body = document.body;
        
        // Toggle mobile menu with enhanced animations
        function toggleMobileMenu() {
            if (mobileMenu.classList.contains('translate-x-full')) {
                // Open menu with animations
                openMobileMenu();
            } else {
                // Close menu with animations
                closeMobileMenu();
            }
        }
        
        // Open mobile menu function with enhanced animations
        function openMobileMenu() {
            // Show overlay first
            mobileMenuOverlay.classList.remove('hidden');
            
            // Trigger animations with proper timing
            requestAnimationFrame(() => {
                // Fade in overlay
                mobileMenuOverlay.classList.remove('opacity-0');
                mobileMenuOverlay.classList.add('opacity-100');
                
                // Slide in menu
                mobileMenu.classList.remove('translate-x-full');
                
                // Add a subtle animation to menu items
                animateMenuItems();
                
                // Prevent body scrolling
                body.classList.add('menu-open');
            });
        }
        
        // Close mobile menu function with enhanced animations
        function closeMobileMenu() {
            // Animate menu closing
            mobileMenu.classList.add('translate-x-full');
            mobileMenuOverlay.classList.remove('opacity-100');
            mobileMenuOverlay.classList.add('opacity-0');
            
            // Hide overlay after transition completes
            setTimeout(() => {
                mobileMenuOverlay.classList.add('hidden');
                
                // Enable body scrolling
                body.classList.remove('menu-open');
            }, 300); // Match transition duration
        }
        
        // Animate menu items with a subtle staggered effect
        function animateMenuItems() {
            // Get all the major sections in the mobile menu
            const menuSections = mobileMenu.querySelectorAll('nav, .mb-8, .pt-4');
            
            // Apply staggered fade-in animation
            menuSections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                
                setTimeout(() => {
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 100 + (index * 100)); // Staggered delay
            });
        }
        
        // Toggle button animation
        function animateMenuButton() {
            mobileMenuButton.classList.add('scale-90');
            setTimeout(() => {
                mobileMenuButton.classList.remove('scale-90');
            }, 200);
        }
        
        // Event listeners with improved interaction feedback
        mobileMenuButton.addEventListener('click', function() {
            animateMenuButton();
            toggleMobileMenu();
        });
        
        closeMenuButton.addEventListener('click', closeMobileMenu);
        mobileMenuOverlay.addEventListener('click', closeMobileMenu);
        
        // Close menu when clicking navigation links with active state management
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Remove active class from all links
                mobileNavLinks.forEach(navLink => {
                    navLink.classList.remove('bg-blue-50', 'text-blue-600');
                });
                
                // Add active class to clicked link
                this.classList.add('bg-blue-50', 'text-blue-600');
                
                // Close the menu
                closeMobileMenu();
            });
        });
    });
</script>
