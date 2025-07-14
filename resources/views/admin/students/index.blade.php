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
            <a href="{{ route('admin.students.create') }}" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-600 transition-colors flex items-center">
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

    <!-- Filter Section - Moved to Top -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Filter Students</h2>
            <form action="{{ route('admin.students.index') }}" method="GET" id="filter-form">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- School Filter -->
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700">School</label>
                        <select id="school_id" name="school_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Program Type Filter -->
                    <div>
                        <label for="program_type_id" class="block text-sm font-medium text-gray-700">Program Type</label>
                        <select id="program_type_id" name="program_type_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md">
                            <option value="">All Programs</option>
                            @foreach($programTypes as $programType)
                                <option value="{{ $programType->id }}" {{ $programTypeId == $programType->id ? 'selected' : '' }}>{{ $programType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md">
                            <option value="">All Status</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    
                    <!-- Filter Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-blue-600 transition-colors">
                            Apply Filters
                        </button>
                        
                        @if($schoolId || $programTypeId || $status)
                            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-center text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Students List - Now Full Width -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="selectAll" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="selectAll" class="ml-2 text-sm text-gray-700 cursor-pointer hover:text-gray-900">Select All</label>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Students</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ count($students) }} results</p>
                        </div>
                    </div>
                    <div>
                        <form id="bulkDeleteForm" action="{{ route('admin.students.bulk-destroy') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button id="bulkDeleteBtn" disabled class="px-3 py-1 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors flex items-center opacity-50">
                            <i class="fas fa-trash-alt mr-1"></i> Delete
                        </button>
                    </div>
                </div>
                
                @if(count($students) > 0)
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
                                            <div class="flex flex-col sm:flex-row sm:items-center text-sm text-gray-500 mt-1">
                                                <span class="flex items-center">
                                                    <i class="fas fa-envelope mr-1.5 text-gray-400"></i>
                                                    {{ $student->email }}
                                                </span>
                                                <span class="hidden sm:inline mx-1.5">•</span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-school mr-1.5 text-gray-400"></i>
                                                    {{ $student->school->name ?? 'N/A' }}
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
                                                                @if($student->status == 'pending')
                                                                <a href="{{ route('admin.students.approve', $student->id) }}" class="text-yellow-600 block px-4 py-2 text-sm hover:bg-gray-100">
                                                                    <i class="fas fa-user-check mr-2"></i> Approve
                                                                </a>
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
                        </a>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="px-4 py-12 text-center">
                    <i class="fas fa-user-graduate text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">No students found with the selected filters</p>
                    @if($schoolId || $programTypeId || $status)
                        <a href="{{ route('admin.students.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">
                            <i class="fas fa-times mr-2"></i> Clear Filters
                        </a>
                    @endif
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
