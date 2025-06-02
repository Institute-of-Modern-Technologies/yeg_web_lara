<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Young Experts Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                            <i class="fas fa-users w-5 h-5 mr-3"></i>
                            <span>Users</span>
                        </a>
                        
                        <!-- Advertisement Section -->
                        <div class="mt-6 pt-6 border-t border-gray-700">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Advertisement</span>
                            
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('admin.hero-sections.index') }}" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                    <i class="fas fa-image w-5 h-5 mr-3"></i>
                                    <span>Hero Sections</span>
                                </a>
                                
                                <a href="{{ route('admin.events.index') }}" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                    <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                                    <span>Events</span>
                                </a>
                                
                                <a href="{{ route('admin.happenings.index') }}" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                    <i class="fas fa-newspaper w-5 h-5 mr-3"></i>
                                    <span>Happenings</span>
                                </a>
                                
                                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                    <i class="fas fa-quote-left w-5 h-5 mr-3"></i>
                                    <span>Testimonials</span>
                                </a>
                                
                                <a href="{{ route('admin.partner-schools.index') }}" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                    <i class="fas fa-school w-5 h-5 mr-3"></i>
                                    <span>Partner Schools</span>
                                </a>
                                
                                <a href="{{ route('admin.students.index') }}" class="flex items-center py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                    <i class="fas fa-user-graduate w-5 h-5 mr-3"></i>
                                    <span>Students</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Setups Section -->
                        <div class="mt-6 pt-6 border-t border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="w-full flex items-center justify-between py-2.5 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                    <div class="flex items-center">
                                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                                        <span>Setups</span>
                                    </div>
                                    <i class="fas" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                </button>
                                
                                <div x-show="open" class="mt-2 space-y-1 pl-12">
                                    <a href="{{ route('admin.program-types.index') }}" class="flex items-center py-2 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                        <i class="fas fa-list-alt w-5 h-5 mr-3"></i>
                                        <span>Program Types</span>
                                    </a>
                                    <a href="{{ route('admin.schools.index') }}" class="flex items-center py-2 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                        <i class="fas fa-school w-5 h-5 mr-3"></i>
                                        <span>Schools</span>
                                    </a>
                                    <a href="{{ route('admin.fees.index') }}" class="flex items-center py-2 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                        <i class="fas fa-money-bill-wave w-5 h-5 mr-3"></i>
                                        <span>Program Fees</span>
                                    </a>
                                    <a href="{{ route('admin.students.index') }}" class="flex items-center py-2 px-4 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white">
                                        <i class="fas fa-user-graduate w-5 h-5 mr-3"></i>
                                        <span>Students</span>
                                    </a>
                                    <!-- More setup items can be added here -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Other menu items will be added when they're implemented -->
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
                        <!-- Other admin features will be added as implemented -->
                    </nav>
                </div>
                @endif
                
                <!-- Bottom section - placeholder for future content -->
                <div class="mt-auto px-4 py-6 border-t border-gray-700">
                    <!-- Support links will be added when implemented -->
                </div>
            </div>
        </aside>

        <!-- Main Content Area - FULL WIDTH -->
        <main class="content-area">
            <div class="p-6">
                @if(Route::currentRouteName() == 'admin.dashboard')
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
                                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ \App\Models\User::count() }}</h3>
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
                        <button class="bg-primary text-white px-4 py-2 rounded-lg flex items-center hover:bg-red-700 transition-colors duration-200 mt-2 md:mt-0" id="add-user-btn" onclick="openUserModal();">
                            <i class="fas fa-plus mr-2"></i> Add New User
                        </button>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between mb-4 space-y-3 md:space-y-0">
                            <div class="relative w-full md:w-64">
                                <input type="text" id="userSearchInput" placeholder="Search users..." class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <div class="flex space-x-2">
                                <select id="userTypeFilter" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">All User Types</option>
                                    <option value="1">Super Admin</option>
                                    <option value="2">School Admin</option>
                                    <option value="3">Student</option>
                                </select>
                                <button id="applyFilterBtn" class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="overflow-x-auto">
                            @php
                                $paginatedUsers = \App\Models\User::paginate(5);
                            @endphp
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
                                    @foreach($paginatedUsers as $user)
                                    <tr data-user-type="{{ $user->user_type_id }}">
                                        <td class="py-3 px-4">{{ $user->name }}</td>
                                        <td class="py-3 px-4">{{ $user->username }}</td>
                                        <td class="py-3 px-4">{{ $user->email }}</td>
                                        <td class="py-3 px-4">
                                            @php
                                                $badgeClass = 'bg-gray-100 text-gray-800';
                                                if($user->user_type_id == 1) {
                                                    $badgeClass = 'bg-purple-100 text-purple-800';
                                                } elseif($user->user_type_id == 2) {
                                                    $badgeClass = 'bg-blue-100 text-blue-800';
                                                } elseif($user->user_type_id == 3) {
                                                    $badgeClass = 'bg-green-100 text-green-800';
                                                }
                                            @endphp
                                            <span class="{{ $badgeClass }} px-2 py-1 rounded-full text-xs font-semibold">
                                                {{ $user->userType ? $user->userType->name : 'Unknown' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button class="text-blue-500 hover:text-blue-700" onclick="editUser({{ $user->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if(Auth::id() != $user->id)
                                                <button class="text-red-500 hover:text-red-700" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Pagination Links with Tailwind Styling -->
                            <div class="mt-5 flex justify-center">
                                {{ $paginatedUsers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    @yield('content')
                @endif
            </div>
        </main>
    </div>

    <!-- User Management Modals -->
    <!-- Create User Modal -->
    <div id="createUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 overflow-hidden animate-fadeIn transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary to-red-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-user-plus mr-3"></i>
                    <span>Create New User</span>
                </h3>
                <button type="button" class="text-white hover:text-gray-200 focus:outline-none" onclick="closeUserModal()">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="createUserForm">
                    @csrf
                    <div class="space-y-4">
                        <!-- Row 1: Name and Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="name" name="name" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter full name" required>
                                </div>
                            </div>
                            
                            <!-- Email Address -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="email" name="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter email address" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 2: Username and User Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Username -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="username">
                                    Username
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="text" id="username" name="username" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter username" required>
                                </div>
                            </div>
                            
                            <!-- User Type -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="user_type_id">
                                    User Type
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select id="user_type_id" name="user_type_id" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary appearance-none" required>
                                        <option value="" disabled selected>Select user type</option>
                                        <option value="3">Student</option>
                                        <option value="2">School Admin</option>
                                        <option value="1">Super Admin</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 3: Password and Confirm Password -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                                    Password
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password" name="password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter password" required>
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('password', 'passwordToggle')">
                                        <i id="passwordToggle" class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="password_confirmation">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Confirm password" required>
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('password_confirmation', 'confirmPasswordToggle')">
                                        <i id="confirmPasswordToggle" class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center" onclick="closeUserModal()">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 overflow-hidden animate-fadeIn transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary to-red-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-user-edit mr-3"></i>
                    <span>Edit User</span>
                </h3>
                <button type="button" class="text-white hover:text-gray-200 focus:outline-none" onclick="closeEditUserModal()">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="space-y-4">
                        <!-- Row 1: Name and Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_name">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="edit_name" name="name" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter full name" required>
                                </div>
                            </div>
                            
                            <!-- Email Address -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_email">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="edit_email" name="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter email address" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 2: Username and User Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Username -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_username">
                                    Username
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="text" id="edit_username" name="username" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter username" required>
                                </div>
                            </div>
                            
                            <!-- User Type -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_user_type_id">
                                    User Type
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select id="edit_user_type_id" name="user_type_id" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary appearance-none" required>
                                        <option value="" disabled>Select user type</option>
                                        <option value="3">Student</option>
                                        <option value="2">School Admin</option>
                                        <option value="1">Super Admin</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 3: Password (Optional) -->
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    <span class="text-sm text-gray-700 font-medium">Password Update (Optional)</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-3">Leave blank if you don't want to change the password</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Password -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_password">
                                            New Password
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" id="edit_password" name="password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter new password">
                                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('edit_password', 'editPasswordToggle')">
                                                <i id="editPasswordToggle" class="fas fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Confirm Password -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_password_confirmation">
                                            Confirm New Password
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" id="edit_password_confirmation" name="password_confirmation" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Confirm new password">
                                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('edit_password_confirmation', 'editConfirmPasswordToggle')">
                                                <i id="editConfirmPasswordToggle" class="fas fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center" onclick="closeEditUserModal()">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Alpine.js -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fadeOut {
            animation: fadeOut 0.2s ease-in-out;
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }
    </style>
    
    <script>
        // Function to open the user modal - accessible directly from HTML
        function openUserModal() {
            console.log('openUserModal called directly');
            const modal = document.getElementById('createUserModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            } else {
                console.error('Modal element not found!');
            }
            return false;
        }
        
        // Functions to open/close the user modals - accessible directly from HTML
        function closeUserModal() {
            console.log('closeUserModal called');
            const modal = document.getElementById('createUserModal');
            const modalContent = modal.querySelector('div'); // Get the modal content container
            
            // Add fadeOut animation
            if (modalContent) {
                modalContent.classList.add('animate-fadeOut');
                
                // Wait for animation to finish before hiding modal
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    modalContent.classList.remove('animate-fadeOut');
                    document.body.style.overflow = ''; // Restore scrolling
                }, 200);
            } else {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scrolling
            }
            return false;
        }
    
        // Immediately execute scripts after page load
        window.addEventListener('load', function() {
            console.log('Page fully loaded');
            
            // Mobile menu toggle
            const sidebar = document.getElementById('sidebar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }
            
            // Debug the modals on the page
            console.log('Modal element:', document.getElementById('createUserModal'));
            
            // Add User button functionality as backup
            const addUserBtn = document.getElementById('add-user-btn');
            console.log('Add User button found:', addUserBtn);
            
            if (addUserBtn) {
                addUserBtn.addEventListener('click', function(e) {
                    console.log('Add User click event fired');
                    e.preventDefault();
                    openUserModal();
                });
            }
            
            // Function to filter users by search term and type
            function filterUsers() {
                const searchTerm = userSearchInput ? userSearchInput.value.toLowerCase() : '';
                const filterType = userTypeFilter ? userTypeFilter.value : '';
                const userTable = document.querySelector('table tbody');
                const userRows = userTable.querySelectorAll('tr');
                
                userRows.forEach(row => {
                    const userName = row.cells[0].textContent.toLowerCase();
                    const userUsername = row.cells[1].textContent.toLowerCase();
                    const userEmail = row.cells[2].textContent.toLowerCase();
                    const userType = row.cells[3].textContent.toLowerCase();
                    const userTypeId = row.getAttribute('data-user-type');
                    
                    // Match search term
                    const matchesSearch = searchTerm === '' || 
                        userName.includes(searchTerm) || 
                        userUsername.includes(searchTerm) || 
                        userEmail.includes(searchTerm) || 
                        userType.includes(searchTerm);
                    
                    // Match filter type
                    const matchesFilter = filterType === '' || userTypeId === filterType;
                    
                    // Show/hide row
                    if (matchesSearch && matchesFilter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // User search functionality
            const userSearchInput = document.getElementById('userSearchInput');
            if (userSearchInput) {
                userSearchInput.addEventListener('keyup', filterUsers);
            }
            
            // User type filter functionality
            const userTypeFilter = document.getElementById('userTypeFilter');
            const applyFilterBtn = document.getElementById('applyFilterBtn');
            
            if (userTypeFilter && applyFilterBtn) {
                applyFilterBtn.addEventListener('click', filterUsers);
                userTypeFilter.addEventListener('change', filterUsers);
            }
            
            // Add click event listeners to close modals when clicking outside
            document.querySelectorAll('.fixed.inset-0.bg-black.bg-opacity-50').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        if (modal.id === 'editUserModal') {
                            closeEditUserModal();
                        } else {
                            closeUserModal();
                        }
                    }
                });
            });
            
            // Close modals with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const visibleModal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50.flex');
                    if (visibleModal) {
                        if (visibleModal.id === 'editUserModal') {
                            closeEditUserModal();
                        } else {
                            closeUserModal();
                        }
                    }
                }
            });
            
            // Create User Form submission handling with AJAX
            const createUserForm = document.getElementById('createUserForm');
            if (createUserForm) {
                createUserForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
                    
                    // Clear previous error messages
                    document.querySelectorAll('.error-message').forEach(el => el.remove());
                    document.querySelectorAll('.border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.remove('focus:ring-red-500');
                        el.classList.remove('focus:border-red-500');
                    });
                    
                    // Get form data
                    const formData = new FormData(this);
                    
                    // Send AJAX request
                    fetch('{{ route("users.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(Object.fromEntries(formData))
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#FF0000',
                            }).then(() => {
                                // Reset form and close modal
                                createUserForm.reset();
                                closeUserModal();
                                
                                // Reload page to refresh user list
                                window.location.reload();
                            });
                        } else {
                            // Show validation errors
                            const errors = data.errors;
                            if (errors) {
                                Object.keys(errors).forEach(field => {
                                    const input = document.getElementById(field);
                                    if (input) {
                                        // Highlight input
                                        input.classList.add('border-red-500');
                                        input.classList.add('focus:ring-red-500');
                                        input.classList.add('focus:border-red-500');
                                        
                                        // Add error message
                                        const errorMsg = document.createElement('div');
                                        errorMsg.className = 'text-red-500 text-xs mt-1 error-message';
                                        errorMsg.textContent = errors[field][0];
                                        input.parentNode.appendChild(errorMsg);
                                    }
                                });
                            }
                            
                            // Show error toast
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            Toast.fire({
                                icon: 'error',
                                title: 'There were errors in your submission'
                            });
                        }
                    })
                    .catch(error => {
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem creating the user. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#FF0000'
                        });
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
                });
            }
            
            // Edit User Form submission handling with AJAX
            const editUserForm = document.getElementById('editUserForm');
            if (editUserForm) {
                editUserForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
                    
                    // Clear previous error messages
                    document.querySelectorAll('#editUserForm .error-message').forEach(el => el.remove());
                    document.querySelectorAll('#editUserForm .border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.remove('focus:ring-red-500');
                        el.classList.remove('focus:border-red-500');
                    });
                    
                    // Get form data
                    const formData = new FormData(this);
                    const userId = document.getElementById('edit_user_id').value;
                    
                    // Send AJAX request
                    fetch(`/admin/users/${userId}`, {
                        method: 'POST', // Laravel accepts POST for PUT with _method
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            Swal.fire({
                                title: 'Success!',
                                text: data.message || 'User updated successfully',
                                icon: 'success',
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                // Close modal
                                closeEditUserModal();
                                
                                // Reload page to refresh user list
                                window.location.reload();
                            });
                        } else {
                            // Show validation errors
                            const errors = data.errors;
                            if (errors) {
                                Object.keys(errors).forEach(field => {
                                    // Handle field name adjustments
                                    let formField = field;
                                    if (field.startsWith('edit_')) {
                                        formField = field;
                                    } else {
                                        formField = 'edit_' + field;
                                    }
                                    
                                    const input = document.getElementById(formField);
                                    if (input) {
                                        // Highlight input
                                        input.classList.add('border-red-500');
                                        input.classList.add('focus:ring-red-500');
                                        input.classList.add('focus:border-red-500');
                                        
                                        // Add error message
                                        const errorMsg = document.createElement('div');
                                        errorMsg.className = 'text-red-500 text-xs mt-1 error-message';
                                        errorMsg.textContent = errors[field][0];
                                        input.parentNode.appendChild(errorMsg);
                                    }
                                });
                            }
                            
                            // Show error toast
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            Toast.fire({
                                icon: 'error',
                                title: data.message || 'There were errors in your submission'
                            });
                        }
                    })
                    .catch(error => {
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem updating the user. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
                });
            }
        });
        
        // These general modal functions are kept for reference but not used directly
        // We're using the specific openUserModal and closeUserModal functions instead
        
        // Password visibility toggle
        function togglePasswordVisibility(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
        
        // Open the edit user modal and fetch user data
        function openEditUserModal() {
            const modal = document.getElementById('editUserModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            } else {
                console.error('Edit modal element not found!');
            }
            return false;
        }
        
        // Close the edit user modal
        function closeEditUserModal() {
            const modal = document.getElementById('editUserModal');
            const modalContent = modal.querySelector('div'); // Get the modal content container
            
            // Add fadeOut animation
            if (modalContent) {
                modalContent.classList.add('animate-fadeOut');
                
                // Wait for animation to finish before hiding modal
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    modalContent.classList.remove('animate-fadeOut');
                    document.body.style.overflow = ''; // Restore scrolling
                }, 200);
            } else {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scrolling
            }
            
            // Reset form
            document.getElementById('editUserForm').reset();
            
            // Clear validation errors
            document.querySelectorAll('#editUserForm .error-message').forEach(el => el.remove());
            document.querySelectorAll('#editUserForm .border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.remove('focus:ring-red-500');
                el.classList.remove('focus:border-red-500');
            });
            
            return false;
        }
        
        // Edit user function - fetch user data and show modal
        function editUser(userId) {
            // Show loading indicator
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching user data',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Fetch user data from the server
            fetch(`/admin/users/${userId}/edit`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Close loading indicator
                Swal.close();
                
                if (data.success) {
                    // Populate form with user data
                    const user = data.user;
                    document.getElementById('edit_user_id').value = user.id;
                    document.getElementById('edit_name').value = user.name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_username').value = user.username;
                    document.getElementById('edit_user_type_id').value = user.user_type_id;
                    
                    // Open modal
                    openEditUserModal();
                } else {
                    // Show error message
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Could not retrieve user data',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                Swal.fire({
                    title: 'Error!',
                    text: 'There was a problem fetching user data. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            });
        }
        
        // Delete user function
        function deleteUser(userId, userName) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete ${userName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete user
                    fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                data.message,
                                'success'
                            ).then(() => {
                                // Reload page to refresh user list
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'There was a problem deleting the user. Please try again.',
                            'error'
                        );
                        console.error('Error:', error);
                    });
                }
            });
        }
    </script>
</body>
</html>