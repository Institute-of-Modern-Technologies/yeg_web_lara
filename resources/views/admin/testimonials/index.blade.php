@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Testimonials</h1>
        <a href="{{ route('admin.testimonials.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Testimonial</span>
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

    <!-- Testimonials List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-quote-left mr-2 text-primary"></i>
                <span>Testimonials</span>
            </h2>
            <p class="text-sm text-gray-500">Drag and drop to reorder testimonials</p>
        </div>

        @if($testimonials->isEmpty())
        <div class="p-6 text-center text-gray-500">
            <p>No testimonials found. Click "Add New Testimonial" to create one.</p>
        </div>
        @else
        <div class="p-6">
            <ul id="sortable-testimonials" class="space-y-4">
                @foreach($testimonials as $testimonial)
                <li class="border border-gray-200 rounded-lg overflow-hidden testimonial-item {{ !$testimonial->is_active ? 'opacity-50' : '' }}" data-id="{{ $testimonial->id }}">
                    <div class="flex flex-col md:flex-row md:items-center p-4 bg-white">
                        <!-- Drag Handle -->
                        <div class="flex-shrink-0 mr-4 cursor-move drag-handle">
                            <span class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-grip-vertical text-xl"></i>
                            </span>
                        </div>
                        
                        <!-- Image -->
                        <div class="flex-shrink-0 md:mr-4 mb-3 md:mb-0">
                            <div class="w-20 h-20 rounded-full overflow-hidden">
                                <img src="{{ asset('storage/' . $testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow">
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $testimonial->name }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $testimonial->role }}{{ $testimonial->institution ? ', ' . $testimonial->institution : '' }}</p>
                                </div>
                                <div class="text-yellow-400 mt-2 md:mt-0">
                                    {!! $testimonial->getRatingStars() !!}
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $testimonial->getShortContent(150) }}</p>
                            <div class="mt-2">
                                        <!-- Toggle Switch for Active Status -->
                                        <div class="flex items-center mr-3">
                            <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" class="toggle-active toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" 
                                    id="toggle-{{$testimonial->id}}" 
                                    data-id="{{$testimonial->id}}" 
                                    {{ $testimonial->is_active ? 'checked' : '' }}>
                                <label for="toggle-{{$testimonial->id}}" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                            <span class="status-label text-sm font-medium text-gray-700">{{ $testimonial->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-shrink-0 mt-3 md:mt-0 md:ml-4 space-x-2">
                            <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
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
        const testimonialsList = document.getElementById('sortable-testimonials');
        if (testimonialsList) {
            new Sortable(testimonialsList, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function() {
                    updateTestimonialOrder();
                }
            });
        }
        
        // Add event listeners to toggle switches with direct AJAX
        document.querySelectorAll('.toggle-active').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const isActive = this.checked;
                const testimonialItem = this.closest('.testimonial-item');
                const statusLabel = this.closest('.flex.items-center').querySelector('.status-label');
                const originalStatus = !isActive; // Store original status in case we need to revert
                
                console.log('Toggle clicked for ID:', id, 'New status:', isActive ? 'active' : 'inactive');
                
                // Update UI immediately for better user experience
                statusLabel.textContent = isActive ? 'Active' : 'Inactive';
                
                if (!isActive) {
                    testimonialItem.classList.add('opacity-50');
                } else {
                    testimonialItem.classList.remove('opacity-50');
                }
                
                // Send AJAX request using the Fetch API
                fetch(`/admin/testimonials/${id}/toggle-active`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PATCH',
                        is_active: isActive ? 1 : 0
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
                        testimonialItem.classList.remove('opacity-50');
                    } else {
                        testimonialItem.classList.add('opacity-50');
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
        
        // Update testimonial order via AJAX
        function updateTestimonialOrder() {
            const testimonialItems = document.querySelectorAll('.testimonial-item');
            const testimonials = [];
            
            testimonialItems.forEach((item, index) => {
                testimonials.push({
                    id: item.dataset.id,
                    position: index
                });
            });
            
            // Send the order to the server
            fetch('{{ route("admin.testimonials.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ testimonials: testimonials })
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
