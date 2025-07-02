@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Program Types</h1>
        <button onclick="openCreateModal()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Program Type</span>
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

    <!-- Program Type List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap justify-between items-center gap-2">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list-alt mr-2 text-primary"></i>
                <span>Program Types</span>
            </h2>
        </div>

        @if($programTypes->isEmpty())
        <div class="p-6 text-center text-gray-500">
            <p>No program types found. Click "Add New Program Type" to create one.</p>
        </div>
        @else
        <!-- Table view for desktop/tablet -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($programTypes as $programType)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $programType->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button type="button" onclick="openEditModal({{ $programType->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete({{ $programType->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <form id="delete-form-{{ $programType->id }}" action="{{ route('admin.program-types.destroy', $programType->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Card view for mobile -->
        <div class="block md:hidden">
            <ul class="divide-y divide-gray-200">
                @foreach($programTypes as $programType)
                <li class="p-4">
                    <div class="flex flex-col space-y-3">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $programType->name }}</h3>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="openEditModal({{ $programType->id }})" class="flex items-center text-blue-600 bg-blue-50 hover:bg-blue-100 p-2 rounded-md">
                                <i class="fas fa-edit mr-1"></i>
                                <span class="text-sm">Edit</span>
                            </button>
                            <button onclick="confirmDelete({{ $programType->id }})" class="flex items-center text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-md">
                                <i class="fas fa-trash-alt mr-1"></i>
                                <span class="text-sm">Delete</span>
                            </button>
                            <form id="delete-form-{{ $programType->id }}" action="{{ route('admin.program-types.destroy', $programType->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $programTypes->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Create Program Type Modal -->
<div id="createProgramTypeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden animate-fadeIn transform transition-all duration-300">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-primary to-red-700 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-plus-circle mr-3"></i>
                Add New Program Type
            </h3>
            <button onclick="closeCreateModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <form id="createProgramTypeForm" action="{{ route('admin.program-types.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                            id="name" type="text" name="name" placeholder="Enter program type name" required>
                    </div>
                    

                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Program Type Modal -->
<div id="editProgramTypeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden animate-fadeIn transform transition-all duration-300">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-primary to-red-700 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-edit mr-3"></i>
                Edit Program Type
            </h3>
            <button onclick="closeEditModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <form id="editProgramTypeForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="edit_name">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                            id="edit_name" type="text" name="name" placeholder="Enter program type name" required>
                    </div>
                    

                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for modals and interactions -->
<!-- Include Axios for AJAX requests -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Create Modal Functions
    function openCreateModal() {
        document.getElementById('createProgramTypeModal').classList.remove('hidden');
        document.getElementById('createProgramTypeModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeCreateModal() {
        document.getElementById('createProgramTypeModal').classList.add('hidden');
        document.getElementById('createProgramTypeModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
        document.getElementById('createProgramTypeForm').reset();
    }
    
    // Delete Confirmation Function
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                confirmButton: 'px-4 py-2 text-white rounded-lg hover:bg-red-700 transition-colors',
                cancelButton: 'px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors mr-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById(`delete-form-${id}`).submit();
                
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
    
    // Edit Modal Functions
    function openEditModal(id) {
        // Show loading
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching program type data',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Using Axios which is included with Laravel by default
        axios.get(`/admin/program-types/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // Parse the data
            const programType = response.data;
            
            // Set form values
            document.getElementById('edit_name').value = programType.name;
            
            // Set the form action
            document.getElementById('editProgramTypeForm').action = `/admin/program-types/${id}`;
            
            // Close loading and open modal
            Swal.close();
            document.getElementById('editProgramTypeModal').classList.remove('hidden');
            document.getElementById('editProgramTypeModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not load program type. Please try again.'
            });
        });
    }
    
    function closeEditModal() {
        document.getElementById('editProgramTypeModal').classList.add('hidden');
        document.getElementById('editProgramTypeModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    
    // Initialize when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Setup any initial state or event listeners here
        console.log('Program Types page initialized');
    });
</script>
@endsection
