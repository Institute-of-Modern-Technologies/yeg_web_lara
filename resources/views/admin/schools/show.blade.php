@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <!-- Back button and heading -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.schools.index') }}" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-300 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Back to Schools</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">School Details</h1>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.schools.edit', $school->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300 flex items-center justify-center w-full sm:w-auto">
                <i class="fas fa-edit mr-2"></i>
                <span>Edit School</span>
            </a>
            <form action="{{ route('admin.schools.destroy', $school->id) }}" method="POST" class="delete-form" data-name="{{ $school->name }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-700 transition-all duration-300 flex items-center justify-center w-full sm:w-auto">
                    <i class="fas fa-trash-alt mr-2"></i>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- School Details Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-primary to-red-800 text-white">
            <h2 class="text-xl font-semibold">
                <i class="fas fa-school mr-2"></i>
                <span>{{ $school->name }}</span>
            </h2>
        </div>
        
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- School Logo -->
                <div class="flex-shrink-0 flex flex-col items-center">
                    <div class="w-48 h-48 mb-3 rounded-lg overflow-hidden shadow-lg border border-gray-200">
                        @if($school->logo)
                            <x-image src="storage/{{ $school->logo }}" alt="{{ $school->name }} Logo" class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-school text-gray-400 text-5xl"></i>
                            </div>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        @if($school->status == 'approved') bg-green-100 text-green-800
                        @elseif($school->status == 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($school->status == 'approved') 
                            <i class="fas fa-check-circle mr-1"></i> 
                        @elseif($school->status == 'rejected') 
                            <i class="fas fa-times-circle mr-1"></i>
                        @else 
                            <i class="fas fa-clock mr-1"></i>
                        @endif
                        {{ ucfirst($school->status) }}
                    </span>
                </div>
                
                <!-- School Information -->
                <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Contact Information</h3>
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-primary w-5 mr-2"></i>
                                <span>{{ $school->email ?? 'No email provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-primary w-5 mr-2"></i>
                                <span>{{ $school->phone }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-primary w-5 mr-2"></i>
                                <span>{{ $school->location }}</span>
                            </div>
                            @if($school->gps_coordinates)
                            <div class="flex items-center">
                                <i class="fas fa-map-pin text-primary w-5 mr-2"></i>
                                <span>{{ $school->gps_coordinates }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">School Details</h3>
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-user text-primary w-5 mr-2"></i>
                                <span><strong>Owner/Contact:</strong> {{ $school->owner_name }}</span>
                            </div>
                            @if($school->avg_students)
                            <div class="flex items-center">
                                <i class="fas fa-users text-primary w-5 mr-2"></i>
                                <span><strong>Average Students:</strong> {{ $school->avg_students }}</span>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-primary w-5 mr-2"></i>
                                <span><strong>Registered:</strong> {{ $school->created_at->format('F j, Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-primary w-5 mr-2"></i>
                                <span><strong>Last Updated:</strong> {{ $school->updated_at->format('F j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students from this school -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-primary to-red-800 text-white">
            <h2 class="text-xl font-semibold">
                <i class="fas fa-user-graduate mr-2"></i>
                <span>Registered Students ({{ $students->total() }})</span>
            </h2>
        </div>

        @if($students->isEmpty())
            <div class="p-6 text-center">
                <p class="text-gray-500">No students registered from this school yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Registration #
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Info
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $student->full_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $student->gender }} | {{ $student->age }} years
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->registration_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->phone ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->email ?? 'No email' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->class }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($student->status == 'active') bg-green-100 text-green-800
                                        @elseif($student->status == 'inactive') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.students.show', $student->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>

<!-- JavaScript for confirmations -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const schoolName = this.getAttribute('data-name');
                
                if (confirm(`Are you sure you want to delete "${schoolName}"? This action cannot be undone.`)) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
