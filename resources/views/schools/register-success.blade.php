<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="{{ asset('css/sticky-headers.css') }}" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registration Successful - Young Experts Group</title>
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
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="has-sticky-header">
    <header class="bg-primary text-white shadow-md sticky-header">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span class="text-white text-xl font-medium">Young</span>
                    <span class="text-secondary mx-1 text-xl font-medium">Experts</span>
                    <span class="text-white text-xl font-medium">Group</span>
                </a>
                <a href="{{ url('/') }}" class="text-white hover:text-gray-200">
                    <i class="fas fa-home mr-1"></i> Back to Home
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-12 text-center">
                    <div class="mb-6 inline-flex items-center justify-center h-20 w-20 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-5xl"></i>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">Registration Submitted Successfully!</h1>
                    
                    <p class="text-gray-600 mb-6">
                        Thank you for registering your school with Young Experts Group. Your application has been received and is pending review.
                    </p>
                    
                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                    @endif
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left mb-6">
                        <h3 class="text-blue-800 font-semibold flex items-center mb-2">
                            <i class="fas fa-info-circle mr-2"></i> What happens next?
                        </h3>
                        <ul class="space-y-1 text-sm text-blue-700 list-disc list-inside">
                            <li>Our team will review your application</li>
                            <li>You will be contacted via the provided contact information</li>
                            <li>Upon approval, your school will be added to our network</li>
                        </ul>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ url('/') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors flex items-center justify-center">
                            <i class="fas fa-home mr-2"></i> Return to Home
                        </a>
                        <a href="{{ route('school.register') }}" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i> Register Another School
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white mt-10">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Young Experts Group. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
