@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Success Header with brand color -->
            <div class="bg-[#950713] py-6 px-6">
                <div class="flex items-center justify-center">
                    <div class="rounded-full bg-white/20 p-3">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="mt-4 text-center text-3xl font-extrabold text-white">
                    Registration Successful
                </h2>
                <p class="mt-2 text-center text-lg text-white/90">
                    Thank you for applying to become a trainer!
                </p>
            </div>

            <!-- Success Content -->
            <div class="py-8 px-6">
                <div class="text-center">
                    <p class="text-gray-700 text-lg mb-6">
                        Your application has been received and is currently under review by our team. 
                        We'll get back to you as soon as possible regarding the next steps.
                    </p>
                    

                    
                    <div class="mt-8 space-y-4">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-blue-800 font-medium">What's Next?</h4>
                            <ul class="mt-2 text-sm text-blue-700 text-left list-disc pl-5">
                                <li>Our team will review your application within 3-5 business days</li>
                                <li>You'll receive an email notification once your application is reviewed</li>
                                <li>If approved, you'll be invited for an interview or teaching demonstration</li>
                                <li>Please check your email regularly for updates</li>
                            </ul>
                        </div>
                        
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-gray-800 font-medium">Have Questions?</h4>
                            <p class="mt-2 text-sm text-gray-600">
                                If you have any questions or need to update your application, please contact us at
                                <a href="mailto:trainers@yegacademy.com" class="text-[#950713] font-medium hover:underline">
                                    trainers@yegacademy.com
                                </a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#950713] hover:bg-[#7a0510] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#950713]">
                            Return to Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
