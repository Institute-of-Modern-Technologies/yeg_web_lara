@extends('layouts.school')

@section('title', 'Student Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Student Management</h1>
            <p class="text-gray-400">Manage your school's students and their accounts</p>
        </div>
        <button id="addStudentBtn" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Add New Student
        </button>
    </div>

    <!-- Students Table -->
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-700/30 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $student->full_name }}</div>
                                    <div class="text-sm text-gray-400">{{ $student->city }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $student->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $student->phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/50 text-blue-300 border border-blue-700">
                                {{ $student->programType->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $student->age ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->status === 'active' ? 'bg-green-900/50 text-green-300 border border-green-700' : 'bg-gray-900/50 text-gray-300 border border-gray-700' }}">
                                {{ ucfirst($student->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="editStudent({{ $student->id }})" 
                                        class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteStudent({{ $student->id }})" 
                                        class="text-red-400 hover:text-red-300 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg">No students found</p>
                                <p class="text-sm">Add your first student to get started</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
    <div class="mt-6">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
let currentStudentId = null;

// Add Student Modal
document.getElementById('addStudentBtn').addEventListener('click', function() {
    showStudentModal();
});

function showStudentModal(studentId = null) {
    currentStudentId = studentId;
    const isEdit = studentId !== null;
    
    Swal.fire({
        title: isEdit ? 'Edit Student' : 'Add New Student',
        html: `
            <form id="studentForm" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Phone</label>
                        <input type="tel" id="phone" name="phone" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">City</label>
                        <input type="text" id="city" name="city" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Program Type</label>
                        <select id="program_type_id" name="program_type_id" required
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select Program</option>
                            @foreach($programTypes as $programType)
                            <option value="{{ $programType->id }}">{{ $programType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: isEdit ? 'Update Student' : 'Add Student',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        background: '#1f2937',
        color: '#f9fafb',
        customClass: {
            popup: 'swal-wide'
        },
        preConfirm: () => {
            const form = document.getElementById('studentForm');
            const formData = new FormData(form);
            
            // Basic validation
            const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'city', 'date_of_birth', 'program_type_id'];
            for (let field of requiredFields) {
                if (!formData.get(field)) {
                    Swal.showValidationMessage(`${field.replace('_', ' ')} is required`);
                    return false;
                }
            }
            
            return Object.fromEntries(formData);
        }
    }).then((result) => {
        if (result.isConfirmed) {
            if (isEdit) {
                updateStudent(studentId, result.value);
            } else {
                createStudent(result.value);
            }
        }
    });
    
    // Load student data if editing
    if (isEdit) {
        loadStudentData(studentId);
    }
}

function loadStudentData(studentId) {
    fetch(`/school/students/${studentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.student) {
                const student = data.student;
                document.getElementById('first_name').value = student.first_name || '';
                document.getElementById('last_name').value = student.last_name || '';
                document.getElementById('email').value = student.email || '';
                document.getElementById('phone').value = student.phone || '';
                document.getElementById('city').value = student.city || '';
                document.getElementById('date_of_birth').value = student.date_of_birth || '';
                document.getElementById('program_type_id').value = student.program_type_id || '';
            }
        })
        .catch(error => {
            console.error('Error loading student data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load student data.',
                background: '#1f2937',
                color: '#f9fafb',
                confirmButtonColor: '#ef4444'
            });
        });
}

function createStudent(data) {
    fetch('/school/students', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                background: '#1f2937',
                color: '#f9fafb',
                confirmButtonColor: '#10b981'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.error || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Failed to create student. Please try again.',
            background: '#1f2937',
            color: '#f9fafb',
            confirmButtonColor: '#ef4444'
        });
    });
}

function updateStudent(studentId, data) {
    fetch(`/school/students/${studentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                background: '#1f2937',
                color: '#f9fafb',
                confirmButtonColor: '#10b981'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.error || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Failed to update student. Please try again.',
            background: '#1f2937',
            color: '#f9fafb',
            confirmButtonColor: '#ef4444'
        });
    });
}

function editStudent(studentId) {
    showStudentModal(studentId);
}

function deleteStudent(studentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the student and their user account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        background: '#1f2937',
        color: '#f9fafb'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/school/students/${studentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        background: '#1f2937',
                        color: '#f9fafb',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.error || 'Unknown error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Failed to delete student. Please try again.',
                    background: '#1f2937',
                    color: '#f9fafb',
                    confirmButtonColor: '#ef4444'
                });
            });
        }
    });
}
</script>

<style>
.swal-wide {
    width: 600px !important;
}
</style>
@endsection
