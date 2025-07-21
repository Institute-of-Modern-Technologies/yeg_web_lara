@extends('admin.dashboard')

@section('content')
<div class="p-6 bg-gray-50">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Student Management</h1>
        <div class="flex flex-wrap gap-2 sm:flex-nowrap">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-file-csv mr-2"></i>
                    <span>Import/Export</span>
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                    <div class="py-1">
                        <a href="{{ route('admin.students.import') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-upload mr-2"></i> Import Students
                        </a>
                        <a href="{{ route('admin.students.export') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-download mr-2"></i> Export Students
                        </a>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.students.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg shadow-md hover:bg-red-800 transition-all duration-300 flex items-center w-full sm:w-auto justify-center">
                <i class="fas fa-plus mr-2"></i>
                <span>Add Student</span>
            </a>
        </div>
    </div>
    
    <!-- Notifications -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
        <p>{{ session('warning') }}</p>
        @if(session('import_errors'))
            <button id="showImportErrors" class="text-yellow-700 underline cursor-pointer mt-2">Show Error Details</button>
            <div id="importErrorDetails" class="hidden mt-2">
                <ul class="list-disc pl-6">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Modern Filter Section -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transform transition-all duration-300 hover:shadow-xl">
            <div class="bg-gradient-to-r from-primary to-red-800 py-3 px-5">
                <h3 class="text-white font-medium flex items-center">
                    <i class="fas fa-filter mr-2"></i> Find Students
                </h3>
            </div>
            <div class="p-5">
                <form action="{{ route('admin.students.index') }}" method="GET" id="filter-form">
                    <!-- Search Field - Full Width -->  
                    <div class="mb-5">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300 group-focus-within:text-primary">Search</label>
                        <div class="relative group">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-primary focus:border-primary focus:ring-opacity-20 block w-full pl-10 pr-12 border-gray-300 rounded-lg shadow-sm transition-all duration-300" placeholder="Search by name, email, phone, or ID...">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors duration-300">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- School Filter -->
                    <div class="relative group">
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300 group-focus-within:text-primary">School</label>
                        <div class="relative">
                            <select id="school_id" name="school_id" class="w-full rounded-lg pl-10 border-gray-300 shadow-sm transition-all duration-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 pr-8 appearance-none">
                                <option value="">All Schools</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors duration-300">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Program Type Filter -->
                    <div class="relative group">
                        <label for="program_type_id" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300 group-focus-within:text-primary">Program Type</label>
                        <div class="relative">
                            <select id="program_type_id" name="program_type_id" class="w-full rounded-lg pl-10 border-gray-300 shadow-sm transition-all duration-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 pr-8 appearance-none">
                                <option value="">All Programs</option>
                                @foreach($programTypes as $programType)
                                    <option value="{{ $programType->id }}" {{ $programTypeId == $programType->id ? 'selected' : '' }}>{{ $programType->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors duration-300">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="relative group">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300 group-focus-within:text-primary">Status</label>
                        <div class="relative">
                            <select id="status" name="status" class="w-full rounded-lg pl-10 border-gray-300 shadow-sm transition-all duration-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 pr-8 appearance-none">
                                <option value="">All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                                <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors duration-300">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Filter Buttons -->
                <div class="flex gap-3 mt-5">
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-medium rounded-lg shadow-md hover:bg-red-800 transition-all duration-300 flex items-center justify-center min-w-[120px] group">
                        <i class="fas fa-filter mr-2 group-hover:animate-pulse"></i>Apply Filters
                    </button>
                    
                    @if($schoolId || $programTypeId || $status || request('search'))
                        <a href="{{ route('admin.students.index') }}" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg shadow-md hover:bg-gray-300 transition-all duration-300 flex items-center justify-center min-w-[120px]">
                            <i class="fas fa-times mr-2"></i>Clear All
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="space-y-6">
        <!-- Students List with Mobile-First Design -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-primary to-red-800 text-white px-0 py-0">
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h2 class="font-semibold text-white flex items-center">
                        <i class="fas fa-user-graduate mr-2"></i>
                        <span>Students</span>
                        <span class="ml-2 text-sm bg-white bg-opacity-20 rounded-full px-2 py-0.5">{{ count($students) }} results</span>
                    </h2>
                    <div class="flex items-center">
                        <div class="flex items-center bg-white bg-opacity-10 rounded-lg px-3 py-1.5 mr-3">
                            <input type="checkbox" id="selectAll" class="h-4 w-4 text-white border-white border-opacity-50 rounded">
                            <label for="selectAll" class="ml-2 text-sm text-white cursor-pointer">Select All</label>
                        </div>
                        <form id="bulkDeleteForm" action="{{ route('admin.students.bulk-destroy') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button id="bulkDeleteBtn" disabled class="px-3 py-1.5 bg-red-600 bg-opacity-80 text-white text-sm rounded-md hover:bg-red-700 transition-colors flex items-center opacity-50 shadow-sm">
                            <i class="fas fa-trash-alt mr-1.5"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
                
                @if(count($students) > 0)
                <!-- Mobile Card View (visible on small screens) -->
                <div class="block md:hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach($students as $student)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="selected_students[]" value="{{ $student->id }}" form="bulkDeleteForm" class="student-checkbox h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mt-1" onClick="event.stopPropagation()">
                                
                                <div class="w-full" onclick="window.location='{{ route('admin.students.show', $student->id) }}'" style="cursor: pointer;">
                                    <!-- Student Header: Name, Avatar and Status -->
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center space-x-3">
                                            <!-- Student Avatar -->
                                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            
                                            <div>
                                                <h3 class="font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h3>
                                                <p class="text-xs text-gray-500">ID: {{ $student->student_id ?? 'Not assigned' }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        @switch($student->status)
                                            @case('active')
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                                @break
                                            @case('inactive')
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                                @break
                                            @case('pending')
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                                @break
                                            @default
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ ucfirst($student->status) }}
                                                </span>
                                        @endswitch
                                    </div>
                                    
                                    <!-- Student Details -->
                                    <div class="text-sm text-gray-600 mt-2 space-y-1.5">
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope text-gray-400 w-5"></i>
                                            <span class="ml-2 truncate">{{ $student->email }}</span>
                                        </div>
                                        
                                        <!-- Parent Contact (New) -->
                                        <div class="flex items-center">
                                            <i class="fas fa-user-friends text-gray-400 w-5"></i>
                                            <span class="ml-2 truncate">{{ $student->parent_contact ?: 'No parent contact' }}</span>
                                        </div>
                                        
                                        <!-- Program Type (New) -->
                                        <div class="flex items-center">
                                            <i class="fas fa-graduation-cap text-gray-400 w-5"></i>
                                            <span class="ml-2 truncate">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#950713]/10 text-[#950713]">
                                                    {{ $student->programType ? $student->programType->name : 'Not assigned' }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-school text-gray-400 w-5"></i>
                                            <span class="ml-2 truncate text-[#950713] font-medium">{{ $student->display_school_name }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-graduation-cap text-gray-400 w-5"></i>
                                            <span class="ml-2 truncate">{{ $student->program_type->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mobile Action Buttons -->
                            <div class="mt-4 flex justify-end border-t border-gray-100 pt-3 gap-2">
                                <a href="{{ route('admin.students.show', $student->id) }}" class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @if($student->status == 'pending')
                                <form method="POST" action="{{ route('admin.students.approve', $student->id) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-green-50 text-green-600 rounded-md hover:bg-green-100 transition-colors flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i> Approve
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Desktop List View (visible on medium screens and up) -->
                <div class="hidden md:block">
                    <ul class="divide-y divide-gray-200">
                        @foreach($students as $student)
                        <li class="hover:bg-gray-50">
                            <div class="block">
                                <div class="flex items-center px-4 py-4 sm:px-6">
                                    <input type="checkbox" name="selected_students[]" value="{{ $student->id }}" form="bulkDeleteForm" class="student-checkbox h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mr-4" onClick="event.stopPropagation()">
                                    <div class="min-w-0 flex-1 flex items-center justify-between" onclick="window.location='{{ route('admin.students.show', $student->id) }}'" style="cursor: pointer;">
                                        <div class="flex items-center space-x-4">
                                            <!-- Student Avatar -->
                                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary flex items-center justify-center text-white font-bold text-lg">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            
                                            <!-- Student Info -->
                                            <div>
                                                <p class="text-base font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-4 gap-y-1 text-sm text-gray-500 mt-2">
                                                    <!-- Email -->
                                                    <span class="flex items-center">
                                                        <i class="fas fa-envelope mr-1.5 text-gray-400"></i>
                                                        <span class="truncate">{{ $student->email }}</span>
                                                    </span>
                                                    
                                                    <!-- Parent Contact (New) -->
                                                    <span class="flex items-center">
                                                        <i class="fas fa-user-friends mr-1.5 text-gray-400"></i>
                                                        <span class="truncate">{{ $student->parent_contact ?: 'No parent contact' }}</span>
                                                    </span>
                                                    
                                                    <!-- School -->
                                                    <span class="flex items-center">
                                                        <i class="fas fa-school mr-1.5 text-gray-400"></i>
                                                        <span class="text-[#950713] font-medium truncate">{{ $student->display_school_name }}</span>
                                                    </span>
                                                    
                                                    <!-- Program Type (New) -->
                                                    <span class="flex items-center">
                                                        <i class="fas fa-graduation-cap mr-1.5 text-gray-400"></i>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#950713]/10 text-[#950713]">
                                                            {{ $student->programType ? $student->programType->name : 'Not assigned' }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Badge & Actions -->
                                        <div class="flex items-center space-x-3">
                                            <!-- Status Badge -->
                                            @switch($student->status)
                                                @case('active')
                                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                    @break
                                                @case('inactive')
                                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Inactive
                                                    </span>
                                                    @break
                                                @case('pending')
                                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ ucfirst($student->status) }}
                                                    </span>
                                            @endswitch
                                            
                                            <!-- Action Menu -->
                                            <div class="flex items-center">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 flex" onclick="event.stopPropagation();">
                                                        <div class="relative" x-data="{ open: false }">
                                                            <button @click.prevent="open = !open" class="text-gray-500 hover:text-gray-700">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10">
                                                                <div class="py-1">
                                                                    <a href="{{ route('admin.students.show', $student->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                                                        <i class="fas fa-eye mr-2"></i> View Details
                                                                    </a>
                                                                    <a href="{{ route('admin.students.edit', $student->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                                                        <i class="fas fa-edit mr-2"></i> Edit
                                                                    </a>
                                                                </div>
                                                                @if($student->status == 'pending')
                                                                <div class="border-t border-gray-100 mt-1"></div>
                                                                <div class="py-1">
                                                                    <form method="POST" action="{{ route('admin.students.approve', $student->id) }}" class="block">
                                                                        @csrf
                                                                        <button type="submit" class="text-green-600 w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                                                            <i class="fas fa-user-check mr-2"></i> Approve
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                @endif
                                                                <button onclick="event.preventDefault(); confirmDelete({{ $student->id }});" class="text-red-600 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                                                    <i class="fas fa-trash-alt mr-2"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <!-- Empty State - Mobile & Desktop -->                
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="bg-gray-100 rounded-full p-6 mb-4">
                        <i class="fas fa-user-graduate text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No students found</h3>
                    <p class="text-gray-500 max-w-md mb-6">There are no students matching your current filters. Try changing your search criteria or add a new student.</p>
                    <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Student
                    </a>
                </div>
                @endif
                
                <!-- Pagination -->
                <div class="px-4 py-4 border-t border-gray-200">
                    {{ $students->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    // Single student delete confirmation
    function confirmDelete(studentId) {
        event.preventDefault();
        event.stopPropagation();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById(`delete-form-${studentId}`).submit();
                
                // Show deletion in progress message
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    }
    
    // Bulk delete functionality
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        
        // Select all functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                
                studentCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                
                updateBulkDeleteButton();
            });
        }
        
        // Individual checkbox change
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkDeleteButton();
                
                // Update 'select all' checkbox
                if (selectAllCheckbox) {
                    const allChecked = Array.from(studentCheckboxes).every(cb => cb.checked);
                    const anyChecked = Array.from(studentCheckboxes).some(cb => cb.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = anyChecked && !allChecked;
                }
            });
        });
        
        // Enable/disable bulk delete button based on selection
        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
            
            if (checkedCount > 0) {
                bulkDeleteBtn.disabled = false;
                bulkDeleteBtn.classList.remove('opacity-50');
            } else {
                bulkDeleteBtn.disabled = true;
                bulkDeleteBtn.classList.add('opacity-50');
            }
        }
        
        // Import error details toggle
        const showImportErrors = document.getElementById('showImportErrors');
        if (showImportErrors) {
            const importErrorDetails = document.getElementById('importErrorDetails');
            showImportErrors.addEventListener('click', function() {
                if (importErrorDetails.classList.contains('hidden')) {
                    importErrorDetails.classList.remove('hidden');
                    showImportErrors.textContent = 'Hide Error Details';
                } else {
                    importErrorDetails.classList.add('hidden');
                    showImportErrors.textContent = 'Show Error Details';
                }
            });
        }
        
        // Bulk delete confirmation
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
            
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${checkedCount} selected student(s). This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the bulk delete form
                    bulkDeleteForm.submit();
                    
                    // Show deletion in progress message
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        });
    });
</script>
