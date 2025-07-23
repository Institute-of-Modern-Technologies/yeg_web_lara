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
                            <th class="px-4 py-3 w-24">Level</th>
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
                                
                                <!-- Level Column -->
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-medium rounded-md bg-gray-100 border border-gray-200 text-gray-700">
                                        {{ $stage->level ?? 'Not Set' }}
                                    </span>
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
    <div id="stageModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-[9999] hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full justify-center items-center">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t bg-gradient-to-r from-[#950713] to-red-800">
                    <h3 class="text-xl font-semibold text-white">
                        <i class="fas fa-plus-circle mr-2"></i> <span id="modalTitleText">Add New Stage</span>
                    </h3>
                    <button type="button" class="text-white bg-transparent hover:text-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="stageModal" id="closeModalBtnX">
                        <i class="fas fa-times text-xl"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4 max-h-[60vh] overflow-y-auto">
                    <form id="stageForm" class="space-y-5">
                        @csrf
                        <input type="hidden" id="stageId" name="id" value="">
                        
                        <!-- Name Input -->
                        <div class="mb-4">
                            <label for="stageName" class="block mb-2 text-sm font-medium text-gray-900">Stage Name <span class="text-red-500">*</span></label>
                            <input type="text" id="stageName" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#950713] focus:border-[#950713] block w-full p-2.5" placeholder="Enter stage name">
                            <span class="text-red-500 text-xs error-message mt-1" id="nameError"></span>
                        </div>
                        
                        <!-- Description Input -->
                        <div class="mb-4">
                            <label for="stageDescription" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                            <textarea id="stageDescription" name="description" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-[#950713] focus:border-[#950713]" placeholder="Provide a brief description of this stage"></textarea>
                            <span class="text-red-500 text-xs error-message" id="descriptionError"></span>
                        </div>
                        
                        <!-- Row with Status, Level and Order -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Status Select -->
                            <div>
                                <label for="stageStatus" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                <select id="stageStatus" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#950713] focus:border-[#950713] block w-full p-2.5">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <span class="text-red-500 text-xs error-message" id="statusError"></span>
                            </div>
                            
                            <!-- Level Input -->
                            <div>
                                <label for="stageLevel" class="block mb-2 text-sm font-medium text-gray-900">Level</label>
                                <div class="form-group">
                                    <label for="stageLevel">Level</label>
                                    <input type="text" id="stageLevel" name="level" class="form-control" placeholder="Enter level">
                                    <span class="error-message text-danger hidden" data-error="level"></span>
                                </div>
                            
                            <!-- Order Input -->
                            <div>
                                <label for="stageOrder" class="block mb-2 text-sm font-medium text-gray-900">Display Order</label>
                                <input type="number" id="stageOrder" name="order" min="0" value="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#950713] focus:border-[#950713] block w-full p-2.5">
                                <span class="text-red-500 text-xs error-message" id="orderError"></span>
                            </div>
                        </div>
                        
                        <!-- Activities Multi-select -->
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900">
                                <i class="fas fa-tasks text-[#950713] mr-2"></i> Assign Activities
                            </label>
                            <div class="max-h-48 overflow-y-auto p-3 border rounded-lg bg-gray-50">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($activities as $activity)
                                        <div class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                            <input type="checkbox" name="activities[]" id="activity{{ $activity->id }}" value="{{ $activity->id }}" class="activity-checkbox h-4 w-4 text-[#950713] focus:ring-[#950713] border-gray-300 rounded">
                                            <label for="activity{{ $activity->id }}" class="ms-2 text-sm font-medium text-gray-900 select-none cursor-pointer">{{ $activity->name }}</label>
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
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b bg-gray-50">
                    <button id="saveStageBtn" type="button" class="text-white bg-[#950713] hover:bg-[#850612] focus:ring-4 focus:outline-none focus:ring-[#950713]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                        <i class="fas fa-save mr-2"></i> Save Stage
                    </button>
                    <button id="closeModalBtn" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="hidden fixed inset-0 overflow-y-auto z-[100]" role="dialog" aria-modal="true">
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

    <!-- Only keeping one instance of the Stage Modal -->



@endsection

    <!-- Required Scripts for Stages Management -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        console.log('DOM Content Loaded - Complete Rewrite');
        
        // =========================
        // MODAL CONTROL FUNCTIONS
        // =========================
        
        function openModal() {
            $('#stageModal').css({
                'display': 'flex',
                'opacity': '1'
            });
            $('body').addClass('overflow-hidden');
        }
        
        function closeModal() {
            $('#stageModal').hide();
            $('body').removeClass('overflow-hidden');
            resetForm();
        }
        
        function resetForm() {
            $('#stageForm')[0].reset();
            $('#stageId').val('');
            $('#stageForm').find('.error-message').text('').addClass('hidden');
            $('input[name="activities[]"]').prop('checked', false);
        }
        
        // =========================
        // ADD NEW STAGE
        // =========================
        
        $('#addStageBtn, #emptyAddStageBtn').on('click', function(e) {
            e.preventDefault();
            resetForm();
            $('#modalTitleText').text('Add New Stage');
            openModal();
            console.log('Add stage modal opened');
        });
        
        // =========================
        // EDIT STAGE
        // =========================
        
        $(document).on('click', '.edit-stage-btn', function(e) {
            e.preventDefault();
            
            var stageId = $(this).data('stage-id');
            console.log('Edit clicked for stage ID:', stageId);
            
            // Force hard reset the form element to clear browser validation state
            $('#stageForm').replaceWith($('#stageForm').clone());
            
            // Reset form before populating
            resetForm();
            
            $.ajax({
                url: '/admin/stages/' + stageId + '/edit',
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                beforeSend: function() {
                    console.log('Fetching stage data...');
                },
                success: function(response) {
                    console.log('Stage data received:', response);
                    
                    if (response.success && response.stage) {
                        var stage = response.stage;
                        
                        // Populate form fields
                        $('#stageId').val(stage.id);
                        $('#stageName').val(stage.name);
                        $('#stageStatus').val(stage.status);
                        $('#stageLevel').val(stage.level || 'Level 1');
                        $('#stageOrder').val(stage.order || 0);
                        $('#stageDescription').val(stage.description || '');
                        
                        // Check activities if any
                        if (stage.activities && stage.activities.length > 0) {
                            var activityIds = stage.activities.map(function(activity) {
                                return activity.id;
                            });
                            
                            $('input[name="activities[]"]').each(function() {
                                if (activityIds.includes(parseInt($(this).val()))) {
                                    $(this).prop('checked', true);
                                }
                            });
                        }
                        
                        // Update modal title and open
                        $('#modalTitleText').text('Edit Stage: ' + stage.name);
                        openModal();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not load stage data',
                            confirmButtonColor: '#950713'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error loading stage data:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load stage data. Please try again.',
                        confirmButtonColor: '#950713'
                    });
                }
            });
        });
        
        // =========================
        // SAVE STAGE (CREATE/UPDATE)
        // =========================
        
        $('#saveStageBtn').on('click', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            $('.error-message').text('').addClass('hidden');
            
            var stageId = $('#stageId').val();
            var isEditing = stageId !== '';
            var formData = new FormData($('#stageForm')[0]);
            
            // Debug output for form data
            console.log('Form Data Contents:');
            console.log('Stage ID:', stageId);
            console.log('Name:', $('#stageName').val());
            console.log('Status:', $('#stageStatus').val());
            console.log('Level:', $('#stageLevel').val());
            
            // Manual field check - if form values aren't in FormData, add them manually
            if (!formData.has('name') || formData.get('name') === '') {
                formData.append('name', $('#stageName').val());
            }
            
            if (!formData.has('status') || formData.get('status') === '') {
                formData.append('status', $('#stageStatus').val());
            }
            
            if (!formData.has('level') || formData.get('level') === '') {
                formData.append('level', $('#stageLevel').val());
            }
            
            // Set the correct URL and method based on create/update
            var url = isEditing ? '/admin/stages/' + stageId : '/admin/stages';
            var method = 'POST'; // Always use POST, we'll add _method for PUT
            
            // For PUT requests, Laravel expects a _method field
            if (isEditing) {
                formData.append('_method', 'PUT');
            }
            
            // Double-check critical fields are in the form data
            console.log('Form data keys before submission:');
            for(var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]); 
            }
            
            // Show loading state
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Saving...');
            
            $.ajax({
                url: url,
                method: 'POST', // Always POST with _method for PUT
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Always reset the button state first
                    $('#saveStageBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Save Stage');
                    
                    if (response.success) {
                        // Close the modal immediately on success
                        closeModal();
                        
                        // Show success message and reload
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Stage saved successfully!',
                            confirmButtonColor: '#950713'
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to save stage.',
                            confirmButtonColor: '#950713'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error saving stage:', xhr);
                    $('#saveStageBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Save Stage');
                    
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $('#' + field + 'Error').text(messages[0]).removeClass('hidden');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An unexpected error occurred. Please try again.',
                            confirmButtonColor: '#950713'
                        });
                    }
                }
            });
        });
        
        // =========================
        // CLOSE MODALS
        // =========================
        
        // Close stage modal
        $('#closeModalBtn, #closeModalBtnX').on('click', function() {
            closeModal();
        });
        
        // Close with ESC key
        $(document).keydown(function(e) {
            if (e.key === 'Escape') {
                closeModal();
                $('#deleteConfirmModal').addClass('hidden');
            }
        });
        
        // =========================
        // DELETE STAGE
        // =========================
        
        // Open delete confirmation
        $('.delete-stage-btn').on('click', function() {
            var stageId = $(this).data('stage-id');
            var stageName = $(this).closest('tr').find('h4').text().trim();
            
            $('#deleteStageId').val(stageId);
            $('#deleteStageNameDisplay').text(stageName);
            $('#deleteConfirmModal').removeClass('hidden');
        });
        
        // Close delete modal
        $('#closeDeleteModalBtn, #cancelDeleteBtn').on('click', function() {
            $('#deleteConfirmModal').addClass('hidden');
        });
        
        // Confirm delete
        $('#confirmDeleteBtn').on('click', function() {
            var stageId = $('#deleteStageId').val();
            
            // Show loading state
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Deleting...');
            
            $.ajax({
                url: '/admin/stages/' + stageId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#deleteConfirmModal').addClass('hidden');
                    $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash-alt mr-2"></i> Delete Stage');
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message || 'Stage has been deleted.',
                            confirmButtonColor: '#950713'
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to delete stage.',
                            confirmButtonColor: '#950713'
                        });
                    }
                },
                error: function(xhr) {
                    $('#deleteConfirmModal').addClass('hidden');
                    $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash-alt mr-2"></i> Delete Stage');
                    
                    console.error('Error deleting stage:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while deleting the stage. Please try again.',
                        confirmButtonColor: '#950713'
                    });
                }
            });
        });
        
        // =========================
        // TOGGLE STATUS
        // =========================
        
        $('.toggle-status-btn').on('click', function() {
            var btn = $(this);
            var stageId = btn.data('stage-id');
            var isActive = btn.hasClass('text-red-600');
            var newStatus = isActive ? 'inactive' : 'active';
            
            $.ajax({
                url: '/admin/stages/' + stageId + '/toggle-status',
                type: 'POST',
                data: { 
                    status: newStatus,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> ' + (isActive ? 'Deactivating...' : 'Activating...'));
                },
                success: function(response) {
                    if (response.success) {
                        var row = btn.closest('tr');
                        var statusBadge = row.find('td:nth-child(3) span').first();
                        
                        if (newStatus === 'active') {
                            statusBadge.removeClass('bg-red-100 text-red-800').addClass('bg-green-100 text-green-800').text('Active');
                            btn.removeClass('text-green-600 hover:bg-green-50').addClass('text-red-600 hover:bg-red-50')
                                .html('<i class="fas fa-toggle-off mr-1"></i> Deactivate');
                        } else {
                            statusBadge.removeClass('bg-green-100 text-green-800').addClass('bg-red-100 text-red-800').text('Inactive');
                            btn.removeClass('text-red-600 hover:bg-red-50').addClass('text-green-600 hover:bg-green-50')
                                .html('<i class="fas fa-toggle-on mr-1"></i> Activate');
                        }
                        
                        // Show success toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        
                        Toast.fire({
                            icon: 'success',
                            title: 'Status updated successfully'
                        });
                    }
                },
                error: function() {
                    btn.html('<i class="fas fa-' + (isActive ? 'toggle-off' : 'toggle-on') + ' mr-1"></i> ' + 
                           (isActive ? 'Deactivate' : 'Activate'));
                           
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update status. Please try again.',
                        confirmButtonColor: '#950713'
                    });
                }
            });
        });
        
        // =========================
        // SORTABLE (DRAG & DROP)
        // =========================
        
        var stagesTableBody = document.getElementById('stages-table-body');
        if (stagesTableBody && stagesTableBody.querySelectorAll('tr[data-stage-id]').length > 0) {
            new Sortable(stagesTableBody, {
                handle: '.handle',
                animation: 150,
                ghostClass: 'bg-gray-100',
                onEnd: function() {
                    // Update order numbers visually
                    $('.stage-order').each(function(index) {
                        $(this).text(index + 1);
                    });
                    
                    // Get new order
                    var stageOrder = {};
                    $('tr[data-stage-id]').each(function(index) {
                        stageOrder[$(this).data('stage-id')] = index + 1;
                    });
                    
                    // Update order in database
                    $.ajax({
                        url: '/admin/stages/update-order',
                        type: 'POST',
                        data: JSON.stringify({ order: stageOrder }),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log('Stage order updated successfully');
                            }
                        },
                        error: function() {
                            console.error('Failed to update stage order');
                        }
                    });
                }
            });
        }
        
        // =========================
        // SEARCH FUNCTIONALITY
        // =========================
        
        $('#stageSearchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase().trim();
            $('#stages-table-body tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
