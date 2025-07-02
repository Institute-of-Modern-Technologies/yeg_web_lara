@extends('admin.dashboard')

<!-- Add SweetAlert2 in the head to ensure it's loaded early -->
@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Happenings</h1>
        <a href="{{ route('admin.happenings.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Happening</span>
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

    <!-- Happenings List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-newspaper mr-2 text-primary"></i>
                <span>Happenings</span>
            </h2>
            <p class="text-sm text-gray-500">Drag and drop to reorder happenings</p>
        </div>

        @if($happenings->isEmpty())
        <div class="p-6 text-center text-gray-500">
            <p>No happenings found. Click "Add New Happening" to create one.</p>
        </div>
        @else
        <div class="p-6">
            <ul id="sortable-happenings" class="space-y-4">
                @foreach($happenings as $happening)
                <div class="bg-white p-4 rounded-lg shadow-md flex md:flex-row flex-col md:items-center gap-4 happening-item {{ !$happening->is_active ? 'opacity-50' : '' }}" data-id="{{ $happening->id }}">
                    <div class="flex flex-col md:flex-row md:items-center p-4 bg-white">
                        <!-- Drag Handle -->
                        <div class="flex-shrink-0 mr-4 cursor-move drag-handle">
                            <span class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-grip-vertical text-xl"></i>
                            </span>
                        </div>
                        
                        <!-- Media Preview -->
                        <div class="flex-shrink-0 md:mr-4 mb-3 md:mb-0 w-full md:w-40 h-24 overflow-hidden rounded">
                            @if($happening->media_type == 'image')
                                <img src="{{ asset('storage/' . $happening->media_path) }}" alt="{{ $happening->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="bg-gray-900 w-full h-full flex items-center justify-center">
                                    <i class="fas fa-play-circle text-3xl text-white"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $happening->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $happening->getShortContent(150) }}</p>
                            <div class="mt-2 flex flex-wrap gap-2">

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="far fa-user mr-1"></i>
                                    {{ $happening->author_name ?? 'Unknown' }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ $happening->getFormattedDate() }}
                                </span>
                                @if($happening->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="far fa-folder mr-1"></i>
                                    {{ $happening->category }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-shrink-0 mt-3 md:mt-0 md:ml-4 space-x-2 items-center">
                            <form action="{{ route('admin.happenings.toggle-active', $happening->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="toggle-active relative inline-flex items-center h-6 rounded-full w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors {{ $happening->is_active ? 'bg-green-500' : 'bg-gray-200' }}" data-id="{{$happening->id}}" title="{{ $happening->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate' }}">
                                    <span class="sr-only">{{ $happening->is_active ? 'Active' : 'Inactive' }}</span>
                                    <span class="{{ $happening->is_active ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition-transform"></span>
                                </button>
                                <input type="hidden" name="is_active" value="{{ $happening->is_active ? 0 : 1 }}">
                            </form>
                            <a href="{{ route('admin.happenings.edit', $happening->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.happenings.destroy', $happening->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this happening?')">
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
        const happeningsList = document.getElementById('sortable-happenings');
        if (happeningsList) {
            new Sortable(happeningsList, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function() {
                    updateHappeningOrder();
                }
            });
        }
        
        // Handle toggle button with form submission
        document.querySelectorAll('.toggle-active').forEach(toggleBtn => {
            toggleBtn.addEventListener('click', function() {
                const form = this.closest('form');
                const isCurrentlyActive = this.classList.contains('bg-green-500');
                const newStatus = !isCurrentlyActive; // Toggle the status
                const happeningCard = this.closest('.happening-item');
                
                console.log('Toggle clicked, submitting form...');
                
                // Update UI immediately for better UX
                this.classList.toggle('bg-green-500');
                this.classList.toggle('bg-gray-200');
                
                // Move the toggle button knob
                const toggleKnob = this.querySelector('span:not(.sr-only)');
                if (newStatus) {
                    toggleKnob.classList.remove('translate-x-1');
                    toggleKnob.classList.add('translate-x-6');
                    happeningCard.classList.remove('opacity-50');
                } else {
                    toggleKnob.classList.remove('translate-x-6');
                    toggleKnob.classList.add('translate-x-1');
                    happeningCard.classList.add('opacity-50');
                }
                
                // Update title attribute
                this.title = newStatus ? 'Active - Click to deactivate' : 'Inactive - Click to activate';
                
                // Update the hidden input with the new status
                const hiddenInput = form.querySelector('input[name="is_active"]');
                hiddenInput.value = newStatus ? '1' : '0';
                
                // Submit the form with traditional form submission
                form.submit();
            });
        });
        
        // Update happening order via AJAX
        function updateHappeningOrder() {
            const happeningItems = document.querySelectorAll('.happening-item');
            const happenings = [];
            
            happeningItems.forEach((item, index) => {
                happenings.push({
                    id: item.dataset.id,
                    position: index
                });
            });
            
            // Send the order to the server
            fetch('{{ route("admin.happenings.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ happenings: happenings })
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
