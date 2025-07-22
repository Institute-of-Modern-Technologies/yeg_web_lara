@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Activities Setup</h1>
        <button type="button" id="openActivityModal" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Activity</span>
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Activities List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-tasks mr-2 text-primary"></i>
                <span>Activities</span>
            </h2>
        </div>

        <div class="p-6">
            @if($activities->isEmpty())
            <div class="text-center text-gray-500">
                <p>No activities found. Click "Add New Activity" to create one.</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Created At</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $activity->id }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $activity->name }}</td>
                            <td class="px-6 py-4">{{ $activity->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.activities.edit', $activity->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-md flex items-center">
                                        <i class="fas fa-edit mr-1"></i>
                                        <span class="text-sm">Edit</span>
                                    </a>
                                    <button type="button" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors" onclick="confirmDelete({{ $activity->id }}, '{{ addslashes($activity->name) }}')">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        <span class="text-sm">Delete</span>
                                    </button>
                                    <form id="delete-form-{{ $activity->id }}" action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Activity Modal -->
<div id="activityModal" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div id="modalOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
    
    <!-- Modal container with flexbox centering -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <!-- Modal panel -->
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-auto overflow-hidden">
            <!-- Modal header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Add New Activity</h3>
            </div>
            
            <!-- Modal body -->
            <div class="p-6">
                <form id="activityForm" method="POST" action="{{ route('admin.activities.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Activity Name</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary border-gray-300" 
                               id="name" name="name" required>
                        <p class="mt-1 text-sm text-red-600 hidden" id="nameError"></p>
                    </div>
                </form>
            </div>
            
            <!-- Modal footer -->
            <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                <button type="button" id="cancelActivityBtn" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="button" id="saveActivityBtn" class="px-4 py-2 bg-primary border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Save Activity
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add SweetAlert2 deletion handler script -->
<script type="text/javascript">
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
            title: 'Delete Activity',
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
        const modal = document.getElementById('activityModal');
        const overlay = document.getElementById('modalOverlay');
        const openModalBtn = document.getElementById('openActivityModal');
        const cancelBtn = document.getElementById('cancelActivityBtn');
        const saveBtn = document.getElementById('saveActivityBtn');
        const activityForm = document.getElementById('activityForm');
        
        // Open modal
        openModalBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
            document.getElementById('name').focus();
        });
        
        // Close modal
        function closeModal() {
            modal.classList.add('hidden');
            document.getElementById('name').value = '';
            document.getElementById('nameError').classList.add('hidden');
        }
        
        cancelBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);
        
        // Submit form
        saveBtn.addEventListener('click', function() {
            const nameInput = document.getElementById('name');
            const nameError = document.getElementById('nameError');
            
            // Simple validation
            if (!nameInput.value.trim()) {
                nameError.textContent = 'Activity name is required';
                nameError.classList.remove('hidden');
                return;
            }
            
            // Submit the form via AJAX
            const formData = new FormData(activityForm);
            
            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            
            fetch(activityForm.action, {
                method: 'POST',
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
                        text: data.message || 'Activity has been created successfully',
                        icon: 'success',
                        confirmButtonColor: '#950713'
                    }).then(() => {
                        // Reload the page to show the new activity
                        window.location.reload();
                    });
                } else {
                    // Show error message
                    nameError.textContent = data.errors?.name || 'Something went wrong';
                    nameError.classList.remove('hidden');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = 'Save Activity';
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
                saveBtn.innerHTML = 'Save Activity';
            });
        });
    });
</script>
@endsection
