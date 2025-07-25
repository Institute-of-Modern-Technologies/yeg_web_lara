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
                                                                    <button type="button" class="text-blue-600 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 open-payment-modal" data-student-id="{{ $student->id }}" data-student-name="{{ $student->first_name }} {{ $student->last_name }}">
                                                                        <i class="fas fa-money-bill-wave mr-2"></i> Record Payment
                                                                    </button>
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
@end@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Receipt Modal -->
<div id="receiptModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 max-w-4xl">
        <div class="bg-white rounded-lg shadow-xl">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-gray-100 py-3 px-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-gray-900">Payment Receipt</h3>
                <button type="button" class="close-receipt-modal text-gray-400 hover:text-gray-500" onclick="closeReceiptModal()">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <!-- Modal Body - Receipt Content -->
            <div id="receipt-content" class="p-4">
                <!-- Receipt will be loaded here via AJAX -->
                <div class="flex justify-center items-center py-12">
                    <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-primary" role="status">
                        <span class="hidden">Loading...</span>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer - Action Buttons -->
            <div class="bg-gray-100 px-4 py-3 flex flex-wrap gap-3 justify-center md:justify-end rounded-b-lg">
                <!-- Print Button -->
                <button id="print-receipt" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-print mr-2"></i> Print Receipt
                </button>
                
                <!-- Send via WhatsApp Button -->
                <button id="whatsapp-receipt" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fab fa-whatsapp mr-2"></i> Send via WhatsApp
                </button>
                
                <!-- Send via Email Button -->
                <button id="email-receipt" class="bg-primary hover:bg-red-800 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-envelope mr-2"></i> Send via Email
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/receipt-modal.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment form submission handler
        const paymentForm = document.getElementById('paymentForm');
        
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                // Show processing state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                submitButton.disabled = true;
                
                // Submit payment data via AJAX
                fetch('/admin/payments/store', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    
                    if (data.success) {
                        // Close payment modal
                        document.getElementById('paymentModal').classList.add('hidden');
                        
                        const paymentId = data.payment_id;
                        console.log('Payment ID:', paymentId); // Debug log
                        
                        // Show success message with View Receipt option
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Recorded',
                            text: data.message || 'Payment has been successfully recorded',
                            confirmButtonColor: '#950713',
                            showCancelButton: true,
                            cancelButtonText: 'Close',
                            confirmButtonText: 'View Receipt',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            timer: null, // Prevent auto-closing
                            timerProgressBar: false
                        }).then((result) => {
                            if (result.isConfirmed && typeof openReceiptModal === 'function') {
                                // Open receipt modal
                                console.log('Opening receipt for payment ID:', paymentId); // Debug log
                                openReceiptModal(paymentId);
                            } else {
                                // Reload page to reflect changes
                                window.location.reload();
                            }
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'There was an error recording the payment',
                            confirmButtonColor: '#950713'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Reset button state
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error processing your request.',
                        confirmButtonColor: '#950713'
                    });
                });
            });
        }
        
        // Add event listeners to close receipt modal buttons
        const closeButtons = document.querySelectorAll('.close-receipt-modal');
        if (closeButtons.length > 0) {
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    closeReceiptModal();
                });
            });
        }
    });
</script>

<!-- Receipt Modal -->
<div id="receiptModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 max-w-4xl">
        <div class="bg-white rounded-lg shadow-xl">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-gray-100 py-3 px-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-gray-900">Payment Receipt</h3>
                <button type="button" class="close-receipt-modal text-gray-400 hover:text-gray-500" onclick="closeReceiptModal()">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <!-- Modal Body - Receipt Content -->
            <div id="receipt-content" class="p-4">
                <!-- Receipt will be loaded here via AJAX -->
                <div class="flex justify-center items-center py-12">
                    <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-primary" role="status">
                        <span class="hidden">Loading...</span>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer - Action Buttons -->
            <div class="bg-gray-100 px-4 py-3 flex flex-wrap gap-3 justify-center md:justify-end rounded-b-lg">
                <!-- Print Button -->
                <button id="print-receipt" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-print mr-2"></i> Print Receipt
                </button>
                
                <!-- Send via WhatsApp Button -->
                <button id="whatsapp-receipt" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fab fa-whatsapp mr-2"></i> Send via WhatsApp
                </button>
                
                <!-- Send via Email Button -->
                <button id="email-receipt" class="bg-primary hover:bg-red-800 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-envelope mr-2"></i> Send via Email
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/receipt-modal.js') }}"></script>

