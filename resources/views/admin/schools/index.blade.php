@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Schools</h1>
        <a href="{{ route('admin.schools.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg shadow-md hover:bg-red-800 transition-all duration-300 flex items-center w-full sm:w-auto justify-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Register New School</span>
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

    <!-- School List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-0 sm:px-0 py-0 border-b border-gray-200 bg-gradient-to-r from-primary to-red-800 text-white">
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="font-semibold text-white flex items-center">
                    <i class="fas fa-school mr-2"></i>
                    <span>Registered Schools</span>
                </h2>
            </div>
            <div class="bg-white rounded-t-lg mx-4 sm:mx-6 p-4 -mb-px shadow-lg transform -translate-y-1">
                <form action="{{ route('admin.schools.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative w-full sm:flex-grow group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors duration-300">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name, email, location..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary shadow-sm transition-all duration-300">
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-lg shadow-md hover:bg-red-800 transition-all duration-300 flex items-center justify-center min-w-[90px] group w-full sm:w-auto">
                            <i class="fas fa-search mr-2 group-hover:animate-pulse"></i>Search
                        </button>
                        @if(request('search'))
                        <a href="{{ route('admin.schools.index') }}" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow-md transition-all duration-300 flex items-center justify-center min-w-[90px] w-full sm:w-auto">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($schools->isEmpty())
        <div class="p-6 text-center text-gray-500">
            <p>No schools found. Click "Register New School" to add one.</p>
        </div>
        @else
        <!-- Mobile Card View (visible on small screens) -->
        <div class="block md:hidden">
            <div class="space-y-4 p-4">
                @foreach($schools as $school)
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-3 gap-3">
                        <div class="flex-shrink-0 h-14 w-14">
                            @if($school->logo)
                            <img class="h-14 w-14 rounded-full object-cover shadow-sm" src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }} Logo">
                            @else
                            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-primary/10 to-primary/20 flex items-center justify-center shadow-sm">
                                <i class="fas fa-school text-primary text-lg"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 truncate">{{ $school->name }}</h3>
                            @php
                                $statusAttr = [
                                    'active' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-circle', 'label' => 'Approved'],
                                    'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-clock', 'label' => 'Pending'],
                                    'rejected' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fa-times-circle', 'label' => 'Rejected']
                                ][$school->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-question-circle', 'label' => 'Unknown'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-full text-xs font-medium {{ $statusAttr['class'] }}">
                                <i class="fas {{ $statusAttr['icon'] }} mr-1"></i>
                                {{ $statusAttr['label'] }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex items-start gap-2">
                            <div class="text-gray-400 w-5 pt-0.5"><i class="fas fa-phone"></i></div>
                            <div>{{ $school->phone ?: 'No phone number' }}</div>
                        </div>
                        @if($school->email)
                        <div class="flex items-start gap-2">
                            <div class="text-gray-400 w-5 pt-0.5"><i class="fas fa-envelope"></i></div>
                            <div class="text-blue-600 break-all">{{ $school->email }}</div>
                        </div>
                        @endif
                        <div class="flex items-start gap-2">
                            <div class="text-gray-400 w-5 pt-0.5"><i class="fas fa-map-marker-alt"></i></div>
                            <div>{{ $school->city ?? 'N/A' }}{{ $school->state ? ', '.$school->state : '' }}</div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-3 flex flex-wrap justify-end gap-2">
                        <a href="{{ route('admin.schools.show', $school->id) }}" class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                            <i class="fas fa-eye mr-1.5"></i> View
                        </a>
                        
                        <a href="{{ route('admin.schools.edit', $school->id) }}" class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                            <i class="fas fa-edit mr-1.5"></i> Edit
                        </a>

                        <button onclick="confirmDelete({{ $school->id }})" class="px-3 py-1.5 text-xs bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-colors flex items-center">
                            <i class="fas fa-trash-alt mr-1.5"></i> Delete
                        </button>
                        
                        <!-- Status change dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="px-3 py-1.5 text-xs bg-indigo-50 text-indigo-600 rounded-md hover:bg-indigo-100 transition-colors flex items-center">
                                <i class="fas fa-exchange-alt mr-1.5"></i> Status
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 overflow-hidden" style="display: none;">
                                <div class="py-1 text-xs">
                                    <div class="px-3 py-2 bg-gray-50 text-gray-500 font-medium">Change status to:</div>
                                    <form method="POST" action="{{ route('admin.schools.update-status', $school->id) }}" class="status-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-green-700 hover:bg-green-50 flex items-center transition-colors duration-200">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Approve School
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.schools.update-status', $school->id) }}" class="status-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-yellow-600 hover:bg-yellow-50 flex items-center transition-colors duration-200">
                                            <i class="fas fa-clock mr-2"></i>
                                            Mark as Pending
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.schools.update-status', $school->id) }}" class="status-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-red-700 hover:bg-red-50 flex items-center transition-colors duration-200">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Reject School
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.schools.edit', $school->id) }}" class="text-blue-600 hover:bg-blue-50 p-2 rounded-md">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <button onclick="confirmDelete({{ $school->id }})" class="text-red-600 hover:bg-red-50 p-2 rounded-md">
                            <i class="fas fa-trash-alt mr-1"></i> Delete
                        </button>
                        <form id="delete-form-{{ $school->id }}" action="{{ route('admin.schools.destroy', $school->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $schools->links() }}
            </div>
        </div>
        
        <!-- Desktop Table View (hidden on small screens) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            School
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact Info
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Owner
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="schools-table-body">
                    @foreach($schools as $school)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($school->logo)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }} Logo">
                                    @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-school text-gray-400"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $school->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $school->location }}</div>
                                    @if($school->gps_coordinates)
                                    <div class="text-xs text-gray-400">GPS: {{ $school->gps_coordinates }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $school->phone }}</div>
                            @if($school->email)
                            <div class="text-sm text-gray-500">{{ $school->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $school->owner_name }}</div>
                            @if($school->avg_students)
                            <div class="text-sm text-gray-500">~{{ $school->avg_students }} students</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php $statusAttr = $school->getStatusAttributes(); @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusAttr['color'] }}">
                                <i class="fas {{ $statusAttr['icon'] }} mr-1"></i>
                                {{ $statusAttr['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <!-- Status Change -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" style="display: none;">
                                        <div class="py-1">
                                            <form method="POST" action="{{ route('admin.schools.update-status', $school->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-green-700 hover:bg-gray-100">
                                                    <i class="fas fa-check-circle mr-2"></i> Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.schools.update-status', $school->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-yellow-700 hover:bg-gray-100">
                                                    <i class="fas fa-clock mr-2"></i> Mark as Pending
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.schools.update-status', $school->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                                    <i class="fas fa-times-circle mr-2"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Edit -->
                                <a href="{{ route('admin.schools.edit', $school->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Delete -->
                                <button onclick="confirmDelete({{ $school->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $school->id }}" action="{{ route('admin.schools.destroy', $school->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $schools->links() }}
        </div>
        @endif
    </div>
</div>

<!-- JavaScript for interactions -->
<script>
    // Delete confirmation
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
    
    // Listen to all status form submissions
    document.addEventListener('DOMContentLoaded', function() {
        const statusForms = document.querySelectorAll('.status-form');
        statusForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const status = this.querySelector('input[name="status"]').value;
                const originalForm = this;
                
                // Configure alert based on status
                let config = {
                    title: '',
                    text: '',
                    icon: 'question',
                    confirmButtonColor: '#950713', // Brand color
                    cancelButtonColor: '#6B7280'
                };
                
                if (status === 'active') {
                    config.title = 'Approve School';
                    config.text = 'Are you sure you want to approve this school?';
                    config.icon = 'question';
                    config.confirmButtonColor = '#10B981'; // Green for approve
                } else if (status === 'rejected') {
                    config.title = 'Reject School';
                    config.text = 'Are you sure you want to reject this school?';
                    config.icon = 'warning';
                    config.confirmButtonColor = '#EF4444'; // Red for reject
                } else if (status === 'pending') {
                    config.title = 'Mark as Pending';
                    config.text = 'Are you sure you want to mark this school as pending?';
                    config.icon = 'info';
                    config.confirmButtonColor = '#F59E0B'; // Yellow for pending
                }
                
                Swal.fire({
                    title: config.title,
                    text: config.text,
                    icon: config.icon,
                    showCancelButton: true,
                    confirmButtonColor: config.confirmButtonColor,
                    cancelButtonColor: config.cancelButtonColor,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the original form
                        originalForm.removeEventListener('submit', arguments.callee);
                        originalForm.submit();
                        
                        // Show processing message
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
