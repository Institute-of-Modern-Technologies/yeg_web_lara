@extends('admin.dashboard')

<!-- Add SweetAlert2 in the head to ensure it's loaded early -->
@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<script>
// Define confirmDelete function globally
function confirmDelete(id, title) {
    console.log('Confirming delete for:', id, title);
    
    Swal.fire({
        title: 'Delete Hero Section?',
        text: `Are you sure you want to delete "${title}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Delete confirmed, submitting form...');
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>
<div class="p-6">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <h1 class="text-2xl font-bold text-gray-900 mb-2 md:mb-0">Hero Section Management</h1>
        <a href="{{ route('admin.hero-sections.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
            <i class="fas fa-plus-circle mr-2"></i>
            <span>Add New Hero Section</span>
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <p>{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Hero Sections List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-image mr-2 text-primary"></i>
                <span>Hero Sections</span>
            </h2>
        </div>

        <div class="p-6">
            @if($heroSections->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-image text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No hero sections found</p>
                    <p class="text-gray-400 text-sm mt-1">Create your first hero section to display on your website</p>
                </div>
            @else
                <div id="hero-sections-list" class="space-y-6">
                    @foreach($heroSections as $section)
                    <div class="bg-gray-50 rounded-lg overflow-hidden hero-section-item {{ !$section->is_active ? 'opacity-50' : '' }}" data-id="{{ $section->id }}">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <!-- Drag Handle and Order -->
                            <div class="flex items-center md:mr-4 mb-3 md:mb-0">
                                <div class="cursor-grab handle p-2 mr-2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-grip-vertical"></i>
                                </div>
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-gray-700 text-sm font-medium">
                                    {{ $section->display_order }}
                                </span>
                            </div>
                            
                            <!-- Image Preview -->
                            <div class="flex-shrink-0 md:mr-4 mb-3 md:mb-0 w-full md:w-40 h-24 overflow-hidden rounded">
                                <img src="{{ asset('/' . $section->image_path) }}" alt="{{ $section->title }}" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $section->title }}</h3>
                                <p class="text-gray-600 text-sm line-clamp-2">{{ $section->subtitle }}</p>
                                <div class="mt-2 flex flex-wrap gap-2">

                                    @if($section->button_text)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-link text-xs mr-1"></i>
                                        {{ $section->button_text }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center space-x-2 mt-3 md:mt-0">
                                <button type="button" class="toggle-active relative inline-flex items-center h-6 rounded-full w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors {{ $section->is_active ? 'bg-green-500' : 'bg-gray-200' }}" data-id="{{$section->id}}" title="{{ $section->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate' }}">
                                    <span class="sr-only">{{ $section->is_active ? 'Active' : 'Inactive' }}</span>
                                    <span class="{{ $section->is_active ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition-transform"></span>
                                </button>
                                <a href="{{ route('admin.hero-sections.edit', $section->id) }}" class="p-2 text-blue-500 hover:text-blue-700" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="confirmDelete({{ $section->id }}, '{{ $section->title }}')" class="p-2 text-red-500 hover:text-red-700" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $section->id }}" method="POST" action="{{ route('admin.hero-sections.destroy', $section->id) }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<!-- SweetAlert2 is already loaded in head -->
<script>
    // Initialize drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const heroSectionsList = document.getElementById('hero-sections-list');
        if (heroSectionsList) {
            new Sortable(heroSectionsList, {
                handle: '.handle',
                animation: 150,
                onEnd: function() {
                    updateOrder();
                }
            });
        }
        
        // Handle toggle button for active status
        document.querySelectorAll('.toggle-active').forEach(toggleBtn => {
            toggleBtn.addEventListener('click', function() {
                const id = this.dataset.id;
                const isCurrentlyActive = this.classList.contains('bg-green-500');
                const newStatus = !isCurrentlyActive; // Toggle the status
                const heroCard = this.closest('.hero-section-item');
                
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
                    heroCard.classList.remove('opacity-50');
                } else {
                    toggleKnob.classList.remove('translate-x-6');
                    toggleKnob.classList.add('translate-x-1');
                    heroCard.classList.add('opacity-50');
                }
                
                // Update title attribute
                this.title = newStatus ? 'Active - Click to deactivate' : 'Inactive - Click to activate';
                
                // Send AJAX request using the Fetch API
                fetch(`/admin/hero-sections/${id}/toggle-active`, {
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
                    if (originalStatus) {
                        // Was active, revert to active
                        this.classList.add('bg-green-500');
                        this.classList.remove('bg-gray-200');
                        const toggleKnob = this.querySelector('span:not(.sr-only)');
                        toggleKnob.classList.add('translate-x-6');
                        toggleKnob.classList.remove('translate-x-1');
                        heroCard.classList.remove('opacity-50');
                        this.title = 'Active - Click to deactivate';
                    } else {
                        // Was inactive, revert to inactive
                        this.classList.remove('bg-green-500');
                        this.classList.add('bg-gray-200');
                        const toggleKnob = this.querySelector('span:not(.sr-only)');
                        toggleKnob.classList.remove('translate-x-6');
                        toggleKnob.classList.add('translate-x-1');
                        heroCard.classList.add('opacity-50');
                        this.title = 'Inactive - Click to activate';
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
    });
    
    // Update the order of hero sections
    function updateOrder() {
        const sections = document.querySelectorAll('#hero-sections-list > div');
        const items = [];
        
        sections.forEach((section, index) => {
            const id = section.getAttribute('data-id');
            items.push({
                id: id,
                order: index
            });
            
            // Update displayed order number
            const orderBadge = section.querySelector('.rounded-full');
            if (orderBadge) {
                orderBadge.textContent = index;
            }
        });
        
        // Send updated order to server
        fetch('{{ route("admin.hero-sections.update-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ items: items })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Optional: show success message
                console.log('Order updated successfully');
            } else {
                console.error('Error updating order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // confirmDelete function is now defined globally above
</script>
@endpush
@endsection
