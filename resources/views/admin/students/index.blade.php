@extends('admin.dashboard')

@section('content')
<div class="p-6">

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6 bg-primary">
        <h1 class="text-2xl font-bold text-white">Students Management</h1>
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
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
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
                                {{ $student->phone }}<br>{{ $student->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $student->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                    <button @click="open = !open" class="text-gray-500 hover:text-gray-800 focus:outline-none">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                     <div x-show="open" class="absolute right-0 mt-2 w-auto bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                                        <a href="{{ route('admin.students.show', $student->id) }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-gray-100" title="View Student">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-gray-100" title="Edit Student">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete({{ $student->id }})" class="block w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100" title="Delete Student">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
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
</div>
@endsection

<script>
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
</script>
