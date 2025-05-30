@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary py-6 px-8">
                <h1 class="text-2xl font-bold text-white">Registration Complete</h1>
                <p class="text-white text-opacity-80 mt-1">Thank you for registering!</p>
            </div>
            
            <div class="p-8">
                <div class="flex items-center justify-center mb-8">
                    <div class="rounded-full bg-green-100 p-3">
                        <svg class="h-16 w-16 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Registration Successful!</h2>
                        <p class="text-gray-600 mt-1">Your registration has been completed successfully. Please save your registration details for future reference.</p>
                    </div>
                </div>
                
                <!-- Registration Details -->
                <div class="bg-gray-50 border border-gray-200 rounded-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Registration Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Registration Number</p>
                            <p class="text-base font-medium text-gray-800">{{ session('registration.student')->registration_number }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="text-base font-medium text-gray-800">{{ session('registration.student')->first_name }} {{ session('registration.student')->last_name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-base font-medium text-gray-800">{{ session('registration.student')->email }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-base font-medium text-gray-800">{{ session('registration.student')->phone }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Program</p>
                            <p class="text-base font-medium text-gray-800">{{ session('registration.student')->programType->name }}</p>
                        </div>
                        
                        @if(session('registration.student')->school)
                        <div>
                            <p class="text-sm text-gray-500">School</p>
                            <p class="text-base font-medium text-gray-800">{{ session('registration.student')->school->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Next Steps Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">What's Next?</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>You will receive a confirmation email with your registration details.</li>
                                    <li>Please keep your registration number safe for future reference.</li>
                                    <li>Your program coordinator will contact you with further instructions.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
                @endif
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">What's Next?</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>We'll be in touch with you shortly regarding program details and schedule.</p>
                                <p class="mt-1">Please check your email and phone for communications from the Young Experts Group team.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center">
                    <a href="{{ url('/') }}" class="bg-primary hover:bg-primary-dark text-white font-medium py-3 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50">
                        Return to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
