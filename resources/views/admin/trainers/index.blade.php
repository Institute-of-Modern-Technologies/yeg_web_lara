@extends('admin.dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Trainers Management</h1>
        <a href="{{ route('admin.trainers.create') }}" class="px-4 py-2 bg-primary hover:bg-red-800 text-white rounded-lg shadow-md transition-all duration-300 flex items-center w-full sm:w-auto justify-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Trainer</span>
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Modern Search Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100 transform transition-all duration-300 hover:shadow-xl">
        <div class="bg-gradient-to-r from-primary to-red-800 py-3 px-5">
            <h3 class="text-white font-medium flex items-center">
                <i class="fas fa-filter mr-2"></i> Find Trainers
            </h3>
        </div>
        <div class="p-5">
            <form action="{{ route('admin.trainers.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-5">
                <div class="flex-grow relative group">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300 group-focus-within:text-primary">Search</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..." class="w-full rounded-lg border-gray-300 pl-10 shadow-sm transition-all duration-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors duration-300">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>
                
                <div class="w-full md:w-auto relative group">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300 group-focus-within:text-primary">Status</label>
                    <div class="relative">
                        <select name="status" id="status" class="w-full md:w-auto rounded-lg pl-10 border-gray-300 shadow-sm transition-all duration-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 pr-8 appearance-none">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors duration-300">
                            <i class="fas fa-filter"></i>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2.5 bg-primary hover:bg-red-800 text-white rounded-lg shadow-md transition-all duration-300 flex items-center justify-center min-w-[90px] group">
                        <i class="fas fa-search mr-2 group-hover:animate-pulse"></i>Search
                    </button>
                    @if(request('search') || request('status'))
                    <a href="{{ route('admin.trainers.index') }}" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow-md transition-all duration-300 flex items-center justify-center min-w-[90px]">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile-First Responsive Design for Trainers -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Mobile Card View (visible on small screens) -->
        <div class="block md:hidden">
            <div class="divide-y divide-gray-200">
                @forelse($trainers as $trainer)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex justify-between items-start">
                            <!-- Trainer Info -->
                            <div class="flex items-center space-x-3">
                                <div class="h-12 w-12 rounded-full bg-primary bg-opacity-10 flex items-center justify-center text-primary">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $trainer->name }}</h3>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope text-gray-400 w-4"></i>
                                            <span class="ml-2">{{ $trainer->email }}</span>
                                        </div>
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-phone text-gray-400 w-4"></i>
                                            <span class="ml-2">{{ $trainer->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <div>
                                @if($trainer->status == 'pending')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($trainer->status == 'approved')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @elseif($trainer->status == 'rejected')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Action Buttons for Mobile -->
                        <div class="mt-4 flex justify-between items-center border-t border-gray-100 pt-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <button onclick="confirmDelete({{ $trainer->id }})" class="px-3 py-1.5 text-xs bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-colors flex items-center">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </div>
                            
                            @if($trainer->status == 'pending')
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('admin.trainers.update-status', $trainer->id) }}" id="mobile-approve-form-{{ $trainer->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="button" onclick="confirmStatusChange({{ $trainer->id }}, 'approve', true)" class="px-3 py-1.5 text-xs bg-green-50 text-green-600 rounded-md hover:bg-green-100 transition-colors flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i> Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.trainers.update-status', $trainer->id) }}" id="mobile-reject-form-{{ $trainer->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="button" onclick="confirmStatusChange({{ $trainer->id }}, 'reject', true)" class="px-3 py-1.5 text-xs bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-colors flex items-center">
                                        <i class="fas fa-times-circle mr-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-user-slash text-gray-300 text-5xl mb-3"></i>
                        <p>No trainers found</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Desktop Table View (visible on medium screens and up) -->
        <div class="hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($trainers as $trainer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary bg-opacity-10 flex items-center justify-center text-primary">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $trainer->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $trainer->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $trainer->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($trainer->status == 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($trainer->status == 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @elseif($trainer->status == 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium relative">
                                <div class="flex justify-center items-center">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click.prevent="open = !open" class="text-gray-500 hover:text-[#950713]">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                            <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-edit mr-2"></i> Edit
                                            </a>
                                            <a href="#" onclick="confirmDelete({{ $trainer->id }})" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                <i class="fas fa-trash-alt mr-2"></i> Delete
                                            </a>
                                            @if($trainer->status == 'pending')
                                            <form method="POST" action="{{ route('admin.trainers.update-status', $trainer->id) }}" id="approve-form-{{ $trainer->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="button" onclick="confirmStatusChange({{ $trainer->id }}, 'approve')" class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                                                    <i class="fas fa-check-circle mr-2"></i> Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.trainers.update-status', $trainer->id) }}" id="reject-form-{{ $trainer->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="button" onclick="confirmStatusChange({{ $trainer->id }}, 'reject')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                    <i class="fas fa-times-circle mr-2"></i> Reject
                                                </button>
                                            </form>
                                            @endif
                                                    <form action="{{ route('admin.trainers.update-status', $trainer) }}" method="POST" class="block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#950713] transition-colors">
                                                            <i class="fas fa-user-times mr-2"></i> Reject
                                                        </button>
                                                    </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No trainers found. <a href="{{ route('admin.trainers.create') }}" class="text-primary hover:underline">Add one</a>!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Trainer delete confirmation
    function confirmDelete(trainerId) {
        event.preventDefault();
        event.stopPropagation();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#950713',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('admin.trainers.index') }}/${trainerId}`;
                form.style.display = 'none';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(method);
                document.body.appendChild(form);
                
                // Submit the form
                form.submit();
                
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
    
    // Status change confirmation
    function confirmStatusChange(trainerId, action, isMobile = false) {
        event.preventDefault();
        event.stopPropagation();
        
        const formPrefix = isMobile ? 'mobile-' : '';
        const formId = `${formPrefix}${action}-form-${trainerId}`;
        const statusText = action === 'approve' ? 'approve this trainer' : 'reject this trainer';
        const statusTitle = action === 'approve' ? 'Approve Trainer' : 'Reject Trainer';
        const buttonColor = action === 'approve' ? '#10B981' : '#EF4444';
        
        Swal.fire({
            title: statusTitle,
            text: `Are you sure you want to ${statusText}?`,
            icon: action === 'approve' ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: buttonColor,
            cancelButtonColor: '#6B7280',
            confirmButtonText: action === 'approve' ? 'Yes, approve!' : 'Yes, reject!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById(formId).submit();
                
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
    }
</script>
@endpush
