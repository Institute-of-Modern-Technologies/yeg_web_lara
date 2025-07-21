@extends('admin.dashboard')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        border-color: #D1D5DB !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-dropdown {
        border-color: #D1D5DB !important;
        border-radius: 0.375rem !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #950713 !important;
        color: white !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: rgba(149, 7, 19, 0.1) !important;
    }
    /* Style for new entries */
    .select2-results__option .select2-highlighted-new {
        font-style: italic;
        color: #950713;
    }
</style>
@endsection

@section('content')
<div class="p-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold">Add New Student</h1>
                <p class="text-sm text-gray-500">Create a new student record</p>
            </div>
            <div>
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-medium hover:bg-gray-700 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Students
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif
        
        @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Create Form -->
        <form action="{{ route('admin.students.store') }}" method="POST" class="bg-white shadow-md rounded-lg overflow-hidden">
            @csrf
            
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Student Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    
                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <!-- Parent Contact -->
                    <div>
                        <label for="parent_contact" class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Contact <span class="text-red-500">*</span></label>
                        <input type="text" name="parent_contact" id="parent_contact" value="{{ old('parent_contact') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" onchange="calculateAge()" required>
                    </div>
                    
                    <!-- Age (hidden) -->
                    <div class="hidden">
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                        <input type="number" name="age" id="age" min="1" max="100" value="{{ old('age') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" readonly>
                    </div>
                    
                    <!-- Class -->
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class <span class="text-red-500">*</span></label>
                        <input type="text" name="class" id="class" value="{{ old('class') }}" placeholder="e.g. Grade 3, JHS 1, Form 2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Address Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Town <span class="text-red-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Region -->
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region <span class="text-red-500">*</span></label>
                        <select id="region" name="region" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
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
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Program Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Program Type -->
                    <div>
                        <label for="program_type_id" class="block text-sm font-medium text-gray-700 mb-1">Program Type <span class="text-red-500">*</span></label>
                        <select name="program_type_id" id="program_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            <option value="">Select Program Type</option>
                            @foreach($programTypes as $type)
                            <option value="{{ $type->id }}" {{ old('program_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- School Information Section -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">School Information</h2>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">School <span class="text-red-500">*</span></label>
                    
                    <!-- Option tabs -->
                    <div class="flex border-b border-gray-200 mb-4">
                        <button type="button" id="select-school-tab" 
                            class="py-2 px-4 border-b-2 border-[#950713] text-[#950713] font-medium text-sm focus:outline-none"
                            onclick="switchTab('select')">
                            Select from List
                        </button>
                        <button type="button" id="enter-school-tab" 
                            class="py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm focus:outline-none"
                            onclick="switchTab('enter')">
                            Enter Manually
                        </button>
                    </div>
                    
                    <!-- Hidden input to track which method is selected -->
                    <input type="hidden" name="school_selection_method" id="school_selection_method" value="select">
                    
                    <!-- Select school option -->
                    <div id="select-school-content" class="animate-fade-in">
                        <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#950713] focus:ring focus:ring-[#950713] focus:ring-opacity-50">
                            <option value="">-- Select School --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle text-[#950713] mr-1"></i>
                            Select the student's school from the list.
                        </p>
                    </div>
                    
                    <!-- Enter school manually option -->
                    <div id="enter-school-content" class="hidden animate-fade-in">
                        <input type="text" id="school_name" name="school_name" placeholder="Enter school name" value="{{ old('school_name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#950713] focus:ring focus:ring-[#950713] focus:ring-opacity-50">
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle text-[#950713] mr-1"></i>
                            If the school is not in our list, please enter the name here. It will be added as a pending school.
                        </p>
                    </div>
                    
                    @error('school_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    
                    @error('school_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="p-6 flex items-center justify-end gap-4 border-t border-gray-200">
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                    <i class="fas fa-save mr-2"></i> Add Student
                </button>
            </div>
        </form>
    </div>
</div>
    <!-- Include jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function calculateAge() {
            const dob = document.getElementById('date_of_birth').value;
            
            if(dob) {
                const birthDate = new Date(dob);
                const today = new Date();
                
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDifference = today.getMonth() - birthDate.getMonth();
                
                if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                document.getElementById('age').value = age;
            }
        }
        
        /**
         * Toggle between existing school selection and new school input
         */
        function toggleSchoolSelection(type) {
            if (type === 'existing') {
                document.getElementById('existing_school_container').classList.remove('hidden');
                document.getElementById('new_school_container').classList.add('hidden');
                document.getElementById('school_id').setAttribute('required', 'required');
                document.getElementById('school_name').removeAttribute('required');
                document.getElementById('school_name').value = '';
            } else {
                document.getElementById('existing_school_container').classList.add('hidden');
                document.getElementById('new_school_container').classList.remove('hidden');
                document.getElementById('school_id').removeAttribute('required');
                document.getElementById('school_name').setAttribute('required', 'required');
                document.getElementById('school_id').value = '';
                
                // If Select2 is initialized
                if (window.jQuery && $.fn.select2) {
                    $('#school_id').val(null).trigger('change');
                }
            }
        }
        
        // Initialize Select2 for school dropdown with tagging support
        function initializeSelect2() {
            $('#school_id').select2({
                placeholder: 'Select a school or type to add a new one',
                allowClear: true,
                width: '100%',
                // Modern styling with brand color
                theme: 'classic',
                tags: true,
                createTag: function(params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newTag: true
                    };
                },
                templateResult: function(data) {
                    if (!data.id) return data.text;
                    
                    var $result = $('<span></span>');
                    $result.text(data.text);
                    
                    if (data.newTag) {
                        $result = $('<span><span class="text-[#950713] mr-1">New:</span>' + data.text + '</span>');
                    }
                    
                    return $result;
                },
                templateSelection: function(data) {
                    if (!data.id) return data.text;
                    
                    if (data.newTag) {
                        return $('<span><span class="text-[#950713] mr-1">New:</span><span style="color: #950713; font-weight: 500;">' + data.text + '</span></span>');
                    }
                    
                    return $('<span style="color: #950713; font-weight: 500;">' + data.text + '</span>');
                },
                // Custom styling for the dropdown
                dropdownCssClass: 'select2-dropdown-school'
            });
            
            // Add custom CSS for the Select2 dropdown
            $('<style>\n\
                .select2-container--classic .select2-selection--single {\n\
                    height: 38px;\n\
                    padding: 4px;\n\
                    font-size: 16px;\n\
                    border: 1px solid #d1d5db;\n\
                }\n\
                .select2-container--classic .select2-selection--single .select2-selection__rendered {\n\
                    line-height: 28px;\n\
                    padding-left: 8px;\n\
                }\n\
                .select2-container--classic .select2-selection--single .select2-selection__arrow {\n\
                    height: 36px;\n\
                }\n\
                .select2-container--classic .select2-results__option--highlighted[aria-selected] {\n\
                    background-color: #950713 !important;\n\
                    color: white;\n\
                }\n\
                .select2-container--classic .select2-selection--single:focus {\n\
                    border-color: #950713 !important;\n\
                    box-shadow: 0 0 0 3px rgba(149, 7, 19, 0.25);\n\
                }\n\
                .select2-container--classic.select2-container--open .select2-dropdown {\n\
                    border-color: #950713;\n\
                }\n\
                .select2-container--classic .select2-results__option {\n\
                    padding: 8px;\n\
                    font-size: 15px;\n\
                }\n\
            </style>').appendTo('head');
        }
        
        // Function to switch between tabs for school selection
        function switchTab(tab) {
            // Update tab styles
            if (tab === 'select') {
                document.getElementById('select-school-tab').classList.add('border-[#950713]', 'text-[#950713]');
                document.getElementById('select-school-tab').classList.remove('border-transparent', 'text-gray-500');
                document.getElementById('enter-school-tab').classList.add('border-transparent', 'text-gray-500');
                document.getElementById('enter-school-tab').classList.remove('border-[#950713]', 'text-[#950713]');
                
                // Show/hide content
                document.getElementById('select-school-content').classList.remove('hidden');
                document.getElementById('enter-school-content').classList.add('hidden');
                
                // Reset the manual input
                document.getElementById('school_name').value = '';
                
                // Update the hidden input to track the active tab
                document.getElementById('school_selection_method').value = 'select';
            } else {
                document.getElementById('enter-school-tab').classList.add('border-[#950713]', 'text-[#950713]');
                document.getElementById('enter-school-tab').classList.remove('border-transparent', 'text-gray-500');
                document.getElementById('select-school-tab').classList.add('border-transparent', 'text-gray-500');
                document.getElementById('select-school-tab').classList.remove('border-[#950713]', 'text-[#950713]');
                
                // Show/hide content
                document.getElementById('enter-school-content').classList.remove('hidden');
                document.getElementById('select-school-content').classList.add('hidden');
                
                // Reset the dropdown
                document.getElementById('school_id').value = '';
                
                // Update the hidden input to track the active tab
                document.getElementById('school_selection_method').value = 'enter';
            }
        }
        
        // Initialize the form when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('date_of_birth').value) {
                calculateAge();
            }
            
            // Add form validation for school selection
            document.querySelector('form').addEventListener('submit', function(e) {
                const selectionMethod = document.getElementById('school_selection_method').value;
                let isValid = true;
                
                if (selectionMethod === 'select') {
                    if (!document.getElementById('school_id').value) {
                        e.preventDefault();
                        const errorMsg = document.querySelector('#school_id_error') || document.createElement('p');
                        errorMsg.id = 'school_id_error';
                        errorMsg.className = 'text-red-500 text-xs mt-1';
                        errorMsg.textContent = 'Please select a school from the list.';
                        
                        if (!document.querySelector('#school_id_error')) {
                            document.getElementById('school_id').parentNode.appendChild(errorMsg);
                        }
                        isValid = false;
                    }
                } else { // 'enter' tab is active
                    if (!document.getElementById('school_name').value) {
                        e.preventDefault();
                        const errorMsg = document.querySelector('#school_name_error') || document.createElement('p');
                        errorMsg.id = 'school_name_error';
                        errorMsg.className = 'text-red-500 text-xs mt-1';
                        errorMsg.textContent = 'Please enter a school name.';
                        
                        if (!document.querySelector('#school_name_error')) {
                            document.getElementById('school_name').parentNode.appendChild(errorMsg);
                        }
                        isValid = false;
                    }
                }
                
                return isValid;
            });
        });
    </script>
@endsection
