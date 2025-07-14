@extends('admin.dashboard')

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
                    
                    <!-- School Selection Type -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">School Selection <span class="text-red-500">*</span></label>
                        <div class="flex gap-4 mb-3">
                            <div class="flex items-center">
                                <input type="radio" id="existing_school" name="school_selection" value="existing" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary" checked onchange="toggleSchoolSelection()">
                                <label for="existing_school" class="ml-2 block text-sm text-gray-700">Select from existing schools</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="new_school" name="school_selection" value="new" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary" onchange="toggleSchoolSelection()">
                                <label for="new_school" class="ml-2 block text-sm text-gray-700">Enter a new school</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- School Dropdown (for existing schools) -->
                    <div id="existing_school_div" class="md:col-span-2">
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">Select School <span class="text-red-500">*</span></label>
                        <select name="school_id" id="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- New School Name (for manual entry) -->
                    <div id="new_school_div" class="md:col-span-2 hidden">
                        <label for="school_name" class="block text-sm font-medium text-gray-700 mb-1">School Name <span class="text-red-500">*</span></label>
                        <input type="text" id="school_name" name="school_name" value="{{ old('school_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Enter school name">
                    </div>
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
        
        function toggleSchoolSelection() {
            const existingSchoolRadio = document.getElementById('existing_school');
            const existingSchoolDiv = document.getElementById('existing_school_div');
            const newSchoolDiv = document.getElementById('new_school_div');
            const schoolIdSelect = document.getElementById('school_id');
            const schoolNameInput = document.getElementById('school_name');
            
            if (existingSchoolRadio.checked) {
                existingSchoolDiv.classList.remove('hidden');
                newSchoolDiv.classList.add('hidden');
                schoolIdSelect.setAttribute('required', '');
                schoolNameInput.removeAttribute('required');
            } else {
                existingSchoolDiv.classList.add('hidden');
                newSchoolDiv.classList.remove('hidden');
                schoolIdSelect.removeAttribute('required');
                schoolNameInput.setAttribute('required', '');
            }
        }
        
        // Initialize the form when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            toggleSchoolSelection();
            if (document.getElementById('date_of_birth').value) {
                calculateAge();
            }
        });
    </script>
@endsection
