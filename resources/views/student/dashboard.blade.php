<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard - Young Experts Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-green-600 text-white shadow-md">
            <div class="container mx-auto px-6 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <span class="text-white text-xl font-medium">Young</span>
                            <span class="text-yellow-300 mx-1 text-xl">Experts</span>
                            <span class="text-white text-xl font-medium">Group</span>
                        </a>
                        <span class="ml-4 text-sm bg-green-700 px-2 py-1 rounded">Student Portal</span>
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
            <aside class="w-64 bg-green-800 text-white p-4 hidden md:block">
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="block py-2 px-4 rounded bg-green-700">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-4 rounded hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-book mr-2"></i> My Courses
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-4 rounded hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-tasks mr-2"></i> Assignments
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-4 rounded hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-chart-line mr-2"></i> Progress
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-4 rounded hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-user-cog mr-2"></i> Profile
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
