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
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <span class="font-semibold">2</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Are you from a partner school?</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Students from partner schools receive a ₵100 discount on program fees. If your school isn't listed, select "Not Yet".</p>
                </div>
                
                <form action="{{ route('student.registration.process_step2_other') }}" method="POST" class="space-y-6">
                    @csrf
                    
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
                                        Regular fee: GHC450<br>
                                        Partner school fee: GHC350
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div class="border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="radio" name="school_selection" id="school_not_yet" value="not_yet" class="w-5 h-5 text-primary focus:ring-primary" checked>
                                </div>
                                <label for="school_not_yet" class="ml-3 cursor-pointer flex-grow">
                                    <h3 class="font-medium text-gray-800">Not Yet</h3>
                                    <p class="text-gray-600 text-sm">I'm not from a partner school (Standard fee: GHC450)</p>
                                </label>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="radio" name="school_selection" id="school_select" value="select_school" class="w-5 h-5 text-primary focus:ring-primary">
                                </div>
                                <label for="school_select" class="ml-3 cursor-pointer flex-grow">
                                    <h3 class="font-medium text-gray-800">Select Partner School</h3>
                                    <p class="text-gray-600 text-sm">I'm from a partner school (Discounted fee: GHC350)</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="school_select_container" class="mt-6 hidden">
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">Select Your School</label>
                        <select id="school_id" name="school_id" class="mt-1 block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md">
                            <option value="">-- Select School --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                        
                        @error('school_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('student.registration.step1') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                            Back
                        </a>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-3 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50">
                            Continue to Payment
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
        const schoolSelectContainer = document.getElementById('school_select_container');
        const schoolSelect = document.getElementById('school_id');
        const schoolNotYetRadio = document.getElementById('school_not_yet');
        const schoolSelectRadio = document.getElementById('school_select');
        
        // Initialize state based on default selection
        updateSchoolContainer();
        
        // Add event listeners to the radio buttons
        schoolNotYetRadio.addEventListener('change', updateSchoolContainer);
        schoolSelectRadio.addEventListener('change', updateSchoolContainer);
        
        function updateSchoolContainer() {
            if (schoolSelectRadio.checked) {
                schoolSelectContainer.classList.remove('hidden');
                schoolSelect.setAttribute('required', 'required');
            } else {
                schoolSelectContainer.classList.add('hidden');
                schoolSelect.removeAttribute('required');
                schoolSelect.value = '';
            }
        }
    });
</script>
@endpush
@endsection
