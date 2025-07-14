@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold">Edit Student</h1>
                <p class="text-sm text-gray-500">Update student information and settings</p>
            </div>
            <div>
                <a href="{{ route('admin.students.show', $student->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-medium hover:bg-gray-700 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Details
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

        <!-- Edit Form -->
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="bg-white shadow-md rounded-lg overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Student Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', explode(' ', $student->full_name)[0] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', count(explode(' ', $student->full_name)) > 1 ? implode(' ', array_slice(explode(' ', $student->full_name), 1)) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <!-- Hidden Age -->
                    <input type="hidden" name="age" id="age" value="{{ old('age', $student->age) }}" readonly>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    
                    <!-- Parent Contact -->
                    <div>
                        <label for="parent_contact" class="block text-sm font-medium text-gray-700 mb-1">Parent Contact <span class="text-red-500">*</span></label>
                        <input type="text" name="parent_contact" id="parent_contact" value="{{ old('parent_contact', $student->parent_contact) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" onchange="calculateAge()" required>
                    </div>
                    
                    <!-- Class -->
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class <span class="text-red-500">*</span></label>
                        <input type="text" name="class" id="class" value="{{ old('class', $student->class) }}" placeholder="e.g. Grade 3, JHS 1, Form 2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Address Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                        <input type="text" id="address" name="address" value="{{ old('address', $student->address) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Town <span class="text-red-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city', $student->city) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Region -->
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region <span class="text-red-500">*</span></label>
                        <select id="region" name="region" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            <option value="">Select Region</option>
                            <option value="Greater Accra" {{ old('region', $student->region) == 'Greater Accra' ? 'selected' : '' }}>Greater Accra</option>
                            <option value="Ashanti" {{ old('region', $student->region) == 'Ashanti' ? 'selected' : '' }}>Ashanti</option>
                            <option value="Western" {{ old('region', $student->region) == 'Western' ? 'selected' : '' }}>Western</option>
                            <option value="Eastern" {{ old('region', $student->region) == 'Eastern' ? 'selected' : '' }}>Eastern</option>
                            <option value="Central" {{ old('region', $student->region) == 'Central' ? 'selected' : '' }}>Central</option>
                            <option value="Volta" {{ old('region', $student->region) == 'Volta' ? 'selected' : '' }}>Volta</option>
                            <option value="Northern" {{ old('region', $student->region) == 'Northern' ? 'selected' : '' }}>Northern</option>
                            <option value="Upper East" {{ old('region', $student->region) == 'Upper East' ? 'selected' : '' }}>Upper East</option>
                            <option value="Upper West" {{ old('region', $student->region) == 'Upper West' ? 'selected' : '' }}>Upper West</option>
                            <option value="Bono" {{ old('region', $student->region) == 'Bono' ? 'selected' : '' }}>Bono</option>
                            <option value="Other" {{ old('region', $student->region) == 'Other' ? 'selected' : '' }}>Other</option>
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
                            <option value="{{ $type->id }}" {{ old('program_type_id', $student->program_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- School -->
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">School <span class="text-red-500">*</span></label>
                        <select name="school_id" id="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $student->school_id) == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">Account Status</h2>
                
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <input id="status-active" name="status" type="radio" value="active" 
                                    {{ old('status', $student->status) == 'active' ? 'checked' : '' }}
                                    class="focus:ring-primary h-4 w-4 text-primary border-gray-300">
                                <label for="status-active" class="ml-2 block text-sm text-gray-700">
                                    Active
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="status-inactive" name="status" type="radio" value="inactive"
                                    {{ old('status', $student->status) == 'inactive' ? 'checked' : '' }}
                                    class="focus:ring-primary h-4 w-4 text-primary border-gray-300">
                                <label for="status-inactive" class="ml-2 block text-sm text-gray-700">
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Registration Info (Read-Only) -->
                <div class="bg-gray-50 rounded-md p-4 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Registration Information (Read-Only)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Registration Number</span>
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ $student->registration_number }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Registration Date</span>
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($student->created_at)->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.students.show', $student->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                        Update Student
                    </button>
                </div>
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
        
        // Calculate age on page load if date of birth is already set
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('date_of_birth').value) {
                calculateAge();
            }
        });
    </script>
@endsection
