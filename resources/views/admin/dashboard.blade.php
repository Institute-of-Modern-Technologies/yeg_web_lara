<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Young Experts Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e11d48',
                        secondary: '#f59e0b'
                    }
                }
            }
        }
    </script>
    <style>
        /* Basic resets */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            overflow-x: hidden;
            background-color: #f3f4f6;
        }
        
        /* Header styling */
        .app-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 50; /* Higher z-index */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        
        /* Sidebar styling */
        #sidebar {
            position: fixed;
            left: 0;
            top: 60px; /* Start below header */
            bottom: 0;
            height: calc(100% - 60px); /* Full height minus header */
            width: 256px; /* 16rem */
            background-color: #1f2937;
            z-index: 40; /* Lower than header */
            overflow-y: auto;
        }
        
        /* Content area */
        .content-area {
            width: 100%;
            padding-top: 60px; /* Exact header height */
            min-height: 100vh;
            flex: 1;
        }
        
        @media (min-width: 768px) {
            .content-area {
                padding-left: 256px; /* Exact sidebar width */
                width: calc(100% - 0px); /* Full width minus 0 (to take up all remaining space) */
            }
        }
        
        .active-menu-item {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="app-header bg-primary text-white shadow-md">
            <div class="px-4 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button id="mobile-menu-button" class="md:hidden mr-4 text-white focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <a href="{{ url('/') }}" class="flex items-center">
                            <span class="text-white text-xl font-medium">Young</span>
                            <span class="text-secondary mx-1 text-xl font-medium">Experts</span>
                            <span class="text-white text-xl font-medium">Group</span>
                        </a>
                        <span class="ml-4 text-sm bg-secondary px-2 py-1 rounded">Super Admin</span>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="text-white focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 bg-secondary text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        
                        <!-- User dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-medium">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block text-sm">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="transform -translate-x-full md:translate-x-0 transition-transform duration-300" id="sidebar">
            <div class="h-full flex flex-col pt-0"> <!-- No top padding needed since sidebar starts below header -->
                <!-- Navigation -->
                <div class="px-4 py-6">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</span>
                    
                    <nav class="mt-4 space-y-1">
                        <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-white active-menu-item">
                            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <i class="fas fa-users w-5 h-5 mr-3"></i>
                            <span>User Management</span>
                        </a>
                        
                        <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <i class="fas fa-school w-5 h-5 mr-3"></i>
                            <span>Schools</span>
                        </a>
                        
                        <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <i class="fas fa-book w-5 h-5 mr-3"></i>
                            <span>Courses</span>
                        </a>
                    </nav>
                </div>
                
                <!-- Super Admin Only Section -->
                @if(Auth::user()->user_type_id == 1)
                <div class="px-4 py-6 border-t border-gray-700">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Super Admin</span>
                    
                    <nav class="mt-4 space-y-1">
                        <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white" id="add-user-btn">
                            <i class="fas fa-user-plus w-5 h-5 mr-3"></i>
                            <span>Create New User</span>
                        </a>
                        
                        <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <i class="fas fa-university w-5 h-5 mr-3"></i>
                            <span>Add School</span>
                        </a>
                        
                        <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <i class="fas fa-shield-alt w-5 h-5 mr-3"></i>
                            <span>System Settings</span>
                        </a>
                    </nav>
                </div>
                @endif
                
                <!-- Bottom section for help and support -->
                <div class="mt-auto px-4 py-6 border-t border-gray-700">
                    <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-question-circle w-5 h-5 mr-3"></i>
                        <span>Help & Support</span>
                    </a>
                    
                    <a href="#" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area - FULL WIDTH -->
        <main class="content-area">
            <div class="p-6">
                <!-- Dashboard Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
                    <div class="flex flex-wrap gap-2 mt-3 md:mt-0">
                        <button class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 flex items-center hover:bg-gray-300">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>{{ date('F Y') }}</span>
                        </button>
                        <button class="px-4 py-2 bg-primary text-white rounded-lg flex items-center hover:bg-red-700">
                            <i class="fas fa-file-export mr-2"></i>
                            <span>Export Data</span>
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-primary hover:shadow-lg transition-shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm font-medium uppercase">Total Users</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-1">127</h3>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <i class="fas fa-users text-primary text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center mt-4">
                            <span class="text-green-500 flex items-center text-sm">
                                <i class="fas fa-arrow-up mr-1"></i> 12%
                            </span>
                            <span class="text-gray-400 text-sm ml-2">Since last month</span>
                        </div>
                    </div>
                    
                    <!-- More stat cards here... -->
                </div>

                <!-- User Management Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-800">User Management</h2>
                        @if(Auth::user()->user_type_id == 1)
                        <button class="bg-primary text-white px-4 py-2 rounded-lg flex items-center hover:bg-red-700 transition-colors duration-200 mt-2 md:mt-0" id="add-user-btn">
                            <i class="fas fa-plus mr-2"></i> Add New User
                        </button>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between mb-4 space-y-3 md:space-y-0">
                            <div class="relative w-full md:w-64">
                                <input type="text" placeholder="Search users..." class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <div class="flex space-x-2">
                                <select class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">All User Types</option>
                                    <option value="1">Super Admin</option>
                                    <option value="2">School Admin</option>
                                    <option value="3">Student</option>
                                </select>
                                <button class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                                        <th class="py-3 px-4 text-left font-semibold">Name</th>
                                        <th class="py-3 px-4 text-left font-semibold">Username</th>
                                        <th class="py-3 px-4 text-left font-semibold">Email</th>
                                        <th class="py-3 px-4 text-left font-semibold">User Type</th>
                                        <th class="py-3 px-4 text-center font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm">
                                    <!-- User rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- User Management Modals -->
    <div id="createUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <!-- Create user form -->
    </div>

    <!-- Include Alpine.js -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const sidebar = document.getElementById('sidebar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }
            
            // Add User button functionality
            const addUserBtn = document.getElementById('add-user-btn');
            
            if (addUserBtn) {
                addUserBtn.addEventListener('click', function() {
                    const modal = document.getElementById('createUserModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            }
        });
    </script>
</body>
</html>