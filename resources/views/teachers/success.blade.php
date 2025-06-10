<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration Successful - YEG</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e91e63',
                        'primary-dark': '#c2185b',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .animate-check {
            animation: check-animation 0.5s ease-in-out;
        }
        @keyframes check-animation {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-primary">
                        <span class="sr-only">YEG</span>
                        <span class="inline-block align-middle">YEG</span>
                    </a>
                </div>
                <div class="hidden md:flex md:items-center md:space-x-6">
                    <a href="/" class="text-gray-600 hover:text-gray-800 flex items-center space-x-1">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div class="flex md:hidden items-center">
                    <button id="mobile-menu-toggle" class="p-2 rounded-md text-gray-600 hover:text-gray-800 focus:outline-none">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-b border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-800 flex items-center space-x-1">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="flex flex-col items-center justify-center p-8 sm:p-12">
                <div class="rounded-full bg-green-100 p-6 mb-6">
                    <svg class="w-16 h-16 text-green-500 animate-check" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Registration Successful!</h2>
                <p class="text-lg text-center text-gray-600 mb-6">Thank you for applying to be a YEG Instructor.</p>
                <p class="text-center text-gray-600 mb-8">Your application has been received. Our team will review your details and contact you soon.</p>
                
                <div class="space-y-4 w-full max-w-md">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    If you selected "Will send CV later", please remember to send your CV to <span class="font-medium">imtghanabranch@gmail.com</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <a href="/" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fas fa-home mr-2"></i> Return to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
