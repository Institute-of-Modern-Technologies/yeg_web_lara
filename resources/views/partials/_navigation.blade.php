<!-- Header Navigation -->
<header class="bg-white py-4 px-6 flex justify-between items-center shadow-sm sticky-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="flex items-center">
        <span class="logo-young text-xl font-medium">Young</span>
        <span class="logo-experts mx-1 text-xl">Experts</span>
        <span class="logo-group text-xl font-medium">Group</span>
    </a>
    
    <!-- Navigation Links -->
    <nav class="hidden md:block">
        <ul class="flex space-x-8">
            <li><a href="#hero-section" class="nav-link font-medium active" data-section="hero-section">Home</a></li>
            <li><a href="#programs" class="nav-link font-medium" data-section="programs">About</a></li>
            <li><a href="#our-stages" class="nav-link font-medium" data-section="our-stages">Stages</a></li>
            <li><a href="#about-us" class="nav-link font-medium" data-section="about-us">Programs</a></li>
            <li><a href="#faq" class="nav-link font-medium" data-section="faq">FAQ'S</a></li>
        </ul>
    </nav>
    
    <!-- Right Side - Login, Enroll Button and Social Icons -->
    <div class="flex items-center space-x-4">
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
</style>
