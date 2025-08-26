@extends('admin.dashboard')

<!-- Add SweetAlert2 in the head to ensure it's loaded early -->
@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Partner Schools</h1>
        <a href="{{ route('admin.partner-schools.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New School</span>
        </a>
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

    <!-- Partner Schools List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-school mr-2 text-primary"></i>
                <span>Partner Schools</span>
            </h2>
            <p class="text-sm text-gray-500">Drag and drop to reorder schools</p>
        </div>

        @if($partnerSchools->isEmpty())
        <div class="p-6 text-center text-gray-500">
            <p>No partner schools found. Click "Add New School" to create one.</p>
        </div>
        @else
        <div class="p-6">
            <ul id="sortable-schools" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($partnerSchools as $school)
                <li class="border border-gray-200 rounded-lg overflow-hidden school-item {{ !$school->is_active ? 'opacity-50' : '' }}" data-id="{{ $school->id }}">
                    <div class="flex flex-col p-4 bg-white h-full">
                        <!-- Drag Handle and Status -->
                        <div class="flex justify-between items-center mb-3">
                            <div class="cursor-move drag-handle">
                                <span class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-grip-vertical text-xl"></i>
                                </span>
                            </div>
                            <div>

                            </div>
                        </div>
                        
                        <!-- School Image -->
                        <div class="mb-4 flex-shrink-0">
                            <img src="{{ asset('/' . $school->image_path) }}" alt="{{ $school->name }}" class="w-full h-40 object-cover rounded-md">
                        </div>
                        
                        <!-- School Info -->
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $school->name }}</h3>
                            
                            @if($school->description)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $school->getShortDescription(100) }}</p>
                            @endif
                            
                            @if($school->website_url)
                            <div class="mb-3">
                                <a href="{{ $school->website_url }}" target="_blank" class="text-blue-500 text-sm hover:underline flex items-center">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    <span>Visit Website</span>
                                </a>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex justify-end items-center space-x-2 mt-3 pt-3 border-t border-gray-100">
                            <button type="button" class="toggle-active relative inline-flex items-center h-6 rounded-full w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors {{ $school->is_active ? 'bg-green-500' : 'bg-gray-200' }}" data-id="{{$school->id}}" title="{{ $school->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate' }}">
                                <span class="sr-only">{{ $school->is_active ? 'Active' : 'Inactive' }}</span>
                                <span class="{{ $school->is_active ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition-transform"></span>
                            </button>
                            <a href="{{ route('admin.partner-schools.edit', $school->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.partner-schools.destroy', $school->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this school?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sortable list
        const schoolsList = document.getElementById('sortable-schools');
        if (schoolsList) {
            new Sortable(schoolsList, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function() {
                    updateSchoolOrder();
                }
            });
        }
        
        // Handle toggle button for active status
        document.querySelectorAll('.toggle-active').forEach(toggleBtn => {
            toggleBtn.addEventListener('click', function() {
                const id = this.dataset.id;
                const isCurrentlyActive = this.classList.contains('bg-green-500');
                const newStatus = !isCurrentlyActive; // Toggle the status
                const schoolItem = this.closest('.school-item');
                
                // Store original status for reverting
                const originalStatus = isCurrentlyActive;
                
                // Update UI immediately for better UX
                this.classList.toggle('bg-green-500');
                this.classList.toggle('bg-gray-200');
                
                // Move the toggle button knob
                const toggleKnob = this.querySelector('span:not(.sr-only)');
                if (newStatus) {
                    toggleKnob.classList.remove('translate-x-1');
                    toggleKnob.classList.add('translate-x-6');
                    schoolItem.classList.remove('opacity-50');
                } else {
                    toggleKnob.classList.remove('translate-x-6');
                    toggleKnob.classList.add('translate-x-1');
                    schoolItem.classList.add('opacity-50');
                }
                
                // Update title attribute
                this.title = newStatus ? 'Active - Click to deactivate' : 'Inactive - Click to activate';
                
                // Send AJAX request using the Fetch API
                fetch(`/admin/partner-schools/${id}/toggle-active`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PATCH',
                        is_active: newStatus ? 1 : 0
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    
                    // Show success notification
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Status updated successfully',
                        showConfirmButton: false,
                        timer: 3000
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Revert UI changes on error
                    this.checked = originalStatus;
                    statusLabel.textContent = originalStatus ? 'Active' : 'Inactive';
                    
                    if (originalStatus) {
                        schoolItem.classList.remove('opacity-50');
                    } else {
                        schoolItem.classList.add('opacity-50');
                    }
                    
                    // Show error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update status. Please try again.'
                    });
                });
            });
        });
        
        // Update school order via AJAX
        function updateSchoolOrder() {
            const schoolItems = document.querySelectorAll('.school-item');
            const partnerSchools = [];
            
            schoolItems.forEach((item, index) => {
                partnerSchools.push({
                    id: item.dataset.id,
                    position: index
                });
            });
            
            // Send the order to the server
            fetch('{{ route("admin.partner-schools.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ partnerSchools: partnerSchools })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Order updated successfully');
            })
            .catch(error => {
                console.error('Error updating order:', error);
            });
        }
    });
</script>
@endpush
@endsection
