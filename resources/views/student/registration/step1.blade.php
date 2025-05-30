@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary py-6 px-8">
                <h1 class="text-2xl font-bold text-white">Student Registration</h1>
                <p class="text-white text-opacity-80 mt-1">Step 1: Select Program Type</p>
            </div>
            
            <div class="p-8">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                            <span class="font-semibold">1</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Which program would you like to enroll in?</h2>
                    </div>
                    <p class="text-gray-600 ml-11">Please select one of the following program types.</p>
                </div>
                
                <form action="{{ route('student.registration.process_step1') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($programTypes as $programType)
                        <div class="border rounded-lg p-4 hover:border-primary hover:bg-primary hover:bg-opacity-5 transition-colors cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="radio" name="program_type_id" id="program_{{ $programType->id }}" value="{{ $programType->id }}" class="w-5 h-5 text-primary focus:ring-primary">
                                </div>
                                <label for="program_{{ $programType->id }}" class="ml-3 cursor-pointer flex-grow">
                                    <h3 class="font-medium text-gray-800">{{ $programType->name }}</h3>
                                    @if(isset($programType->description))
                                    <p class="text-gray-600 text-sm">{{ $programType->description }}</p>
                                    @endif
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @error('program_type_id')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    
                    <div class="mt-8">
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-3 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50">
                            Continue to Next Step
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
        const programOptions = document.querySelectorAll('.program-option');
        
        programOptions.forEach(option => {
            const radio = option.querySelector('.program-radio');
            const radioCircle = option.querySelector('.program-radio-circle');
            const radioDot = option.querySelector('.program-radio-dot');
            
            option.addEventListener('click', function() {
                // Reset all options
                programOptions.forEach(opt => {
                    opt.classList.remove('border-primary', 'bg-primary', 'bg-opacity-5');
                    opt.querySelector('.program-radio').checked = false;
                    opt.querySelector('.program-radio-dot').classList.add('hidden');
                });
                
                // Select current option
                option.classList.add('border-primary', 'bg-primary', 'bg-opacity-5');
                radio.checked = true;
                radioDot.classList.remove('hidden');
            });
        });
    });
</script>
@endpush
@endsection
