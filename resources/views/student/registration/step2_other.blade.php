@extends('layouts.app')

@section('content')
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
                
                <!-- Debug Information -->
                <div class="mb-4 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h3 class="font-bold text-sm text-gray-700 mb-1">Debug Information</h3>
                    <div class="text-xs text-gray-600">
                        <p>Session ID: {{ session()->getId() }}</p>
                        <p>Program Type ID: {{ session('registration.program_type_id') ?? 'Not set' }}</p>
                        <p>Previous School Selection: {{ session('registration.school_selection') ?? 'Not set' }}</p>
                    </div>
                </div>
                
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
                    <input type="hidden" name="debug_program_type" value="{{ session('registration.program_type_id') }}">
                    <input type="hidden" name="debug_timestamp" value="{{ time() }}">
                    
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
                    <input type="hidden" name="debug_program_type" value="{{ session('registration.program_type_id') }}">
                    <input type="hidden" name="debug_timestamp" value="{{ time() }}">
                    
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

@push('styles')
<style>
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the partner school form and add validation
        const schoolForm = document.getElementById('school-selection-form');
        const schoolSelect = document.getElementById('school_id');
        
        if (schoolForm) {
            schoolForm.addEventListener('submit', function(e) {
                if (!schoolSelect.value) {
                    e.preventDefault();
                    alert('Please select a school from the dropdown before proceeding');
                    schoolSelect.focus();
                    return false;
                }
            });
        }
    });
</script>
@endpush
@endsection
