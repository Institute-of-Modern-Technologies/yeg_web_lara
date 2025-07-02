@extends('admin.dashboard')

@section('title', 'School Logos Management')

@section('content')
<div class="p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">School Logos</h1>
        <a href="{{ route('admin.school-logos.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus-circle mr-2"></i>
            <span>Add New School Logo</span>
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

    <!-- School Logos List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap justify-between items-center gap-2">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-images mr-2 text-primary"></i>
                <span>School Logos for Marquee</span>
            </h2>
            <p class="text-sm text-gray-500">Drag and drop to reorder logos</p>
        </div>

        @if($schoolLogos->isEmpty())
        <div class="p-6 text-center text-gray-500">
            <p>No school logos found. Click "Add New School Logo" to create one.</p>
        </div>
        @else
        <!-- Table view for desktop/tablet -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Order</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Logo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 sortable">
                    @foreach($schoolLogos as $logo)
                    <tr data-id="{{ $logo->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap handle cursor-move">
                            <div class="flex items-center">
                                <i class="fas fa-grip-vertical text-gray-400 mr-3"></i>
                                <span>{{ $logo->display_order }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex-shrink-0 h-16 w-16 overflow-hidden rounded border border-gray-200">
                                <img src="{{ asset('storage/' . $logo->logo_path) }}" alt="{{ $logo->name }}" class="h-full w-full object-contain">
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $logo->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-500">Status</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center space-x-3">
                                <form action="{{ route('admin.school-logos.toggle-active', $logo->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="relative inline-flex items-center h-6 rounded-full w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors {{ $logo->is_active ? 'bg-green-500' : 'bg-gray-200' }}" title="{{ $logo->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate' }}">
                                        <span class="sr-only">{{ $logo->is_active ? 'Active' : 'Inactive' }}</span>
                                        <span class="{{ $logo->is_active ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition-transform"></span>
                                    </button>
                                </form>
                                <a href="{{ route('admin.school-logos.edit', $logo->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.school-logos.destroy', $logo->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Card view for mobile -->
        <div class="block md:hidden">
            <ul class="divide-y divide-gray-200">
                @foreach($schoolLogos as $logo)
                <li class="p-4 logo-item" data-id="{{ $logo->id }}">
                    <div class="flex items-start gap-2 mb-4">
                        <div class="flex-shrink-0 cursor-move handle">
                            <i class="fas fa-grip-vertical text-gray-400 mt-2"></i>
                        </div>
                        <div class="flex-shrink-0 h-16 w-16 overflow-hidden rounded border border-gray-200">
                            <img src="{{ asset('storage/' . $logo->logo_path) }}" alt="{{ $logo->name }}" class="h-full w-full object-contain">
                        </div>
                        <div class="flex-grow ml-2">
                            <h3 class="font-medium">{{ $logo->name }}</h3>
                            <div class="flex items-center mt-1">
                                <span class="text-xs text-gray-500 mr-2">Order:</span>
                                <span class="font-medium text-sm">{{ $logo->display_order }}</span>
                            </div>

                        </div>
                    </div>
                    <div class="flex justify-end gap-2 items-center">
                        <form action="{{ route('admin.school-logos.toggle-active', $logo->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="relative inline-flex items-center h-6 rounded-full w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors {{ $logo->is_active ? 'bg-green-500' : 'bg-gray-200' }}" title="{{ $logo->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate' }}">
                                <span class="sr-only">{{ $logo->is_active ? 'Active' : 'Inactive' }}</span>
                                <span class="{{ $logo->is_active ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition-transform"></span>
                            </button>
                        </form>
                        <a href="{{ route('admin.school-logos.edit', $logo->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                            <i class="fas fa-edit mr-1"></i>
                            <span class="text-sm">Edit</span>
                        </a>
                        <form action="{{ route('admin.school-logos.destroy', $logo->id) }}" method="POST" class="inline-block delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                <i class="fas fa-trash-alt mr-1"></i>
                                <span class="text-sm">Delete</span>
                            </button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sortable
        // Initialize sortable for desktop table
        const sortableList = document.querySelector('.sortable');
        // Initialize sortable for mobile cards
        const cardList = document.querySelector('.block.md\:hidden ul');
        // Initialize sortable for desktop view
        if (sortableList) {
            new Sortable(sortableList, {
                handle: '.handle',
                animation: 150,
                onEnd: function(evt) {
                    const items = Array.from(sortableList.querySelectorAll('tr'));
                    const data = items.map((item, index) => {
                        return {
                            id: item.dataset.id,
                            order: index + 1
                        };
                    });
                    
                    // Show loading state
                    const toastEl = document.createElement('div');
                    toastEl.className = 'fixed bottom-4 right-4 bg-blue-500 text-white px-6 py-3 rounded shadow-lg z-50 flex items-center';
                    toastEl.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating logo order...';
                    document.body.appendChild(toastEl);
                    
                    // Update order via AJAX
                    fetch('{{ route("admin.school-logos.update-order") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ logos: data })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Remove loading toast
                        document.body.removeChild(toastEl);
                        
                        if (data.success) {
                            // Show success toast
                            const successToast = document.createElement('div');
                            successToast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 flex items-center';
                            successToast.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Logo order updated successfully';
                            document.body.appendChild(successToast);
                            
                            // Update visual order numbers
                            items.forEach((item, index) => {
                                item.querySelector('.handle span').textContent = index + 1;
                            });
                            
                            // Remove success toast after 2 seconds
                            setTimeout(() => {
                                document.body.removeChild(successToast);
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        // Remove loading toast
                        document.body.removeChild(toastEl);
                        
                        // Show error toast
                        const errorToast = document.createElement('div');
                        errorToast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 flex items-center';
                        errorToast.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Error updating logo order';
                        document.body.appendChild(errorToast);
                        
                        console.error('Error updating order:', error);
                        
                        // Remove error toast after 3 seconds
                        setTimeout(() => {
                            document.body.removeChild(errorToast);
                        }, 3000);
                    });
                }
            });
        }
        
        // Initialize sortable for mobile view
        if (cardList) {
            new Sortable(cardList, {
                handle: '.handle',
                animation: 150,
                onEnd: function(evt) {
                    const items = Array.from(cardList.querySelectorAll('li'));
                    const data = items.map((item, index) => {
                        return {
                            id: item.dataset.id,
                            order: index + 1
                        };
                    });
                    
                    // Show loading state
                    const toastEl = document.createElement('div');
                    toastEl.className = 'fixed bottom-4 right-4 bg-blue-500 text-white px-6 py-3 rounded shadow-lg z-50 flex items-center';
                    toastEl.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating logo order...';
                    document.body.appendChild(toastEl);
                    
                    // Update order via AJAX
                    fetch('{{ route("admin.school-logos.update-order") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ logos: data })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Remove loading toast
                        document.body.removeChild(toastEl);
                        
                        if (data.success) {
                            // Show success toast
                            const successToast = document.createElement('div');
                            successToast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 flex items-center';
                            successToast.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Logo order updated successfully';
                            document.body.appendChild(successToast);
                            
                            // Remove success toast after 2 seconds
                            setTimeout(() => {
                                document.body.removeChild(successToast);
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        // Remove loading toast
                        document.body.removeChild(toastEl);
                        
                        // Show error toast
                        const errorToast = document.createElement('div');
                        errorToast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 flex items-center';
                        errorToast.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Error updating logo order';
                        document.body.appendChild(errorToast);
                        
                        console.error('Error updating order:', error);
                        
                        // Remove error toast after 3 seconds
                        setTimeout(() => {
                            document.body.removeChild(errorToast);
                        }, 3000);
                    });
                }
            });
        }
        
        // Confirm delete
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this school logo? This action cannot be undone.')) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
