@extends('admin.dashboard')

@section('content')
    <div class="container px-6 mx-auto grid">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center my-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                <i class="fas fa-layer-group text-[#950713] mr-2"></i> Stages Management
            </h2>
            
            <!-- Primary Add Button -->
            <button id="addStageBtn" 
                    class="px-5 py-2.5 font-medium text-white bg-[#950713] rounded-lg shadow-md hover:bg-[#850612] focus:outline-none focus:ring-2 focus:ring-[#950713] focus:ring-opacity-50 transition-all ease-in-out duration-300 flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Stage
            </button>
        </div>

        <!-- Information Banner -->
        <div class="p-4 mb-6 text-sm bg-blue-50 rounded-lg border-l-4 border-blue-500">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">About Stages</h3>
                    <div class="text-blue-700 mt-1">
                        Stages are used to organize activities at IMT. Each stage can have multiple activities assigned to it.
                    </div>
                </div>
            </div>
        </div>

        <!-- Stages List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Card Header with Search -->
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="text-lg font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-list text-[#950713] mr-2"></i> All Stages
                </h3>
                <div class="relative">
                    <input type="text" id="stageSearchInput" placeholder="Search stages..." 
                          class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#950713] focus:border-[#950713]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3 w-12">
                                <span class="flex items-center">
                                    <span>#</span>
                                    <i class="fas fa-sort-amount-down ml-1 text-gray-400" title="Drag rows to reorder stages"></i>
                                </span>
                            </th>
                            <th class="px-4 py-3">Name & Description</th>
                            <th class="px-4 py-3 w-24">Status</th>
                            <th class="px-4 py-3">Activities</th>
                            <th class="px-4 py-3 w-24 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" id="stages-table-body">
                        @forelse($stages as $index => $stage)
                            <tr class="text-gray-700 hover:bg-gray-50 transition-colors" data-stage-id="{{ $stage->id }}">
                                <!-- Order Number Column -->
                                <td class="px-4 py-3 text-sm border-r border-gray-100">
                                    <div class="flex items-center">
                                        <span class="stage-order font-semibold">{{ $index + 1 }}</span>
                                        <button class="ml-2 text-gray-400 handle cursor-move hover:text-[#950713]" title="Drag to reorder">
                                            <i class="fas fa-grip-vertical"></i>
                                        </button>
                                    </div>
                                </td>
                                
                                <!-- Name & Description Column -->
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <h4 class="font-semibold text-gray-800">{{ $stage->name }}</h4>
                                        @if($stage->description)
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($stage->description, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Status Column -->
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex flex-col items-start gap-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full w-16 text-center
                                            {{ $stage->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($stage->status) }}
                                        </span>
                                        <button data-stage-id="{{ $stage->id }}" 
                                            class="toggle-status-btn text-xs px-2 py-1 rounded-md flex items-center 
                                            {{ $stage->status === 'active' ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }}">
                                            <i class="fas {{ $stage->status === 'active' ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-1"></i>
                                            {{ $stage->status === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </div>
                                </td>
                                
                                <!-- Activities Column -->
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($stage->activities as $activity)
                                            <span class="px-2 py-1 text-xs rounded-md bg-gray-100 border border-gray-200 text-gray-700">
                                                {{ $activity->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400 italic">No activities assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                
                                <!-- Actions Column -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center space-x-3">
                                        <button data-stage-id="{{ $stage->id }}" 
                                                class="edit-stage-btn p-1.5 rounded-lg text-[#950713] hover:bg-[#950713]/10 transition-colors"
                                                title="Edit Stage">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button data-stage-id="{{ $stage->id }}" 
                                                class="delete-stage-btn p-1.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                                                title="Delete Stage">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-layer-group text-4xl mb-3 opacity-30"></i>
                                        <p class="text-lg font-medium">No stages found</p>
                                        <p class="text-sm mt-1">Click the "Add New Stage" button to create one</p>
                                        <button id="emptyAddStageBtn" 
                                                class="mt-4 px-4 py-2 bg-[#950713] text-white rounded-lg hover:bg-[#850612] transition-colors">
                                            <i class="fas fa-plus mr-2"></i> Add First Stage
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stage Modal -->
    <div id="stageModal" class="hidden fixed inset-0 overflow-y-auto z-50" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Modal backdrop with animation -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-800 opacity-75"></div>
            </div>
            
            <!-- Modal panel -->
            <div class="bg-white rounded-lg shadow-xl max-w-xl w-full mx-auto z-10 overflow-hidden transform transition-all">
                <!-- Modal header -->
                <div class="bg-gradient-to-r from-[#950713] to-red-800 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white" id="modalTitle">
                        <i class="fas fa-plus-circle mr-2"></i> <span id="modalTitleText">Add New Stage</span>
                    </h3>
                    <button id="closeModalBtnX" type="button" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal body -->
                <div class="p-6">
                    <form id="stageForm" class="space-y-5">
                        @csrf
                        <input type="hidden" id="stageId" name="id" value="">
                        
                        <!-- Name Input with floating label -->
                        <div class="relative">
                            <input type="text" id="stageName" name="name" 
                                class="block w-full px-4 py-2.5 text-gray-800 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:border-[#950713] peer" 
                                placeholder=" " />
                            <label for="stageName" 
                                class="absolute text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-[#950713]">
                                Stage Name <span class="text-red-500">*</span>
                            </label>
                            <span class="text-red-500 text-xs error-message mt-1" id="nameError"></span>
                        </div>
                        
                        <!-- Description Input -->
                        <div class="mt-5">
                            <label for="stageDescription" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="stageDescription" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#950713] focus:ring focus:ring-[#950713] focus:ring-opacity-20"
                                placeholder="Provide a brief description of this stage"></textarea>
                            <span class="text-red-500 text-xs error-message" id="descriptionError"></span>
                        </div>
                        
                        <!-- Row with Status and Order -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                            <!-- Status Select -->
                            <div>
                                <label for="stageStatus" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="stageStatus" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#950713] focus:ring focus:ring-[#950713] focus:ring-opacity-20">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <span class="text-red-500 text-xs error-message" id="statusError"></span>
                            </div>
                            
                            <!-- Order Input -->
                            <div>
                                <label for="stageOrder" class="block text-sm font-medium text-gray-700">Display Order</label>
                                <input type="number" name="order" id="stageOrder" min="0" value="0" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#950713] focus:ring focus:ring-[#950713] focus:ring-opacity-20">
                                <span class="text-red-500 text-xs error-message" id="orderError"></span>
                            </div>
                        </div>
                        
                        <!-- Activities Multi-select -->
                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tasks text-[#950713] mr-2"></i> Assign Activities
                            </label>
                            <div class="max-h-48 overflow-y-auto p-3 border rounded-md bg-gray-50">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($activities as $activity)
                                        <div class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                            <input type="checkbox" name="activities[]" id="activity{{ $activity->id }}" value="{{ $activity->id }}" 
                                                   class="activity-checkbox h-4 w-4 text-[#950713] focus:ring-[#950713] border-gray-300 rounded">
                                            <label for="activity{{ $activity->id }}" class="ml-2 block text-sm text-gray-700 select-none cursor-pointer">
                                                {{ $activity->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @if(count($activities) === 0)
                                    <div class="text-gray-500 text-center py-2">
                                        <i class="fas fa-info-circle mr-1"></i> No activities available
                                    </div>
                                @endif
                            </div>
                            <span class="text-red-500 text-xs error-message mt-1" id="activitiesError"></span>
                        </div>
                    </form>
                </div>
                
                <!-- Modal footer -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-2 sm:justify-between">
                    <div>
                        <button id="saveStageBtn" type="button" 
                                class="w-full sm:w-auto px-4 py-2 bg-[#950713] text-white rounded-lg hover:bg-[#7d0510] focus:outline-none focus:ring-2 focus:ring-[#950713] focus:ring-opacity-50 flex items-center justify-center transition-colors">
                            <i class="fas fa-save mr-2"></i> Save Stage
                        </button>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button id="closeModalBtn" type="button" 
                                class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 flex items-center justify-center transition-colors">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="hidden fixed inset-0 overflow-y-auto z-50" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Modal backdrop with animation -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-800 opacity-75"></div>
            </div>
            
            <!-- Modal panel -->
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-auto z-10 overflow-hidden transform transition-all">
                <!-- Modal header -->
                <div class="bg-red-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white" id="deleteModalTitle">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Delete Stage
                    </h3>
                    <button id="closeDeleteModalBtn" type="button" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal body -->
                <div class="p-6">
                    <p class="text-gray-700">Are you sure you want to delete this stage? This action cannot be undone.</p>
                    <p class="mt-2 font-semibold text-gray-800" id="deleteStageNameDisplay"></p>
                    <input type="hidden" id="deleteStageId">
                </div>
                
                <!-- Modal footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button id="cancelDeleteBtn" type="button"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition-colors">
                        Cancel
                    </button>
                    <button id="confirmDeleteBtn" type="button"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i> Delete Stage
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing Stage Management');
            
            // Modal elements
            const modal = document.getElementById('stageModal');
            const deleteModal = document.getElementById('deleteConfirmModal');
            const addStageBtn = document.getElementById('addStageBtn');
            const emptyAddStageBtn = document.getElementById('emptyAddStageBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const closeModalBtnX = document.getElementById('closeModalBtnX');
            const closeDeleteBtn = document.getElementById('closeDeleteModalBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const saveBtn = document.getElementById('saveStageBtn');
            const stageForm = document.getElementById('stageForm');
            const modalTitle = document.getElementById('modalTitleText');
            
            // Log to check if elements are found
            console.log('Stage modal found:', !!modal);
            console.log('Delete modal found:', !!deleteModal);
            console.log('Add stage button found:', !!addStageBtn);
            console.log('Empty state add button found:', !!emptyAddStageBtn);
            
            // Form elements
            const stageIdInput = document.getElementById('stageId');
            const nameInput = document.getElementById('stageName');
            const statusSelect = document.getElementById('stageStatus');
            const orderInput = document.getElementById('stageOrder');
            const descriptionTextarea = document.getElementById('stageDescription');
            
            // Error elements
            const errorElements = document.querySelectorAll('.error-message');
            
            // Stage table
            const stagesTableBody = document.getElementById('stages-table-body');
            
            // Open modal for creating a new stage
            if (addStageBtn) {
                addStageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Add Stage button clicked');
                    resetForm();
                    modalTitle.textContent = 'Add New Stage';
                    modal.classList.remove('hidden');
                });
            }
            
            // Empty state add button
            if (emptyAddStageBtn) {
                emptyAddStageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Empty state add button clicked');
                    resetForm();
                    modalTitle.textContent = 'Add New Stage';
                    modal.classList.remove('hidden');
                });
            }
            
            // Close modal buttons
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Close modal button clicked');
                    modal.classList.add('hidden');
                });
            }
            
            if (closeModalBtnX) {
                closeModalBtnX.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Close X button clicked');
                    modal.classList.add('hidden');
                });
            }
            
            // Close delete modal buttons
            if (closeDeleteBtn) {
                closeDeleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    deleteModal.classList.add('hidden');
                });
            }
            
            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    deleteModal.classList.add('hidden');
                });
            }
            
            // Close modals with ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                    if (!deleteModal.classList.contains('hidden')) {
                        deleteModal.classList.add('hidden');
                    }
                }
            });
            
            // Reset form and errors
            function resetForm() {
                stageForm.reset();
                stageIdInput.value = '';
                errorElements.forEach(el => el.textContent = '');
                
                // Uncheck all activity checkboxes
                document.querySelectorAll('.activity-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
            
            // Setup edit buttons
            document.querySelectorAll('.edit-stage-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const stageId = this.getAttribute('data-stage-id');
                    console.log('Edit button clicked for stage ID:', stageId);
                    
                    // Fetch stage data
                    fetch(`/admin/stages/${stageId}/edit`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.stage) {
                            const stage = data.stage;
                            
                            // Reset form and clear errors
                            resetForm();
                            
                            // Populate form with stage data
                            stageIdInput.value = stage.id;
                            nameInput.value = stage.name;
                            statusSelect.value = stage.status;
                            orderInput.value = stage.order || 0;
                            descriptionTextarea.value = stage.description || '';
                            
                            // Set activities
                            if (stage.activities) {
                                const activityIds = stage.activities.map(activity => activity.id);
                                document.querySelectorAll('.activity-checkbox').forEach(checkbox => {
                                    checkbox.checked = activityIds.includes(parseInt(checkbox.value));
                                });
                            }
                            
                            // Update modal title and show
                            modalTitle.textContent = 'Edit Stage';
                            modal.classList.remove('hidden');
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to load stage data.',
                                icon: 'error',
                                confirmButtonColor: '#950713'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to load stage data.',
                            icon: 'error',
                            confirmButtonColor: '#950713'
                        });
                    });
                });
            });
            
            // Setup delete buttons
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const deleteStageId = document.getElementById('deleteStageId');
            const deleteStageNameDisplay = document.getElementById('deleteStageNameDisplay');
            
            document.querySelectorAll('.delete-stage-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const stageId = this.getAttribute('data-stage-id');
                    const stageName = this.closest('tr').querySelector('h4').textContent;
                    
                    console.log('Delete button clicked for stage:', stageName);
                    
                    // Set the stage info in the delete confirmation modal
                    deleteStageId.value = stageId;
                    deleteStageNameDisplay.textContent = stageName;
                    
                    // Show delete confirmation modal
                    deleteModal.classList.remove('hidden');
                });
            });
            
            // Handle delete confirmation
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', function() {
                    const stageId = deleteStageId.value;
                    if (!stageId) return;
                    
                    fetch(`/admin/stages/${stageId}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        deleteModal.classList.add('hidden');
                        
                        if (data.success) {
                            // Show success message
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message || 'Stage has been deleted.',
                                icon: 'success',
                                confirmButtonColor: '#950713'
                            }).then(() => {
                                // Remove the row or reload
                                const row = document.querySelector(`tr[data-stage-id="${stageId}"]`);
                                if (row) {
                                    row.remove();
                                    
                                    // If no stages left, reload to show empty state
                                    if (stagesTableBody.querySelectorAll('tr:not([style*="display: none"])').length === 0) {
                                        window.location.reload();
                                    }
                                } else {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to delete stage.',
                                icon: 'error',
                                confirmButtonColor: '#950713'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        deleteModal.classList.add('hidden');
                        
                        Swal.fire({
                            title: 'Error!',
                            text: 'An unexpected error occurred while deleting the stage.',
                            icon: 'error',
                            confirmButtonColor: '#950713'
                        });
                    });
                });
            }
            
            // Save stage (create or update)
            saveBtn.addEventListener('click', function() {
                const formData = new FormData(stageForm);
                const isEditing = stageIdInput.value !== '';
                const url = isEditing 
                    ? `/admin/stages/${stageIdInput.value}` 
                    : '/admin/stages';
                
                if (isEditing) {
                    formData.append('_method', 'PUT');
                }
                
                // Clear previous errors
                errorElements.forEach(el => el.textContent = '');
                
                // Show loading indicator
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
                saveBtn.disabled = true;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Save Stage';
                    saveBtn.disabled = false;
                    
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#950713'
                        }).then(() => {
                            modal.classList.add('hidden');
                            window.location.reload();
                        });
                    } else {
                        if (data.errors) {
                            // Display validation errors
                            Object.keys(data.errors).forEach(field => {
                                const errorEl = document.getElementById(`${field}Error`);
                                if (errorEl) {
                                    errorEl.textContent = data.errors[field][0];
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Something went wrong.',
                                icon: 'error',
                                confirmButtonColor: '#950713'
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Reset button state
                    saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Save Stage';
                    saveBtn.disabled = false;
                    
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonColor: '#950713'
                    });
                });
            });
            
            // Toggle status functionality
            document.querySelectorAll('.toggle-status-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const stageId = this.getAttribute('data-stage-id');
                    const currentStatus = this.classList.contains('text-red-600') ? 'active' : 'inactive';
                    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                    
                    console.log(`Toggle status for stage ${stageId}: ${currentStatus} -> ${newStatus}`);
                    
                    fetch(`/admin/stages/${stageId}/toggle-active`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI without reloading
                            const row = this.closest('tr');
                            const statusBadge = row.querySelector('td:nth-child(3) span');
                            
                            if (newStatus === 'active') {
                                statusBadge.classList.remove('bg-red-100', 'text-red-800');
                                statusBadge.classList.add('bg-green-100', 'text-green-800');
                                statusBadge.textContent = 'Active';
                                
                                this.innerHTML = '<i class="fas fa-toggle-off mr-1"></i> Deactivate';
                                this.classList.remove('text-green-600', 'hover:bg-green-50');
                                this.classList.add('text-red-600', 'hover:bg-red-50');
                            } else {
                                statusBadge.classList.remove('bg-green-100', 'text-green-800');
                                statusBadge.classList.add('bg-red-100', 'text-red-800');
                                statusBadge.textContent = 'Inactive';
                                
                                this.innerHTML = '<i class="fas fa-toggle-on mr-1"></i> Activate';
                                this.classList.remove('text-red-600', 'hover:bg-red-50');
                                this.classList.add('text-green-600', 'hover:bg-green-50');
                            }
                            
                            // Show success toast
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            Toast.fire({
                                icon: 'success',
                                title: data.message || `Stage status changed to ${newStatus}`
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to update stage status.',
                                icon: 'error',
                                confirmButtonColor: '#950713'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An unexpected error occurred while updating stage status.',
                            icon: 'error',
                            confirmButtonColor: '#950713'
                        });
                    });
                });
            });
            
            // Initialize drag & drop sorting with SortableJS
            if (stagesTableBody) {
                // Check if there are any stages to sort
                const hasStages = stagesTableBody.querySelectorAll('tr[data-stage-id]').length > 0;
                
                if (hasStages) {
                    new Sortable(stagesTableBody, {
                        handle: '.handle',
                        animation: 150,
                        ghostClass: 'bg-gray-100',
                        onEnd: function(evt) {
                            // Update the order numbers visually
                            updateOrderNumbers();
                            
                            // Get the new order of stages
                            const stageIds = Array.from(stagesTableBody.querySelectorAll('tr[data-stage-id]'))
                                .map(row => row.getAttribute('data-stage-id'));
                            
                            // Update the order in the database
                            updateStageOrder(stageIds);
                        }
                    });
                    
                    console.log('SortableJS initialized for stages table');
                }
            }
            
            // Helper function to update the visual order numbers in the table
            function updateOrderNumbers() {
                const visibleRows = Array.from(stagesTableBody.querySelectorAll('tr[data-stage-id]'))
                    .filter(row => !row.classList.contains('hidden') && window.getComputedStyle(row).display !== 'none');
                    
                visibleRows.forEach((row, index) => {
                    const orderCell = row.querySelector('.stage-order');
                    if (orderCell) {
                        orderCell.textContent = index + 1;
                    }
                });
                
                console.log('Order numbers updated visually');
            }
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    console.log('Clicked outside modal, closing');
                    modal.classList.add('hidden');
                }
            });
            
            // Add keyboard event for ESC key to close modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    console.log('ESC key pressed, closing modal');
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
