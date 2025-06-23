<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - Young Experts Group</title>
    <link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" as="style">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#950713',
                        secondary: '#ffcb05',
                    },
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #f5f5f5;
        }
        .success-container {
            border-top: 5px solid #950713;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .header-banner {
            background-color: #950713;
            background-image: linear-gradient(135deg, #950713 0%, #c50000 100%);
        }
        .success-icon {
            color: #950713;
            border: 2px solid #950713;
        }
        .notification-box {
            border-left: 4px solid #950713;
        }
        .animate-check {
            animation: check-animation 0.8s ease-in-out;
        }
        @keyframes check-animation {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Main Content -->
    <div class="pt-12 pb-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto bg-white rounded-lg overflow-hidden success-container">
                <div class="header-banner py-8 px-8">
                    <h1 class="text-3xl font-bold text-white">Registration Complete</h1>
                    <p class="text-white text-opacity-90 mt-2">Thank you for registering with Young Experts Group!</p>
                </div>
            
            <div class="p-8">
                <div class="flex items-center mb-8">
                    <div class="rounded-full bg-white success-icon p-4 flex justify-center items-center mr-4">
                        <svg class="h-16 w-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Registration Successful!</h2>
                        <p class="text-gray-600 mt-2 text-lg">Your registration has been completed successfully. Please save your registration details for future reference.</p>
                        @if(session('registration.student'))
                            <p class="primary-text font-semibold mt-3">Your Registration Number: <span class="text-xl font-bold">{{ session('registration.student')->registration_number }}</span></p>
                        @elseif(session('registration.registration_number'))
                            <p class="primary-text font-semibold mt-3">Your Registration Number: <span class="text-xl font-bold">{{ session('registration.registration_number') }}</span></p>
                        @endif
                    </div>
                </div>
                
                <!-- Registration Details -->
                <div class="bg-gray-50 border border-gray-200 rounded-md p-6 mb-6 shadow-sm">
                    <h3 class="text-xl font-bold primary-text border-b border-gray-200 pb-3 mb-4">Registration Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-3 bg-white rounded-md shadow-sm hover:shadow-md transition-all">
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Registration Number</p>
                            <p class="text-base font-bold text-gray-800">{{ session('registration.student')->registration_number }}</p>
                        </div>
                        
                        <div class="p-3 bg-white rounded-md shadow-sm hover:shadow-md transition-all">
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Full Name</p>
                            <p class="text-base font-bold text-gray-800">{{ session('registration.student')->full_name }}</p>
                        </div>
                        
                        <div class="p-3 bg-white rounded-md shadow-sm hover:shadow-md transition-all">
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Email</p>
                            <p class="text-base font-bold text-gray-800">{{ session('registration.student')->email ?: 'Not provided' }}</p>
                        </div>
                        
                        <div class="p-3 bg-white rounded-md shadow-sm hover:shadow-md transition-all">
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Phone</p>
                            <p class="text-base font-bold text-gray-800">{{ session('registration.student')->phone ?: 'Not provided' }}</p>
                        </div>
                        
                        <div class="p-3 bg-white rounded-md shadow-sm hover:shadow-md transition-all">
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Program</p>
                            <p class="text-base font-bold text-gray-800">{{ session('registration.program_type_name') ?? 'Your Selected Program' }}</p>
                        </div>
                        
                        @if(session('registration.school_name'))
                        <div class="p-3 bg-white rounded-md shadow-sm hover:shadow-md transition-all">
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">School</p>
                            <p class="text-base font-bold text-gray-800">{{ session('registration.school_name') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Notification Message -->
                <div class="notification-box bg-gray-50 border-l-4 p-6 mb-6 shadow-sm rounded-r-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 primary-text" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-lg font-bold primary-text">
                                Registration Under Review
                            </p>
                            <p class="text-md text-gray-700 mt-2">
                                Your registration is currently under review by our admissions team. You will be <span class="font-semibold">notified via email or phone</span> once it has been approved. Please stay tuned for updates on your application status.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                    <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #950713;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Back to Home
                    </a>
                    
                    <button onclick="window.print()" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Registration Details
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation class to check icon after page loads
        setTimeout(function() {
            const checkIcon = document.querySelector('.success-icon svg');
            if (checkIcon) {
                checkIcon.classList.add('animate-check');
            }
        }, 300);
    });
</script>

</body>
</html>
