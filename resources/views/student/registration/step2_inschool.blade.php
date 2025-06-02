@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary py-6 px-8">
                <h1 class="text-2xl font-bold text-white">Student Registration</h1>
                <p class="text-white text-opacity-80 mt-1">Step 2: Select Your School</p>
            </div>
            
            <div class="p-8">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <span class="font-semibold">2</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Select Your School</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Please select your school from the list below.</p>
                </div>
                
                <form action="{{ route('student.registration.process_step2_inschool') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">School</label>
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
                        <a href="/students/register" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                            Back
                        </a>
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