<!-- Custom Success Modal -->
<div id="custom-success-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Success content -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Success icon -->
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <!-- Modal text -->
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Payment Successful
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="success-message">
                                Payment has been successfully recorded
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Action buttons -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="view-receipt-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    View Receipt
                </button>
                <button type="button" id="close-success-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
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

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-gradient-to-r from-primary to-red-800 px-4 py-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Record Cash Payment</h3>
                    <button type="button" class="text-white hover:text-gray-200 close-payment-modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <form id="paymentForm">
                @csrf
                <input type="hidden" name="student_id" id="payment_student_id">
                <input type="hidden" name="payment_method" value="cash">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="student-info mb-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-700">Student: <span id="student_name_display" class="font-bold"></span></p>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₵)</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₵</span>
                                </div>
                                <input type="number" name="amount" id="amount" step="0.01" required class="focus:ring-primary focus:border-primary block w-full pl-8 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                            </div>
                        </div>
                        
                        <!-- Discount -->
                        <div>
                            <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Discount (₵)</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₵</span>
                                </div>
                                <input type="number" name="discount" id="discount" step="0.01" value="0" class="focus:ring-primary focus:border-primary block w-full pl-8 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                            </div>
                        </div>
                        
                        <!-- Final Amount (calculated automatically) -->
                        <div>
                            <label for="final_amount" class="block text-sm font-medium text-gray-700 mb-1">Final Amount (₵)</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₵</span>
                                </div>
                                <input type="number" name="final_amount" id="final_amount" step="0.01" readonly class="bg-gray-50 focus:ring-primary focus:border-primary block w-full pl-8 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                            </div>
                        </div>
                        
                        <!-- Information Note -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        A receipt number will be automatically generated and the payment will be marked as completed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields -->
                        <input type="hidden" name="status" value="completed">
                        <input type="hidden" name="payment_method" value="cash">
                        
                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Additional information about this payment"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-save mr-2"></i> Save Payment
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm close-payment-modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Modal Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const paymentModal = document.getElementById('paymentModal');
        const paymentForm = document.getElementById('paymentForm');
        const openButtons = document.querySelectorAll('.open-payment-modal');
        const closeButtons = document.querySelectorAll('.close-payment-modal');
        const studentIdInput = document.getElementById('payment_student_id');
        const studentNameDisplay = document.getElementById('student_name_display');
        
        // Amount calculation fields
        const amountInput = document.getElementById('amount');
        const discountInput = document.getElementById('discount');
        const finalAmountInput = document.getElementById('final_amount');
        
        // Calculate final amount when amount or discount changes
        function calculateFinalAmount() {
            const amount = parseFloat(amountInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;
            const finalAmount = Math.max(0, amount - discount);
            finalAmountInput.value = finalAmount.toFixed(2);
        }
        
        amountInput.addEventListener('input', calculateFinalAmount);
        discountInput.addEventListener('input', calculateFinalAmount);
        
        // Open payment modal
        openButtons.forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.dataset.studentId;
                const studentName = this.dataset.studentName;
                
                studentIdInput.value = studentId;
                studentNameDisplay.textContent = studentName;
                
                // Reset form
                paymentForm.reset();
                calculateFinalAmount();
                
                // Show modal
                paymentModal.classList.remove('hidden');
            });
        });
        
        // Close payment modal
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                paymentModal.classList.add('hidden');
            });
        });
        
        // Handle form submission
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show processing state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            submitButton.disabled = true;
            
            // Submit payment data via AJAX
            fetch('/admin/payments/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
                
                if (data.success) {
                    // Close payment modal
                    paymentModal.classList.add('hidden');
                    
                    const paymentId = data.payment_id;
                    
                    // Get the custom success modal elements
                    const successModal = document.getElementById('custom-success-modal');
                    const closeSuccessBtn = document.getElementById('close-success-modal');
                    const viewReceiptBtn = document.getElementById('view-receipt-btn');
                    const successMessage = document.getElementById('success-message');
                    
                    // Set the success message
                    successMessage.textContent = data.message || 'Payment has been successfully recorded';
                    
                    // Store payment ID in view receipt button data attribute
                    viewReceiptBtn.setAttribute('data-payment-id', paymentId);
                    
                    // Show the success modal
                    successModal.classList.remove('hidden');
                    
                    // Handle view receipt button click
                    viewReceiptBtn.onclick = function() {
                        // Hide success modal
                        successModal.classList.add('hidden');
                        // Open receipt modal
                        openReceiptModal(paymentId);
                    };
                    
                    // Handle close button click
                    closeSuccessBtn.onclick = function() {
                        // Hide success modal
                        successModal.classList.add('hidden');
                        // Reload page to reflect changes
                        window.location.reload();
                    };
                } else {
                    // Get the custom success modal elements
                    const successModal = document.getElementById('custom-success-modal');
                    const closeSuccessBtn = document.getElementById('close-success-modal');
                    const viewReceiptBtn = document.getElementById('view-receipt-btn');
                    const successMessage = document.getElementById('success-message');
                    
                    // Hide view receipt button for errors
                    viewReceiptBtn.classList.add('hidden');
                    
                    // Update modal title and message for error
                    document.querySelector('#modal-title').textContent = 'Error';
                    successMessage.textContent = data.message || 'An error occurred while processing payment';
                    
                    // Show the modal
                    successModal.classList.remove('hidden');
                    
                    // Handle close button click
                    closeSuccessBtn.onclick = function() {
                        // Hide success modal
                        successModal.classList.add('hidden');
                        // Restore view receipt button visibility for future use
                        viewReceiptBtn.classList.remove('hidden');
                    };
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Reset button state
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;

                // Get the custom success modal elements
                const successModal = document.getElementById('custom-success-modal');
                const closeSuccessBtn = document.getElementById('close-success-modal');
                const viewReceiptBtn = document.getElementById('view-receipt-btn');
                const successMessage = document.getElementById('success-message');
                
                // Hide view receipt button for errors
                viewReceiptBtn.classList.add('hidden');
                
                // Update modal title and message for error
                document.querySelector('#modal-title').textContent = 'Error';
                successMessage.textContent = 'There was a network error processing the payment. Please try again.';
                
                // Show the modal
                successModal.classList.remove('hidden');
                
                // Handle close button click
                closeSuccessBtn.onclick = function() {
                    // Hide success modal
                    successModal.classList.add('hidden');
                    // Restore view receipt button visibility for future use
                    viewReceiptBtn.classList.remove('hidden');
                };

                console.error('Error:', error);
            });
        });
    });
</script>
