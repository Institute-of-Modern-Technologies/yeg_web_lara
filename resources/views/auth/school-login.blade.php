<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>School Portal Login - Young Experts Group</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-900 via-black to-gray-800 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-green-400 to-blue-500 bg-clip-text text-transparent mb-2">
                    YEG School Portal
                </h1>
                <p class="text-gray-400 text-lg">Welcome back! Please sign in to your school account.</p>
            </div>

            <!-- Login Form -->
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl p-8">
                <form method="POST" action="{{ route('school.login.attempt') }}" class="space-y-6">
                    @csrf

                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-user mr-2"></i>Username
                        </label>
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            required 
                            value="{{ old('username') }}"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                            placeholder="Enter your username"
                        >
                        @error('username')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                            placeholder="Enter your password"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-green-500 bg-gray-700 border-gray-600 rounded focus:ring-green-500 focus:ring-2"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-300">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In to School Portal
                        </button>
                    </div>
                </form>

                <!-- Additional Links -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-green-400 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Main Login
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} Young Experts Group. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
