<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>School Registration - Young Experts Group</title>
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
<body>
    <header class="bg-primary text-white shadow-md">
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
        <div class="max-w-4xl mx-auto">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">School Registration</h1>
                <p class="text-gray-600">Join the Young Experts Group network by registering your school below</p>
            </div>
            
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Registration Form Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary to-red-700 text-white">
                    <h2 class="font-semibold text-lg flex items-center">
                        <i class="fas fa-school mr-2"></i>
                        <span>School Information</span>
                    </h2>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('school.register.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- School Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">School Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Phone Number -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('phone') border-red-500 @enderror" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email (Optional) -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-gray-400">(Optional)</span></label>
                                <input type="email" name="email" id="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                                <input type="text" name="location" id="location" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('location') border-red-500 @enderror" value="{{ old('location') }}" required>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- GPS Coordinates (Optional) -->
                            <div>
                                <label for="gps_coordinates" class="block text-sm font-medium text-gray-700 mb-1">GPS Coordinates <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="gps_coordinates" id="gps_coordinates" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('gps_coordinates') border-red-500 @enderror" value="{{ old('gps_coordinates') }}" placeholder="e.g., 5.6037, -0.1870">
                                @error('gps_coordinates')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Owner's Name -->
                            <div>
                                <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">Owner's Name <span class="text-red-500">*</span></label>
                                <input type="text" name="owner_name" id="owner_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('owner_name') border-red-500 @enderror" value="{{ old('owner_name') }}" required>
                                @error('owner_name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Average Number of Students (Optional) -->
                            <div>
                                <label for="avg_students" class="block text-sm font-medium text-gray-700 mb-1">Average Number of Students <span class="text-gray-400">(Optional)</span></label>
                                <input type="number" name="avg_students" id="avg_students" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('avg_students') border-red-500 @enderror" value="{{ old('avg_students') }}">
                                @error('avg_students')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- School Logo (Optional) -->
                            <div class="md:col-span-2">
                                <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">School Logo <span class="text-gray-400">(Optional)</span></label>
                                <div class="mt-1 flex items-center">
                                    <div id="logo-preview" class="w-20 h-20 border border-gray-300 bg-gray-100 rounded-lg flex items-center justify-center mr-4 overflow-hidden">
                                        <i class="fas fa-school text-gray-400 text-3xl"></i>
                                    </div>
                                    <label for="logo" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                        <span>Upload a file</span>
                                        <input id="logo" name="logo" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    PNG, JPG, GIF up to 2MB
                                </p>
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <a href="{{ url('/') }}" class="mr-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                                Submit Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Information Notes -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-blue-800 font-semibold flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Important Information
                </h3>
                <ul class="mt-2 space-y-1 text-sm text-blue-700 list-disc list-inside">
                    <li>Your school registration will be reviewed by our team</li>
                    <li>You will be contacted via the provided phone number or email</li>
                    <li>For any inquiries, please contact us at <a href="mailto:support@yeg.com" class="underline">support@yeg.com</a></li>
                </ul>
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

    <script>
        function previewImage(input) {
            const preview = document.getElementById('logo-preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Remove any existing content
                    preview.innerHTML = '';
                    
                    // Create image element
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    
                    // Add image to preview
                    preview.appendChild(img);
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
