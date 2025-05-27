<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Admin Dashboard - Young Experts Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-md">
            <div class="container mx-auto px-6 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <span class="text-white text-xl font-medium">Young</span>
                            <span class="text-yellow-300 mx-1 text-xl">Experts</span>
                            <span class="text-white text-xl font-medium">Group</span>
                        </a>
                        <span class="ml-4 text-sm bg-blue-700 px-2 py-1 rounded">School Admin</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm">Welcome, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm bg-white/20 hover:bg-white/30 px-3 py-1 rounded transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside class="w-64 bg-blue-800 text-white p-4 hidden md:block">
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="block py-2 px-4 rounded bg-blue-700">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-users mr-2"></i> Students
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-book mr-2"></i> Courses
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-calendar mr-2"></i> Schedule
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Content Area -->
            <main class="flex-1 p-6">
                <h1 class="text-3xl font-bold mb-6">School Admin Dashboard</h1>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-10 text-blue-500">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-gray-600 text-sm">Total Students</h2>
                                <p class="text-2xl font-bold">65</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-10 text-green-500">
                                <i class="fas fa-book text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-gray-600 text-sm">Active Courses</h2>
                                <p class="text-2xl font-bold">12</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-500 bg-opacity-10 text-purple-500">
                                <i class="fas fa-clipboard-check text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-gray-600 text-sm">Pending Enrollments</h2>
                                <p class="text-2xl font-bold">8</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Management Section -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold">Student Management</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between mb-4">
                            <div class="relative">
                                <input type="text" placeholder="Search students..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-1"></i> Add New Student
                            </a>
                        </div>

                        <!-- Students Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                                        <th class="py-3 px-4 text-left">Name</th>
                                        <th class="py-3 px-4 text-left">Email</th>
                                        <th class="py-3 px-4 text-left">Username</th>
                                        <th class="py-3 px-4 text-left">Enrollment Date</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600">
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">John Smith</td>
                                        <td class="py-3 px-4">john.smith@example.com</td>
                                        <td class="py-3 px-4">johnsmith</td>
                                        <td class="py-3 px-4">2025-04-10</td>
                                        <td class="py-3 px-4">
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <button class="text-blue-500 hover:text-blue-700 mx-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-500 hover:text-red-700 mx-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">Lisa Johnson</td>
                                        <td class="py-3 px-4">lisa.johnson@example.com</td>
                                        <td class="py-3 px-4">lisaj</td>
                                        <td class="py-3 px-4">2025-04-15</td>
                                        <td class="py-3 px-4">
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <button class="text-blue-500 hover:text-blue-700 mx-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-500 hover:text-red-700 mx-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">David Brown</td>
                                        <td class="py-3 px-4">david.brown@example.com</td>
                                        <td class="py-3 px-4">dbrown</td>
                                        <td class="py-3 px-4">2025-05-02</td>
                                        <td class="py-3 px-4">
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <button class="text-blue-500 hover:text-blue-700 mx-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-500 hover:text-red-700 mx-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
