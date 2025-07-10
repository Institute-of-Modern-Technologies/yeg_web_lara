<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="{{ asset('css/sticky-headers.css') }}" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Registration - Step 2 - Young Experts Group</title>
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
        
        /* Animation classes */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c50000;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a30000;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
        }
    </style>
</head>
<body class="has-sticky-header">
    <header class="bg-white text-gray-800 shadow-md sticky-header">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span class="text-primary text-xl font-medium">Young</span>
                    <span class="text-secondary mx-1 text-xl font-medium">Experts</span>
                    <span class="text-primary text-xl font-medium">Group</span>
                </a>
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="text-primary hover:text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                <!-- Desktop navigation -->
                <nav class="hidden md:flex space-x-6 items-center">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('school.register') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-school mr-1"></i> School Registration
                    </a>
                    <a href="{{ url('/students/register') }}" class="text-primary border-b-2 border-secondary flex items-center">
                        <i class="fas fa-user-graduate mr-1"></i> Student Registration
                    </a>
                    <a href="{{ url('/about') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-info-circle mr-1"></i> About Us
                    </a>
                    <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-primary flex items-center">
                        <i class="fas fa-envelope mr-1"></i> Contact
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </nav>
            </div>
            <!-- Mobile navigation menu (hidden by default) -->
            <div id="mobile-menu" class="hidden md:hidden mt-3 pb-2 bg-white border-t border-gray-200">
                <div class="flex flex-col space-y-2">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-home mr-2 w-5 text-center"></i> Home
                    </a>
                    <a href="{{ route('school.register') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-school mr-2 w-5 text-center"></i> School Registration
                    </a>
                    <a href="{{ url('/students/register') }}" class="text-primary py-2 bg-gray-100 px-2 rounded flex items-center">
                        <i class="fas fa-user-graduate mr-2 w-5 text-center"></i> Student Registration
                    </a>
                    <a href="{{ url('/about') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-info-circle mr-2 w-5 text-center"></i> About Us
                    </a>
                    <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-primary py-2 flex items-center">
                        <i class="fas fa-envelope mr-2 w-5 text-center"></i> Contact
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center mt-2">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <main class="bg-gray-50 min-h-screen py-8">
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary py-6 px-8">
                <h1 class="text-2xl font-bold text-white">Student Registration</h1>
                <p class="text-white text-opacity-80 mt-1">Step 2: School Information</p>
            </div>
            
            <div class="p-8">
                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                

                
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <span class="font-semibold">2</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Are you from a partner school?</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Students from partner schools receive a ₵100 discount on program fees. If your school isn't listed, select "Not Yet".</p>
                </div>
                
                <!-- SIMPLIFIED APPROACH: TWO SEPARATE FORMS -->
                <!-- Form for "Not Yet" option -->
                <form action="/students/register/process-step2-other" method="POST" class="mb-8 bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    @csrf
                    <input type="hidden" name="school_selection" value="not_yet">

                    
                    <div class="flex items-center mb-4">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Not from a partner school</h3>
                            <p class="text-gray-600">Continue with regular registration process</p>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Continue with Regular Registration
                    </button>
                </form>
                
                <!-- Form for "Select Partner School" option -->
                <form action="/students/register/process-step2-other" method="POST" class="mb-8 bg-white border border-primary rounded-lg p-6 shadow-sm" id="school-selection-form">
                    @csrf
                    <input type="hidden" name="school_selection" value="select_school">

                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="border p-4 rounded-md bg-yellow-50 border-yellow-200 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Program Fee Information</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>
                                        <strong>{{ $programType->name }} Program</strong>:<br>
                                        Regular fee<br>
                                        Partner school fee
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center mb-6">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">I'm from a partner school</h3>
                            <p class="text-gray-600">Select your school to receive a discount</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">Select Your School</label>
                        <select id="school_id" name="school_id" class="block w-full px-4 py-3 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md shadow-sm" required>
                            <option value="">-- Select Your School --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Continue with Partner School
                    </button>
                </form>
                
                <!-- Back Button (outside of forms) -->
                <div class="mt-8 flex justify-start">
                    <a href="/students/register" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                        Back to Program Selection
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

    </main>

    <footer class="bg-gray-800 text-white mt-10">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p> 2023 Young Experts Group. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Toggle school selection type
            const radioButtons = document.querySelectorAll('input[name="school_selection"]');
            
            if (radioButtons) {
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', function() {
                        const selectedOption = document.querySelector('input[name="school_selection"]:checked').value;
                        
                        // Hide all school selection forms first
                        document.querySelectorAll('.school-selection-option').forEach(div => {
                            div.classList.add('hidden');
                        });
                        
                        // Show the selected form
                        document.getElementById(selectedOption + '-form').classList.remove('hidden');
                    });
                });
            }
        });
    </script>
</body>
</html>
