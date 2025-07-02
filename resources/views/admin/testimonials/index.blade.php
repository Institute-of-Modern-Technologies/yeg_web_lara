@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
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
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap justify-between items-center gap-2">
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
                    <div class="flex flex-col md:flex-row md:items-start p-4 bg-white">
                        <div class="flex items-start">
                            <!-- Drag Handle -->
                            <div class="flex-shrink-0 mr-3 cursor-move drag-handle">
                                <span class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-grip-vertical text-xl"></i>
                                </span>
                            </div>
                            
                            <!-- Image -->
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden">
                                    <img src="{{ asset('storage/' . $testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow mt-3 md:mt-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
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

                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-shrink-0 mt-4 md:mt-0 md:ml-4 gap-3 w-full md:w-auto justify-end items-center">
                            <button type="button" class="toggle-active relative inline-flex items-center h-6 rounded-full w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors {{ $testimonial->is_active ? 'bg-green-500' : 'bg-gray-200' }}" data-id="{{$testimonial->id}}" title="{{ $testimonial->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate' }}">
                                <span class="sr-only">{{ $testimonial->is_active ? 'Active' : 'Inactive' }}</span>
                                <span class="{{ $testimonial->is_active ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition-transform"></span>
                            </button>
                            <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="flex items-center text-blue-600 bg-blue-50 hover:bg-blue-100 p-2 rounded-md">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-md">
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
        
        // Handle toggle button for active status
        document.querySelectorAll('.toggle-active').forEach(toggleBtn => {
            toggleBtn.addEventListener('click', function() {
                const id = this.dataset.id;
                const isCurrentlyActive = this.classList.contains('bg-green-500');
                const newStatus = !isCurrentlyActive; // Toggle the status
                const testimonialItem = this.closest('.testimonial-item');
                
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
                    testimonialItem.classList.remove('opacity-50');
                } else {
                    toggleKnob.classList.remove('translate-x-6');
                    toggleKnob.classList.add('translate-x-1');
                    testimonialItem.classList.add('opacity-50');
                }
                
                // Update title attribute
                this.title = newStatus ? 'Active - Click to deactivate' : 'Inactive - Click to activate';
                
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
                        testimonialItem.classList.remove('opacity-50');
                        this.title = 'Active - Click to deactivate';
                    } else {
                        // Was inactive, revert to inactive
                        this.classList.remove('bg-green-500');
                        this.classList.add('bg-gray-200');
                        const toggleKnob = this.querySelector('span:not(.sr-only)');
                        toggleKnob.classList.remove('translate-x-6');
                        toggleKnob.classList.add('translate-x-1');
                        testimonialItem.classList.add('opacity-50');
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
