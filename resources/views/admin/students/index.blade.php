@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Student Management</h1>
        <div class="ml-auto flex space-x-2">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-file-csv mr-1"></i>
                    <span>Import/Export</span>
                    <i class="fas fa-chevron-down ml-1"></i>
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
            <button id="bulkDeleteBtn" disabled class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center opacity-50">
                <i class="fas fa-trash-alt mr-2"></i>
                <span>Bulk Delete</span>
            </button>
            <a href="{{ route('admin.students.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>
                <span>Add New Student</span>
            </a>
        </div>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
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
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6 bg-primary">
        <h1 class="text-xl font-bold text-white">Student Records</h1>
        <p class="text-white text-opacity-80">View and manage student records</p>
    </div>
    
    <!-- Filter Form -->
    <div class="p-6 border-b">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="w-full sm:w-auto">
                <label for="school_id" class="block text-sm font-medium text-gray-700">Filter by School</label>
                <select id="school_id" name="school_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full sm:w-auto">
                <label for="program_type_id" class="block text-sm font-medium text-gray-700">Filter by Program Type</label>
                <select id="program_type_id" name="program_type_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                    <option value="">All Programs</option>
                    @foreach($programTypes as $programType)
                        <option value="{{ $programType->id }}" {{ $programTypeId == $programType->id ? 'selected' : '' }}>{{ $programType->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full sm:w-auto">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-600 transition-colors">
                    Apply Filters
                </button>
                
                @if($schoolId || $programTypeId)
                    <a href="{{ route('admin.students.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Students List -->
    <form id="bulkDeleteForm" action="{{ route('admin.students.bulk-destroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                                <label for="selectAll" class="ml-2 sr-only">Select All</label>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reg. Number
                        </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Program Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        School
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Registration Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(count($students) > 0)
                    @foreach($students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox form-checkbox h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary" {{ $student->payments && $student->payments->count() > 0 ? 'disabled title="Cannot delete student with payment records"' : '' }}>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                {{ $student->registration_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->programType->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->school->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($student->status)
                                    @case('active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                        @break
                                    @case('inactive')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                        @break
                                    @default
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $student->status }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($student->phone)<span class="font-medium">Phone:</span> {{ $student->phone }}<br>@endif
                                @if($student->email)<span class="font-medium">Email:</span> {{ $student->email }}<br>@endif
                                @if($student->parent_contact)<span class="font-medium">Parent:</span> {{ $student->parent_contact }}@endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $student->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.students.show', $student->id) }}" class="text-blue-600 hover:text-blue-900" title="View Student">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit Student">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $student->id }})" class="text-red-600 hover:text-red-900" title="Delete Student">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No students found with the selected filters.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $students->withQueryString()->links() }}
    </div>
</div>
    </form>
</div>
@endsection

<script>
    // Single student delete confirmation
    function confirmDelete(studentId) {
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
        const studentCheckboxes = document.querySelectorAll('.student-checkbox:not([disabled])');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateBulkDeleteButton();
        });
        
        // Individual checkbox change
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkDeleteButton();
                
                // Update 'select all' checkbox
                const allChecked = Array.from(studentCheckboxes).every(cb => cb.checked);
                const anyChecked = Array.from(studentCheckboxes).some(cb => cb.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = anyChecked && !allChecked;
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
        bulkDeleteBtn.addEventListener('click', function() {
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
