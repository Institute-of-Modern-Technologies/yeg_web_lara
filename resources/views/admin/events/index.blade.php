@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Events</h1>
        <a href="{{ route('admin.events.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Event</span>
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

    <!-- Event List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-calendar-alt mr-2 text-primary"></i>
                <span>Events</span>
            </h2>
            <p class="text-sm text-gray-500">Drag and drop to reorder events</p>
        </div>

        @if($events->isEmpty())
        <div class="p-6 text-center text-gray-500">
            <p>No events found. Click "Add New Event" to create one.</p>
        </div>
        @else
        <div class="p-6">
            <ul id="sortable-events" class="space-y-4">
                @foreach($events as $event)
                <li class="border border-gray-200 rounded-lg overflow-hidden event-item {{ !$event->is_active ? 'opacity-50' : '' }}" data-id="{{ $event->id }}">
                    <div class="flex flex-col md:flex-row md:items-center p-4 bg-white">
                        <!-- Drag Handle -->
                        <div class="flex-shrink-0 mr-4 cursor-move drag-handle">
                            <span class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-grip-vertical text-xl"></i>
                            </span>
                        </div>
                        
                        <!-- Media Preview -->
                        <div class="flex-shrink-0 md:mr-4 mb-3 md:mb-0 w-full md:w-40 h-24 overflow-hidden rounded">
                            @if($event->media_type == 'image')
                                <img src="{{ asset('storage/' . $event->media_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="bg-gray-900 w-full h-full flex items-center justify-center">
                                    <i class="fas fa-play-circle text-3xl text-white"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $event->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $event->description }}</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <!-- Toggle switch for active status -->
                                <div class="flex items-center mr-2">
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input type="checkbox" 
                                            class="toggle-active toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" 
                                            id="toggle-{{$event->id}}" 
                                            data-id="{{$event->id}}"
                                            {{ $event->is_active ? 'checked' : '' }}
                                        >
                                        <label for="toggle-{{$event->id}}" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                    </div>
                                    <span class="status-label text-sm font-medium text-gray-900">{{ $event->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $event->level_color }}20; color: {{ $event->level_color }}">
                                    {{ $event->level ?? 'All Levels' }}
                                </span>
                                @if($event->duration)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $event->duration }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-shrink-0 mt-3 md:mt-0 md:ml-4 space-x-2">
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors" onclick="confirmDelete({{ $event->id }}, '{{ addslashes($event->title) }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <form id="delete-form-{{ $event->id }}" action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
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
            title: 'Delete Event',
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
</script>

<!-- Add an inline script for event handlers -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sortable list
        const eventsList = document.getElementById('sortable-events');
        if (eventsList) {
            new Sortable(eventsList, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function() {
                    updateEventOrder();
                }
            });
        }
        
        // Add event listeners to toggle switches with direct AJAX
        document.querySelectorAll('.toggle-active').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const isActive = this.checked;
                const eventItem = this.closest('.event-item');
                const statusLabel = this.closest('.flex.items-center').querySelector('.status-label');
                const originalStatus = !isActive; // Store original status in case we need to revert
                
                console.log('Toggle clicked for ID:', id, 'New status:', isActive ? 'active' : 'inactive');
                
                // Update UI immediately for better user experience
                statusLabel.textContent = isActive ? 'Active' : 'Inactive';
                
                if (!isActive) {
                    eventItem.classList.add('opacity-50');
                } else {
                    eventItem.classList.remove('opacity-50');
                }
                
                // Send AJAX request using the Fetch API
                fetch(`/admin/events/${id}/toggle-active`, {
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
                        eventItem.classList.remove('opacity-50');
                    } else {
                        eventItem.classList.add('opacity-50');
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
        

        
        // Update event order via AJAX
        function updateEventOrder() {
            const eventItems = document.querySelectorAll('.event-item');
            const events = [];
            
            eventItems.forEach((item, index) => {
                events.push({
                    id: item.dataset.id,
                    position: index
                });
            });
            
            // Send the order to the server
            fetch('{{ route("admin.events.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ events: events })
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
@endsection
