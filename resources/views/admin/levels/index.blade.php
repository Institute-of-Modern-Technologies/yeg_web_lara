@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Levels Setup</h1>
        <button type="button" id="openLevelModal" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Level</span>
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                </div>
                <div>
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                </div>
                <div>
                    <p class="font-bold">Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Activities</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($levels as $level)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left">{{ $level->name }}</td>
                            <td class="py-3 px-6 text-left">
                                <span class="px-2 py-1 rounded-full text-xs {{ $level->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($level->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($level->activities as $activity)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                            {{ $activity->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 italic">No activities assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3 px-6 text-right">
                                <div class="flex item-center justify-end gap-2">
                                    <button class="editLevelBtn text-blue-500 hover:text-blue-700" 
                                            data-id="{{ $level->id }}" 
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700" 
                                            onclick="confirmDelete({{ $level->id }}, '{{ $level->name }}')" 
                                            title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $level->id }}" action="{{ route('admin.levels.destroy', $level->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 px-6 text-center text-gray-500">No levels found. Create your first level!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Level Modal -->
<div id="levelModal" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div id="modalOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
    
    <!-- Modal container with flexbox centering -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <!-- Modal panel -->
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-auto overflow-hidden">
            <!-- Modal header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add New Level</h3>
            </div>
            
            <!-- Modal body -->
            <div class="p-6">
                <form id="levelForm" method="POST" action="{{ route('admin.levels.store') }}">
                    @csrf
                    <input type="hidden" id="levelId">
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Level Name</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary border-gray-300" 
                               id="name" name="name" required>
                        <p class="mt-1 text-sm text-red-600 hidden" id="nameError"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary border-gray-300">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <p class="mt-1 text-sm text-red-600 hidden" id="statusError"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3"
                                 class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary border-gray-300"></textarea>
                        <p class="mt-1 text-sm text-red-600 hidden" id="descriptionError"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activities</label>
                        <div class="max-h-44 overflow-y-auto border rounded-md p-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($activities as $activity)
                                    <div class="flex items-center">
                                        <input id="activity-{{ $activity->id }}" 
                                               name="activities[]" 
                                               type="checkbox" 
                                               value="{{ $activity->id }}"
                                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <label for="activity-{{ $activity->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $activity->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-red-600 hidden" id="activitiesError"></p>
                    </div>
                </form>
            </div>
            
            <!-- Modal footer -->
            <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                <button type="button" id="cancelLevelBtn" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="button" id="saveLevelBtn" class="px-4 py-2 bg-primary border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Save Level
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add SweetAlert2 and script handlers -->
<script type="text/javascript">
    // Delete confirmation
    function confirmDelete(id, name) {
        if (typeof Swal === 'undefined') {
            // Fallback to basic confirmation if SweetAlert2 is not available
            if (confirm(`Are you sure you want to delete ${name}? This action cannot be undone.`)) {
                document.getElementById(`delete-form-${id}`).submit();
            }
            return;
        }
        
        // Use SweetAlert2 for confirmation
        Swal.fire({
            title: 'Delete Level',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

    // Modal handling
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('levelModal');
        const overlay = document.getElementById('modalOverlay');
        const openModalBtn = document.getElementById('openLevelModal');
        const cancelBtn = document.getElementById('cancelLevelBtn');
        const saveBtn = document.getElementById('saveLevelBtn');
        const levelForm = document.getElementById('levelForm');
        const modalTitle = document.getElementById('modalTitle');
        
        let editMode = false;
        
        // Open modal for creating a new level
        openModalBtn.addEventListener('click', function() {
            resetForm();
            editMode = false;
            modalTitle.textContent = 'Add New Level';
            levelForm.action = "{{ route('admin.levels.store') }}";
            modal.classList.remove('hidden');
            document.getElementById('name').focus();
        });
        
        // Edit level
        document.querySelectorAll('.editLevelBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                const levelId = this.getAttribute('data-id');
                editMode = true;
                modalTitle.textContent = 'Edit Level';
                
                // Fetch level details via AJAX
                fetch(`{{ url('admin/levels') }}/${levelId}/edit`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const level = data.level;
                        
                        // Set form values
                        document.getElementById('levelId').value = level.id;
                        document.getElementById('name').value = level.name;
                        document.getElementById('status').value = level.status;
                        document.getElementById('description').value = level.description || '';
                        
                        // Update form action
                        levelForm.action = `{{ url('admin/levels') }}/${levelId}`;
                        levelForm.insertAdjacentHTML('afterbegin', `<input type="hidden" name="_method" value="PUT">`);
                        
                        // Check appropriate activities
                        document.querySelectorAll('input[name="activities[]"]').forEach(checkbox => {
                            checkbox.checked = level.activities.some(a => a.id == checkbox.value);
                        });
                        
                        // Show modal
                        modal.classList.remove('hidden');
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to load level data',
                            icon: 'error',
                            confirmButtonColor: '#950713'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#950713'
                    });
                });
            });
        });
        
        // Close modal
        function closeModal() {
            modal.classList.add('hidden');
            resetForm();
        }
        
        function resetForm() {
            levelForm.reset();
            const methodInput = levelForm.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            
            // Hide all error messages
            document.querySelectorAll('p[id$="Error"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
        
        cancelBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);
        
        // Submit form
        saveBtn.addEventListener('click', function() {
            // Simple client-side validation
            let isValid = true;
            const nameInput = document.getElementById('name');
            const nameError = document.getElementById('nameError');
            
            if (!nameInput.value.trim()) {
                nameError.textContent = 'Level name is required';
                nameError.classList.remove('hidden');
                isValid = false;
            } else {
                nameError.classList.add('hidden');
            }
            
            if (!isValid) return;
            
            // Submit the form via AJAX
            const formData = new FormData(levelForm);
            
            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            
            fetch(levelForm.action, {
                method: editMode ? 'POST' : 'POST', // POST always works with _method hidden field
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: data.message || (editMode ? 'Level has been updated successfully' : 'Level has been created successfully'),
                        icon: 'success',
                        confirmButtonColor: '#950713'
                    }).then(() => {
                        // Reload the page to show changes
                        window.location.reload();
                    });
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorEl = document.getElementById(key + 'Error');
                            if (errorEl) {
                                errorEl.textContent = data.errors[key][0];
                                errorEl.classList.remove('hidden');
                            }
                        });
                    } else {
                        // Generic error
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#950713'
                        });
                    }
                    
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = editMode ? 'Update Level' : 'Save Level';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#950713'
                });
                saveBtn.disabled = false;
                saveBtn.innerHTML = editMode ? 'Update Level' : 'Save Level';
            });
        });
    });
</script>
@endsection
