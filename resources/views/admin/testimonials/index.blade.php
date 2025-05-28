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
                <li class="border border-gray-200 rounded-lg overflow-hidden testimonial-item" data-id="{{ $testimonial->id }}">
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
                                @if($testimonial->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="h-2 w-2 rounded-full bg-green-500 mr-1"></span>
                                    Active
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="h-2 w-2 rounded-full bg-gray-500 mr-1"></span>
                                    Inactive
                                </span>
                                @endif
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
