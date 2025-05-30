@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary py-6 px-8">
                <h1 class="text-2xl font-bold text-white">Student Registration</h1>
                <p class="text-white text-opacity-80 mt-1">Step 3: Payment Information</p>
            </div>
            
            <div class="p-8">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <span class="font-semibold">3</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Who is paying for this program?</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Please select whether you will pay individually or the school will sponsor your participation.</p>
                </div>
                
                <form action="{{ route('student.registration.process_step3_inschool') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div class="border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="radio" name="payer_type" id="payer_individual" value="individual" class="w-5 h-5 text-primary focus:ring-primary" checked>
                                </div>
                                <label for="payer_individual" class="ml-3 cursor-pointer flex-grow">
                                    <h3 class="font-medium text-gray-800">Individual Payment</h3>
                                    <p class="text-gray-600 text-sm">I will pay for the program myself.</p>
                                </label>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="radio" name="payer_type" id="payer_school" value="school" class="w-5 h-5 text-primary focus:ring-primary">
                                </div>
                                <label for="payer_school" class="ml-3 cursor-pointer flex-grow">
                                    <h3 class="font-medium text-gray-800">School Sponsorship</h3>
                                    <p class="text-gray-600 text-sm">My school will sponsor my participation.</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-between">
                        <button type="button" onclick="history.back()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                            Back
                        </button>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-3 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50">
                            Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // The standard radio buttons now work correctly without additional JavaScript
        // This is because we're using the browser's built-in radio button functionality
    });
</script>
@endpush
@endsection
