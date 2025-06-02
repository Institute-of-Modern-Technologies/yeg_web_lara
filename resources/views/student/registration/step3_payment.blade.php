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
                
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-5 mb-8 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-2">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Payment Summary</h3>
                            <div class="mt-2 space-y-2">
                                <div class="flex justify-between border-b border-gray-200 pb-2">
                                    <span class="text-gray-600">Program Type:</span>
                                    <span class="font-medium text-gray-800">{{ session('registration.program_type_name') }}</span>
                                </div>
                                
                                @if(session('registration.school_id'))
                                <div class="flex justify-between border-b border-gray-200 pb-2">
                                    <span class="text-gray-600">School:</span>
                                    <span class="font-medium text-gray-800">{{ \App\Models\School::find(session('registration.school_id'))->name }}</span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between border-b border-gray-200 pb-2">
                                    <span class="text-gray-600">Fee Amount:</span>
                                    <span class="font-medium text-gray-800">GHC {{ number_format($feeAmount, 2) }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Instructions</h4>
                                <p class="text-sm text-gray-600">Please complete the payment form below to proceed with your registration. Your payment will be processed securely through our mobile money system.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('student.registration.process_payment') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
                        <!-- Payment Method Selector -->
                        <div class="bg-gray-50 px-4 py-4 border-b border-gray-200">
                            <h3 class="text-base font-medium text-gray-800">Select Payment Method</h3>
                            <p class="text-sm text-gray-500 mt-1">Please choose your preferred mobile money provider</p>
                        </div>
                        
                        <div class="p-5">
                            <!-- Network Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <label class="relative flex flex-col bg-white border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition-all transform duration-200 ease-in-out">
                                    <input type="radio" name="payment_network" value="mtn" class="absolute h-0 w-0 opacity-0" checked>
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center mr-3 transition-all duration-200">
                                            <span class="font-bold text-yellow-600">MTN</span>
                                        </div>
                                        <div>
                                            <span class="block font-medium text-gray-800 transition-all duration-200">MTN Mobile Money</span>
                                            <span class="text-xs text-gray-500">Most popular</span>
                                        </div>
                                    </div>
                                    <div class="absolute top-2 right-2 h-5 w-5 network-check hidden transform transition-all duration-200 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </label>
                                
                                <label class="relative flex flex-col bg-white border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition-all transform duration-200 ease-in-out">
                                    <input type="radio" name="payment_network" value="vodafone" class="absolute h-0 w-0 opacity-0">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center mr-3 transition-all duration-200">
                                            <span class="font-bold text-red-600">VF</span>
                                        </div>
                                        <div>
                                            <span class="block font-medium text-gray-800 transition-all duration-200">Vodafone Cash</span>
                                            <span class="text-xs text-gray-500">Fast processing</span>
                                        </div>
                                    </div>
                                    <div class="absolute top-2 right-2 h-5 w-5 network-check hidden transform transition-all duration-200 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </label>
                                
                                <label class="relative flex flex-col bg-white border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition-all transform duration-200 ease-in-out">
                                    <input type="radio" name="payment_network" value="airteltigo" class="absolute h-0 w-0 opacity-0">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center mr-3 transition-all duration-200">
                                            <span class="font-bold text-blue-600">AT</span>
                                        </div>
                                        <div>
                                            <span class="block font-medium text-gray-800 transition-all duration-200">AirtelTigo Money</span>
                                            <span class="text-xs text-gray-500">No extra fees</span>
                                        </div>
                                    </div>
                                    <div class="absolute top-2 right-2 h-5 w-5 network-check hidden transform transition-all duration-200 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Phone Number Input -->
                            <div class="mb-5">
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Mobile Money Number</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        +233
                                    </span>
                                    <input type="tel" id="phone_number" name="phone_number" class="flex-1 min-w-0 block w-full px-3 py-3 rounded-none rounded-r-md border border-gray-300 focus:ring-primary focus:border-primary" placeholder="XX XXX XXXX" pattern="[0-9]{9}" maxlength="10" required>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Enter your mobile money number without the leading zero</p>
                                @error('phone_number')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Payment Amount Confirmation -->
                            <div class="rounded-md bg-gradient-to-r from-primary-50 to-gray-50 p-4 border border-primary-100 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="block text-base font-medium text-gray-700">Program Fee</span>
                                        <span class="text-xs text-gray-500">Based on your selected program</span>
                                    </div>
                                    <div class="text-xl font-bold text-primary">GHC {{ number_format($feeAmount, 2) }}</div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total Amount Due</span>
                                        <span class="text-lg font-semibold text-gray-800">GHC {{ number_format($feeAmount, 2) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">This fee is set by the administrator based on your program type and school selection.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- For demo purposes only - skip payment option -->
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-md p-3">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <label for="skip_payment" class="flex items-center cursor-pointer">
                                    <input id="skip_payment" name="skip_payment" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <span class="ml-2 block text-sm font-medium text-gray-700">Demo Mode: Skip payment verification</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1 ml-6">This option is for demonstration purposes only.</p>
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
        // Handle network selection UI
        const networkRadios = document.querySelectorAll('input[name="payment_network"]');
        const networkLabels = document.querySelectorAll('label.relative');
        const networkChecks = document.querySelectorAll('.network-check');
        
        // Initialize state
        updateNetworkSelection();
        
        // Add event listeners
        networkRadios.forEach((radio, index) => {
            radio.addEventListener('change', updateNetworkSelection);
            
            // Also make clicking anywhere on the label work
            networkLabels[index].addEventListener('click', function() {
                radio.checked = true;
                updateNetworkSelection();
            });
        });
        
        function updateNetworkSelection() {
            networkRadios.forEach((radio, index) => {
                if (radio.checked) {
                    // Make selected network larger and highlighted
                    networkLabels[index].classList.add('border-primary', 'bg-primary', 'bg-opacity-5', 'scale-110', 'shadow-md', 'z-10');
                    networkChecks[index].classList.remove('hidden');
                    
                    // Make icon and text larger
                    const icon = networkLabels[index].querySelector('.h-12');
                    if (icon) {
                        icon.classList.remove('h-12', 'w-12');
                        icon.classList.add('h-14', 'w-14');
                    }
                    
                    const title = networkLabels[index].querySelector('.font-medium');
                    if (title) {
                        title.classList.add('text-lg');
                    }
                } else {
                    // Reset unselected networks
                    networkLabels[index].classList.remove('border-primary', 'bg-primary', 'bg-opacity-5', 'scale-110', 'shadow-md', 'z-10');
                    networkChecks[index].classList.add('hidden');
                    
                    // Reset icon and text size
                    const icon = networkLabels[index].querySelector('.h-14, .w-14');
                    if (icon) {
                        icon.classList.remove('h-14', 'w-14');
                        icon.classList.add('h-12', 'w-12');
                    }
                    
                    const title = networkLabels[index].querySelector('.font-medium');
                    if (title) {
                        title.classList.remove('text-lg');
                    }
                }
            });
        }
        
        // Format phone number as user types
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                // Remove non-digits
                let value = e.target.value.replace(/\D/g, '');
                
                // Enforce 10 digit limit
                if (value.length > 10) {
                    value = value.substr(0, 10);
                }
                
                // Format as XX XXX XXXX
                if (value.length > 5) {
                    value = value.substr(0, 2) + ' ' + value.substr(2, 3) + ' ' + value.substr(5);
                } else if (value.length > 2) {
                    value = value.substr(0, 2) + ' ' + value.substr(2);
                }
                
                e.target.value = value;
            });
        }
    });
</script>
@endpush

@endsection
