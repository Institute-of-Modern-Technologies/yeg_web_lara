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
            background-color: #f8fafc;
        }
        .success-container {
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .header-banner {
            background-color: #950713;
            background-image: linear-gradient(135deg, #950713 0%, #c50000 100%);
            position: relative;
        }
        .header-banner::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 20px;
            background: linear-gradient(to bottom right, transparent 49%, white 50%);
        }
        
        /* Modern Print Styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
            
            .success-container {
                border: none;
                box-shadow: none;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            
            .header-banner {
                background-image: none !important;
                background-color: #950713 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            .header-banner::after {
                display: none;
            }
            
            .no-print {
                display: none !important;
            }
            
            footer, header, nav, .action-buttons {
                display: none !important;
            }
            
            /* Clean margins and optimize space */
            .print-container {
                padding: 0;
                margin: 0;
            }
            
            .print-p-0 {
                padding: 0 !important;
            }
            
            /* Add a page break before specific sections if needed */
            .print-break-before {
                page-break-before: always;
            }
            
            /* Ensure text is black for better printing */
            p, h1, h2, h3, span, div {
                color: black !important;
            }
            
            /* Exceptions for brand color */
            .brand-color, .text-primary, .brand-text {
                color: #950713 !important;
            }
            
            /* Improve contrast for better printing */
            .bg-gray-50 {
                background-color: white !important;
                border: 1px solid #e5e7eb !important;
            }
            
            /* Add a watermark for official look */
            .print-watermark {
                display: block !important;
                position: fixed;
                bottom: 10px;
                right: 10px;
                opacity: 0.1;
                z-index: 1000;
                transform: rotate(-45deg);
                font-size: 60px;
                color: #950713 !important;
                font-weight: bold;
            }
            
            /* Add print-specific header with logo and date */
            .print-header {
                display: block !important;
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            
            /* Format registration number for better visibility */
            .registration-number {
                font-size: 1.2em !important;
                font-weight: bold !important;
            }
            
            /* Set page margins */
            @page {
                margin: 0.5cm;
            }
        }
        .success-icon {
            background-color: #950713;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(149, 7, 19, 0.3);
        }
        .detail-card {
            transition: all 0.2s ease;
        }
        .detail-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .animate-check {
            animation: check-animation 0.8s ease-in-out;
        }
        @keyframes check-animation {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .badge {
            border-radius: 9999px;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            font-weight: 600;
        }
        .badge-success {
            background-color: rgba(5, 150, 105, 0.1);
            color: rgb(5, 150, 105);
        }
        .badge-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: rgb(245, 158, 11);
        }
        .info-icon {
            height: 18px;
            width: 18px;
            margin-right: 6px;
            opacity: 0.8;
            flex-shrink: 0;
        }
        
        /* Modern button animations */
        @keyframes shine {
            from { transform: translateX(-100%) skewX(45deg); }
            to { transform: translateX(200%) skewX(45deg); }
        }
        
        .group:hover .animate-shine {
            animation: shine 1s ease-in-out;
        }
        
        /* Print button ripple effect */
        #printButton::after {
            content: '';
            position: absolute;
            border-radius: inherit;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: white;
            transform: scale(0);
            opacity: 0.3;
            transition: all 0.4s ease-out;
        }
        
        #printButton:active::after {
            transform: scale(2);
            opacity: 0;
            transition: 0s;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Watermark visible only when printing -->
    <div class="print-watermark hidden">OFFICIAL YEG RECORD</div>

    <div class="py-12 px-4 flex-grow pb-24 print-container">
        <div class="container mx-auto">
            <!-- Print-only header with logo and date -->
            <div class="hidden print-header">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <h2 class="text-2xl font-bold brand-text">Young Experts Group</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-sm">Printed on: <span id="print-date">{{ date('F j, Y') }}</span></p>
                        <p class="text-sm">Registration Record</p>
                    </div>
                </div>
            </div>
            
            <div class="max-w-4xl mx-auto bg-white success-container">
                <div class="header-banner py-12 px-8 relative">
                    <h1 class="text-3xl md:text-4xl font-bold text-white">Registration Successful</h1>
                    <p class="text-white text-opacity-90 mt-3 text-lg">Thank you for joining Young Experts Group!</p>
                </div>
                
                <div class="p-8 md:p-10 relative">
                    <div class="absolute top-0 right-8 transform -translate-y-1/2">
                        <div class="rounded-full success-icon p-4 flex justify-center items-center">
                            <svg class="h-10 w-10 animate-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    
                    @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div class="w-full">
                                <h2 class="text-2xl font-bold text-gray-800">Welcome to Young Experts Group!</h2>
                                <p class="text-gray-600 mt-2">Your registration has been successfully completed and is now under review.</p>
                                @if(session('registration.student'))
                                    <div class="mt-4 flex items-center bg-gray-50 rounded-lg p-3 border-l-4 border-primary">
                                        <span class="font-medium text-gray-700">Registration Number:</span>
                                        <span class="ml-2 px-3 py-1 bg-primary text-white font-semibold rounded registration-number">{{ session('registration.student')->registration_number }}</span>
                                    </div>
                                @elseif(session('registration.registration_number'))
                                    <div class="mt-4 flex justify-between items-center bg-gray-50 border border-gray-200 px-6 py-3 rounded-lg shadow-sm">
                                        <span class="text-gray-700">Registration Number:</span>
                                        <span class="text-xl font-bold text-primary bg-primary bg-opacity-5 px-4 py-2 rounded-md registration-number">{{ session('registration.registration_number') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                
                <!-- Registration Details -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Registration Details</h1>
                            <p class="text-gray-600">Thank you for registering. Below are your registration details.</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Submitted</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        <!-- Personal Information Card -->
                        <div class="border border-gray-100 rounded-lg shadow-sm hover:shadow transition-shadow duration-300 overflow-hidden bg-white">
                            <div class="border-l-4 border-primary px-3 py-2 bg-gray-50 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="ml-2 text-sm text-primary font-semibold uppercase tracking-wide">Personal Info</span>
                            </div>
                            <div class="p-3 grid grid-cols-1 gap-2">
                                <!-- Two column layout for personal info items -->
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 uppercase">Full Name</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ session('registration.student')->full_name }}</p>
                                    </div>
                                    
                                    <div class="p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 uppercase">Phone</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ session('registration.student')->phone ?: 'Not provided' }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 uppercase">Email</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ session('registration.student')->email ?: 'Not provided' }}</p>
                                    </div>
                                    
                                    <div class="p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 uppercase">Parent Contact</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ session('registration.student')->parent_contact ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 uppercase">Gender</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ isset(session('registration.student')->gender) ? session('registration.student')->gender : 'Not specified' }}</p>
                                    </div>
                                    
                                    <div class="p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 uppercase">Date of Birth</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ isset(session('registration.student')->date_of_birth) ? session('registration.student')->date_of_birth : 'Not specified' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Card -->
                        <div class="border border-gray-100 rounded-lg shadow-sm hover:shadow transition-shadow duration-300 overflow-hidden bg-white">
                            <div class="border-l-4 border-primary px-3 py-2 bg-gray-50 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span class="ml-2 text-sm text-primary font-semibold uppercase tracking-wide">Address</span>
                            </div>
                            <div class="p-3 grid grid-cols-1 gap-2">
                                <div class="p-2 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-500 uppercase">Address</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ isset(session('registration.student')->address) ? session('registration.student')->address : 'Not specified' }}</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 uppercase">City/Town</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ isset(session('registration.student')->city) ? session('registration.student')->city : 'Not specified' }}</p>
                                    </div>
                                    
                                    <div class="p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 uppercase">Region</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ 
                                            isset(session('registration.student')->region) ? session('registration.student')->region : 
                                            (isset(session('registration.student')->state) ? session('registration.student')->state : 'Not specified') 
                                        }}</p>
                                    </div>
                                </div>
                                
                                @if(isset(session('registration.student')->postal_code))
                                <div class="p-2 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-500 uppercase">Postal Code</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ session('registration.student')->postal_code }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Program Details Card -->
                        <div class="border border-gray-100 rounded-lg shadow-sm hover:shadow transition-shadow duration-300 overflow-hidden bg-white">
                            <div class="border-l-4 border-primary px-3 py-2 bg-gray-50 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="ml-2 text-sm text-primary font-semibold uppercase tracking-wide">Program</span>
                            </div>
                            <div class="p-3 grid grid-cols-1 gap-2">
                                <div class="p-2 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-500 uppercase">Program Type</p>
                                    <div class="flex items-center">
                                        <span class="text-sm font-bold text-gray-800 truncate">{{ session('registration.program_type_name') }}</span>
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded ml-2">Enrolled</span>
                                    </div>
                                </div>
                                
                                <div class="p-2 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-500 uppercase">Class</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ isset(session('registration.student')->class) ? session('registration.student')->class : 'Not specified' }}</p>
                                </div>
                                
                                @if(session('registration.school_id') && session('registration.school_name'))
                                <div class="p-2 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-500 uppercase">School</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ session('registration.school_name') }}</p>
                                </div>
                                @endif
                            
                                @if(session('registration.fee_amount'))
                                <div class="p-2 bg-gray-50 rounded">
                                    <p class="text-xs text-gray-500 uppercase">Fee Amount</p>
                                    <p class="text-sm font-bold text-gray-800">₵{{ session('registration.fee_amount') }}</p>
                                </div>
                                @endif
                                
                                <div class="p-2 bg-primary bg-opacity-5 rounded border border-primary border-opacity-20">
                                    <p class="text-xs text-primary uppercase font-medium">Registration Status</p>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-yellow-500 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Under Review</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notification Message -->
                <div class="bg-white border border-primary border-opacity-20 rounded-xl p-6 mb-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
                    <div class="flex">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-full p-2">
                            <svg class="h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-lg font-bold text-primary">
                                Next Steps
                            </p>
                            <p class="text-md text-gray-700 mt-2">
                                Your registration is currently <span class="font-semibold">under review</span> by our admissions team. Once approved, your login credentials will be automatically sent to your email. Please check your inbox regularly for updates.
                            </p>
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-yellow-500 mr-2 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">Important Note:</p>
                                        <p class="text-sm text-gray-600 mt-1">If you don't receive your credentials within 48 hours of approval, please contact our admin at <a href="mailto:imtghanabranch@gmail.com" class="text-primary hover:underline font-medium">imtghanabranch@gmail.com</a> or call <a href="tel:+233547147313" class="text-primary hover:underline font-medium">0547147313</a>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons - marked as no-print for clean printing -->
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 no-print">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center sm:justify-between mt-8 action-buttons">
                        <a href="{{ url('/') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-3 rounded-xl font-medium transition-all flex items-center justify-center shadow-sm border border-gray-200">
                            <svg class="h-5 w-5 mr-2 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Home
                        </a>
                        
                        <button onclick="window.print()" id="printButton" class="bg-primary text-white px-6 py-3 rounded-xl font-medium hover:bg-opacity-90 transition-all flex items-center justify-center shadow-md relative overflow-hidden group">
                            <div class="absolute inset-0 w-3 bg-white bg-opacity-20 skew-x-[45deg] group-hover:animate-shine"></div>
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print Registration Details
                        </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-primary text-white py-4 w-full shadow-lg fixed bottom-0 left-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <p class="font-medium">&copy; {{ date('Y') }} Young Experts Group. All rights reserved.</p>
            </div>
            <div class="flex items-center gap-6">
                <a href="#" class="text-white hover:text-white/80 transition-all transform hover:scale-110">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" class="text-white hover:text-white/80 transition-all transform hover:scale-110">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                    </svg>
                </a>
                <a href="#" class="text-white hover:text-white/80 transition-all transform hover:scale-110">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772a4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

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
