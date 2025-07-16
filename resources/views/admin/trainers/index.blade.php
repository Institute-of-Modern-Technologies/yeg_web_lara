@extends('admin.dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Trainers Management</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.trainers.create-accounts') }}" class="px-4 py-2 bg-primary hover:bg-opacity-90 text-white rounded-lg shadow-sm transition-all duration-300">
                <i class="fas fa-user-plus mr-2"></i>Create Accounts for Approved Trainers
            </a>
            <a href="{{ route('admin.trainers.create') }}" class="px-4 py-2 bg-primary hover:bg-opacity-90 text-white rounded-lg shadow-sm transition-all duration-300">
                <i class="fas fa-plus mr-2"></i>Add New Trainer
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
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
                                        <div x-show="open" @click.away="open = false" 
                                            class="fixed inset-0 flex items-center z-[1000]" 
                                            style="background-color: rgba(0,0,0,0.2);"
                                            x-cloak>
                                            <div class="bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none w-64 max-w-md ml-auto mr-8" 
                                                 @click.stop>
                                                <div class="flex justify-between items-center p-3 border-b">
                                                    <h3 class="font-medium">Actions</h3>
                                                    <button @click="open = false" class="text-gray-400 hover:text-[#950713]">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="py-1">
                                                    <a href="{{ route('admin.trainers.show', $trainer) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#950713] transition-colors">
                                                        <i class="fas fa-eye mr-2"></i> View Details
                                                    </a>
                                                    <a href="{{ route('admin.trainers.edit', $trainer) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#950713] transition-colors">
                                                        <i class="fas fa-edit mr-2"></i> Edit
                                                    </a>
                                                    
                                                    @if($trainer->status != 'approved')
                                                    <form action="{{ route('admin.trainers.update-status', $trainer) }}" method="POST" class="block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="w-full text-left text-green-600 block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#950713] transition-colors">
                                                            <i class="fas fa-user-check mr-2"></i> Approve
                                                        </button>
                                                    </form>
                                                    @endif
                                                    
                                                    @if($trainer->status != 'rejected')
                                                    <form action="{{ route('admin.trainers.update-status', $trainer) }}" method="POST" class="block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#950713] transition-colors">
                                                            <i class="fas fa-user-times mr-2"></i> Reject
                                                        </button>
                                                    </form>
                                                    @endif
                                                    
                                                    @if($trainer->status != 'pending')
                                                    <form action="{{ route('admin.trainers.update-status', $trainer) }}" method="POST" class="block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="w-full text-left text-yellow-600 block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#950713] transition-colors">
                                                            <i class="fas fa-user-clock mr-2"></i> Mark Pending
                                                        </button>
                                                    </form>
                                                    @endif
                                                    
                                                    <form action="{{ route('admin.trainers.destroy', $trainer) }}" method="POST" class="block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="event.preventDefault(); confirmDelete('{{ $trainer->id }}');" class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#950713] transition-colors">
                                                            <i class="fas fa-trash-alt mr-2"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <form id="delete-form-{{ $trainer->id }}" action="{{ route('admin.trainers.destroy', $trainer) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById(`delete-form-${trainerId}`).submit();
                
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
</script>
@endpush
