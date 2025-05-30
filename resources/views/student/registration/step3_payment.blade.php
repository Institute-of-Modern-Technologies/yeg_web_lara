@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary py-6 px-8">
                <h1 class="text-2xl font-bold text-white">Student Registration</h1>
                <p class="text-white text-opacity-80 mt-1">Step 3: Payment</p>
            </div>
            
            <div class="p-8">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <span class="font-semibold">3</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Payment Information</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Please provide your payment details below.</p>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Payment Information</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p><strong>Program Type:</strong> {{ session('registration.program_type_name') }}</p>
                                @if(session('registration.school_id'))
                                    <p><strong>School:</strong> {{ \App\Models\School::find(session('registration.school_id'))->name }}</p>
                                @endif
                                <p><strong>Fee Amount:</strong> GHC {{ number_format($feeAmount, 2) }}</p>
                                <p class="mt-1">Please make a payment via Mobile Money to the number below and enter your payment reference number.</p>
                                <p class="mt-2 font-medium">Mobile Money: 0559999999</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('student.registration.process_payment') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Payment Reference Number</label>
                        <input type="text" id="payment_reference" name="payment_reference" class="mt-1 block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" placeholder="Enter the reference number from your payment" required>
                        
                        @error('payment_reference')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- For demo purposes only - skip payment option -->
                    <div class="mt-4">
                        <div class="flex items-center">
                            <input id="skip_payment" name="skip_payment" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="skip_payment" class="ml-2 block text-sm text-gray-600">
                                Demo Mode: Skip payment verification
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-6">This option is for demonstration purposes only.</p>
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
@endsection
