<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard - Young Experts Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#950713',
                        'primary-dark': '#7a0610',
                        'primary-light': '#b31a26'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-primary text-white shadow-md">
            <div class="container mx-auto px-6 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <img src="{{ asset('images/yeg-logo.png') }}" alt="YEG Logo" class="h-10 mr-2" onerror="this.src='https://via.placeholder.com/40x40?text=YEG'; this.onerror='';">
                            <div>
                                <span class="text-white text-xl font-medium">Young</span>
                                <span class="text-yellow-300 mx-1 text-xl">Experts</span>
                                <span class="text-white text-xl font-medium">Group</span>
                            </div>
                        </a>
                        <span class="ml-4 text-sm bg-primary-dark px-3 py-1 rounded-full">Student Portal</span>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-white hover:text-gray-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="relative group">
                            <button class="flex items-center space-x-1 text-sm hover:text-gray-200">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-yellow-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center">2</span>
                            </button>
                            <!-- Notifications Dropdown -->
                            <div class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden group-hover:block">
                                <div class="py-2 px-4 bg-gray-100 text-sm font-semibold">Notifications</div>
                                <div class="max-h-60 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-100 border-b">
                                        <p class="text-sm font-medium">New assignment posted</p>
                                        <p class="text-xs text-gray-500">Web Development - 20 minutes ago</p>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-100">
                                        <p class="text-sm font-medium">Upcoming class reminder</p>
                                        <p class="text-xs text-gray-500">Data Science - Tomorrow at 10:00 AM</p>
                                    </a>
                                </div>
                                <a href="#" class="block bg-gray-100 text-center py-2 text-xs text-primary hover:text-primary-dark">View all notifications</a>
                            </div>
                        </div>
                        
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-sm hover:text-gray-200">
                                <div class="w-8 h-8 rounded-full bg-primary-dark flex items-center justify-center uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <!-- User Dropdown -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden group-hover:block">
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> My Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <div class="border-t"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex flex-1">
            <!-- Mobile Sidebar - Hidden by default, shown when toggled -->
            <div id="mobile-sidebar" class="fixed inset-0 z-40 hidden">
                <div class="absolute inset-0 bg-black opacity-50" id="mobile-sidebar-backdrop"></div>
                <div class="absolute left-0 top-0 h-full w-64 bg-primary-dark text-white p-4 transform transition-transform duration-300 ease-in-out">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold">Menu</h2>
                        <button id="close-sidebar-button" class="text-white hover:text-gray-300">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <nav>
                        <ul class="space-y-2">
                            <li>
                                <a href="#" class="block py-2 px-4 rounded bg-primary">
                                    <i class="fas fa-home mr-2"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                    <i class="fas fa-book mr-2"></i> My Courses
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                    <i class="fas fa-tasks mr-2"></i> Assignments
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                    <i class="fas fa-calendar-alt mr-2"></i> Schedule
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                    <i class="fas fa-chart-line mr-2"></i> Progress
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                    <i class="fas fa-user-cog mr-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                    <i class="fas fa-question-circle mr-2"></i> Help & Support
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Desktop Sidebar -->
            <aside class="w-64 bg-primary-dark text-white p-4 hidden md:block">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Student Portal</h2>
                </div>
                <nav>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="flex items-center py-2 px-4 rounded bg-primary">
                                <i class="fas fa-home w-5 mr-2"></i> 
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                <i class="fas fa-book w-5 mr-2"></i> 
                                <span>My Courses</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                <i class="fas fa-tasks w-5 mr-2"></i> 
                                <span>Assignments</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                <i class="fas fa-calendar-alt w-5 mr-2"></i> 
                                <span>Schedule</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                <i class="fas fa-chart-line w-5 mr-2"></i> 
                                <span>Progress</span>
                            </a>
                        </li>
                        <li class="pt-4 mt-4 border-t border-primary">
                            <a href="#" class="flex items-center py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                <i class="fas fa-user-cog w-5 mr-2"></i> 
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center py-2 px-4 rounded hover:bg-primary transition-colors duration-200">
                                <i class="fas fa-question-circle w-5 mr-2"></i> 
                                <span>Help & Support</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Content Area -->
            <main class="flex-1 p-6">
                <h1 class="text-3xl font-bold mb-6">Student Dashboard</h1>

                <!-- Welcome Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-2">Welcome to Your Learning Portal!</h2>
                    <p class="text-gray-600">Track your progress, complete assignments, and enhance your skills with our interactive courses.</p>
                </div>

                <!-- Course Cards -->
                <h2 class="text-xl font-bold mb-4">My Courses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Course Card 1 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-40 bg-gradient-to-r from-blue-500 to-purple-600 p-6 flex items-end">
                            <h3 class="text-white text-xl font-bold">Web Development Fundamentals</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm text-gray-500">Instructor: John Smith</span>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">In Progress</span>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600">Progress</span>
                                    <span class="text-sm text-gray-600">65%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                            <a href="#" class="block text-center py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-200">
                                Continue Learning
                            </a>
                        </div>
                    </div>

                    <!-- Course Card 2 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-40 bg-gradient-to-r from-yellow-400 to-orange-500 p-6 flex items-end">
                            <h3 class="text-white text-xl font-bold">Data Science Essentials</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm text-gray-500">Instructor: Sarah Johnson</span>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">New</span>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600">Progress</span>
                                    <span class="text-sm text-gray-600">10%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: 10%"></div>
                                </div>
                            </div>
                            <a href="#" class="block text-center py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-200">
                                Start Learning
                            </a>
                        </div>
                    </div>

                    <!-- Course Card 3 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-40 bg-gradient-to-r from-pink-500 to-red-500 p-6 flex items-end">
                            <h3 class="text-white text-xl font-bold">Digital Marketing Strategies</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm text-gray-500">Instructor: Michael Brown</span>
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Coming Soon</span>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600">Available From</span>
                                    <span class="text-sm text-gray-600">June 15, 2025</span>
                                </div>
                            </div>
                            <button disabled class="block w-full text-center py-2 px-4 bg-gray-400 text-white rounded cursor-not-allowed">
                                Not Available Yet
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Assignments -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold">Recent Assignments</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                                        <th class="py-3 px-4 text-left">Assignment</th>
                                        <th class="py-3 px-4 text-left">Course</th>
                                        <th class="py-3 px-4 text-left">Due Date</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600">
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">HTML & CSS Project</td>
                                        <td class="py-3 px-4">Web Development Fundamentals</td>
                                        <td class="py-3 px-4">May 30, 2025</td>
                                        <td class="py-3 px-4">
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <a href="#" class="text-blue-500 hover:text-blue-700">Submit</a>
                                        </td>
                                    </tr>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">JavaScript Basics Quiz</td>
                                        <td class="py-3 px-4">Web Development Fundamentals</td>
                                        <td class="py-3 px-4">May 25, 2025</td>
                                        <td class="py-3 px-4">
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Completed</span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <a href="#" class="text-blue-500 hover:text-blue-700">View Results</a>
                                        </td>
                                    </tr>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">Python Data Analysis</td>
                                        <td class="py-3 px-4">Data Science Essentials</td>
                                        <td class="py-3 px-4">June 5, 2025</td>
                                        <td class="py-3 px-4">
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Not Started</span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <a href="#" class="text-blue-500 hover:text-blue-700">Start</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
