@extends('layouts.school')

@section('title', 'Students')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">Students</h1>
                <p class="mt-2 text-gray-400">Manage your school's students and their user accounts.</p>
            </div>
            <button onclick="openStudentModal()" 
                    class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-green-500/25">
                <i class="fas fa-plus mr-2"></i>Add Student
            </button>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-2xl shadow-2xl border border-gray-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-black">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">All Students</h2>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search students..." 
                               class="pl-10 pr-4 py-2 bg-gray-800 border border-gray-700 text-white placeholder-gray-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if($students->count() > 0)
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gradient-to-r from-black to-gray-950">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Program</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Class</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">User Account</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gradient-to-br from-gray-900 to-gray-950 divide-y divide-gray-800">
                        @foreach($students as $student)
                            <tr class="hover:bg-gradient-to-r hover:from-gray-800 hover:to-gray-850 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ substr($student->full_name ?: $student->first_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-white">
                                                {{ $student->full_name ?: $student->first_name . ' ' . $student->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-400">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 font-medium">
                                    {{ $student->programType->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 font-medium">
                                    {{ $student->class ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $hasUserAccount = \App\Models\User::where('email', $student->email)->exists();
                                    @endphp
                                    @if($hasUserAccount)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg">
                                            <i class="fas fa-check mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-600 to-gray-700 text-gray-300 shadow-lg">
                                            <i class="fas fa-times mr-1"></i>No Account
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->status === 'active')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="editStudent({{ $student->id }})" 
                                                class="text-blue-400 hover:text-blue-300 p-2 rounded-lg hover:bg-gray-800 transition-all duration-200">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteStudent({{ $student->id }})" 
                                                class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-gray-800 transition-all duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-2xl">
                            <i class="fas fa-users text-3xl text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No students yet</h3>
                    <p class="text-gray-400 mb-6 max-w-md mx-auto">Get started by adding your first student to begin managing your school's enrollment.</p>
                    <button onclick="openStudentModal()" 
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-8 py-3 rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-green-500/25">
                        <i class="fas fa-plus mr-2"></i>Add First Student
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 for modals -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom Dark Theme CSS for SweetAlert2 -->
<style>
.swal2-popup {
    background: linear-gradient(135deg, #111827 0%, #0f172a 100%) !important;
    border: 1px solid #374151 !important;
    border-radius: 1rem !important;
    color: #ffffff !important;
}

.swal2-title {
    color: #ffffff !important;
    font-weight: 600 !important;
}

.swal2-html-container {
    color: #e5e7eb !important;
}

.swal2-input, .swal2-textarea, .swal2-select {
    background: #1f2937 !important;
    border: 2px solid #4b5563 !important;
    border-radius: 0.5rem !important;
    color: #ffffff !important;
    padding: 0.75rem !important;
    font-size: 14px !important;
    font-weight: 500 !important;
}

.swal2-input::placeholder, .swal2-textarea::placeholder {
    color: #9ca3af !important;
    opacity: 0.8 !important;
}

.swal2-input:focus, .swal2-textarea:focus, .swal2-select:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
    outline: none !important;
    background: #111827 !important;
}

.swal2-input:hover, .swal2-textarea:hover, .swal2-select:hover {
    border-color: #6b7280 !important;
    background: #111827 !important;
}

.swal2-confirm {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    border: none !important;
    border-radius: 0.75rem !important;
    padding: 0.75rem 1.5rem !important;
    font-weight: 600 !important;
    box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.25) !important;
}

.swal2-confirm:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.4) !important;
}

.swal2-cancel {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
    border: none !important;
    border-radius: 0.75rem !important;
    padding: 0.75rem 1.5rem !important;
    font-weight: 600 !important;
    color: #ffffff !important;
}

.swal2-cancel:hover {
    background: linear-gradient(135deg, #4b5563 0%, #374151 100%) !important;
    transform: translateY(-1px) !important;
}

.swal2-close {
    color: #9ca3af !important;
}

.swal2-close:hover {
    color: #ffffff !important;
}

.swal2-icon.swal2-success .swal2-success-ring {
    border-color: #10b981 !important;
}

.swal2-icon.swal2-success .swal2-success-fix {
    background-color: #111827 !important;
}

.swal2-icon.swal2-error {
    border-color: #ef4444 !important;
    color: #ef4444 !important;
}

.swal2-validation-message {
    background: #374151 !important;
    color: #fbbf24 !important;
    border: 1px solid #f59e0b !important;
}

label {
    color: #e5e7eb !important;
    font-weight: 500 !important;
    margin-bottom: 0.25rem !important;
    display: block !important;
}

/* Target actual HTML inputs in the modal */
.swal2-html-container input[type="text"],
.swal2-html-container input[type="email"],
.swal2-html-container input[type="tel"],
.swal2-html-container input[type="date"],
.swal2-html-container textarea,
.swal2-html-container select {
    background: #1f2937 !important;
    border: 2px solid #4b5563 !important;
    border-radius: 0.5rem !important;
    color: #ffffff !important;
    padding: 0.75rem !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

.swal2-html-container input[type="text"]::placeholder,
.swal2-html-container input[type="email"]::placeholder,
.swal2-html-container input[type="tel"]::placeholder,
.swal2-html-container textarea::placeholder {
    color: #9ca3af !important;
    opacity: 0.8 !important;
}

.swal2-html-container input[type="text"]:focus,
.swal2-html-container input[type="email"]:focus,
.swal2-html-container input[type="tel"]:focus,
.swal2-html-container input[type="date"]:focus,
.swal2-html-container textarea:focus,
.swal2-html-container select:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
    outline: none !important;
    background: #111827 !important;
}

.swal2-html-container input[type="text"]:hover,
.swal2-html-container input[type="email"]:hover,
.swal2-html-container input[type="tel"]:hover,
.swal2-html-container input[type="date"]:hover,
.swal2-html-container textarea:hover,
.swal2-html-container select:hover {
    border-color: #6b7280 !important;
    background: #111827 !important;
}

.swal2-html-container select option {
    background: #1f2937 !important;
    color: #ffffff !important;
}
</style>

<script>
// CSRF Token setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Open Student Modal
function openStudentModal(studentId = null) {
    const isEdit = studentId !== null;
    
    let formFields = `
        <div class="text-left space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" id="first_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                    <input type="text" id="last_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                <input type="text" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program *</label>
                <select id="program_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="">Select Program</option>
                    @foreach($programTypes as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                    <input type="text" id="class" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select id="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <input type="date" id="date_of_birth" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea id="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <input type="text" id="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Contact</label>
                <input type="text" id="parent_contact" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            ${!isEdit ? '<div class="bg-blue-50 p-3 rounded-md"><p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-1"></i> A user account will be created automatically with password: <strong>student123</strong></p></div>' : ''}
        </div>
    `;

    Swal.fire({
        title: isEdit ? 'Edit Student' : 'Add New Student',
        html: formFields,
        width: 700,
        showCancelButton: true,
        confirmButtonText: isEdit ? 'Update Student' : 'Add Student',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#7c3aed',
        preConfirm: () => {
            const formData = {
                first_name: document.getElementById('first_name').value.trim(),
                last_name: document.getElementById('last_name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                program_type_id: document.getElementById('program_type_id').value,
                class: document.getElementById('class').value.trim(),
                gender: document.getElementById('gender').value,
                date_of_birth: document.getElementById('date_of_birth').value,
                address: document.getElementById('address').value.trim(),
                city: document.getElementById('city').value.trim(),
                parent_contact: document.getElementById('parent_contact').value.trim(),
            };

            // Validation
            if (!formData.first_name || !formData.last_name || !formData.email || !formData.phone || !formData.program_type_id) {
                Swal.showValidationMessage('Please fill in all required fields');
                return false;
            }

            return formData;
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

    // If editing, load student data
    if (isEdit) {
        loadStudentData(studentId);
    }
}

// Load student data for editing
function loadStudentData(studentId) {
    fetch(`/school/students/${studentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const student = data.student;
                document.getElementById('first_name').value = student.first_name || '';
                document.getElementById('last_name').value = student.last_name || '';
                document.getElementById('email').value = student.email || '';
                document.getElementById('phone').value = student.phone || '';
                document.getElementById('program_type_id').value = student.program_type_id || '';
                document.getElementById('class').value = student.class || '';
                document.getElementById('gender').value = student.gender || '';
                document.getElementById('date_of_birth').value = student.date_of_birth || '';
                document.getElementById('address').value = student.address || '';
                document.getElementById('city').value = student.city || '';
                document.getElementById('parent_contact').value = student.parent_contact || '';
            }
        })
        .catch(error => {
            console.error('Error loading student data:', error);
        });
}

// Create new student
function createStudent(formData) {
    fetch('{{ route("school.students.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Always return to first page to see the newly created student
                window.location.href = window.location.pathname;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while creating the student.'
        });
    });
}

// Edit student
function editStudent(studentId) {
    openStudentModal(studentId);
}

// Update student
function updateStudent(studentId, formData) {
    fetch(`/school/students/${studentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Always return to first page after updating
                window.location.href = window.location.pathname;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating the student.'
        });
    });
}

// Delete student
function deleteStudent(studentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the student and their user account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!'
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
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Always return to first page after deletion
                        window.location.href = window.location.pathname;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the student.'
                });
            });
        }
    });
}
</script>
@endsection
