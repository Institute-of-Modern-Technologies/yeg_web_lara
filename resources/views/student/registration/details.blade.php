@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary py-6 px-8">
                <h1 class="text-2xl font-bold text-white">Student Registration</h1>
                <p class="text-white text-opacity-80 mt-1">Final Step: Student Details</p>
            </div>
            
            <div class="p-8">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Student Information</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Please provide your personal details to complete your registration.</p>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Registration Information</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p><strong>Program:</strong> {{ $programType->name }}</p>
                                @endif
                                @if(session('registration.fee_amount'))
                                    <p><strong>Fee Amount:</strong> GHC {{ number_format(session('registration.fee_amount'), 2) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('student.registration.process.details') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            @error('first_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            @error('last_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            @error('phone')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            @error('date_of_birth')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                            <select id="gender" name="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Address Information -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                                @error('address')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Town <span class="text-red-500">*</span></label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                                @error('city')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region <span class="text-red-500">*</span></label>
                                <select id="region" name="region" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                                    <option value="">Select Region</option>
                                    <option value="Greater Accra" {{ old('region') == 'Greater Accra' ? 'selected' : '' }}>Greater Accra</option>
                                    <option value="Ashanti" {{ old('region') == 'Ashanti' ? 'selected' : '' }}>Ashanti</option>
                                    <option value="Western" {{ old('region') == 'Western' ? 'selected' : '' }}>Western</option>
                                    <option value="Eastern" {{ old('region') == 'Eastern' ? 'selected' : '' }}>Eastern</option>
                                    <option value="Central" {{ old('region') == 'Central' ? 'selected' : '' }}>Central</option>
                                    <option value="Volta" {{ old('region') == 'Volta' ? 'selected' : '' }}>Volta</option>
                                    <option value="Northern" {{ old('region') == 'Northern' ? 'selected' : '' }}>Northern</option>
                                    <option value="Upper East" {{ old('region') == 'Upper East' ? 'selected' : '' }}>Upper East</option>
                                    <option value="Upper West" {{ old('region') == 'Upper West' ? 'selected' : '' }}>Upper West</option>
                                    <option value="Bono" {{ old('region') == 'Bono' ? 'selected' : '' }}>Bono</option>
                                    <option value="Other" {{ old('region') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('region')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                            Complete Registration
                            <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
