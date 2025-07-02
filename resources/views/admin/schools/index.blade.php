@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Schools</h1>
        <a href="{{ route('admin.schools.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
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
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-school mr-2 text-primary"></i>
                <span>Registered Schools</span>
            </h2>
            <div class="relative w-full sm:w-auto">
                <input type="text" id="school-search" placeholder="Search schools..." class="w-full sm:w-auto pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
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
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center mb-3 gap-3">
                        <div class="flex-shrink-0 h-12 w-12">
                            @if($school->logo)
                            <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }} Logo">
                            @else
                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-school text-gray-400"></i>
                            </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $school->name }}</h3>
                            @php
                                $statusAttr = [
                                    'active' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-circle', 'label' => 'Approved'],
                                    'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-clock', 'label' => 'Pending'],
                                    'rejected' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fa-times-circle', 'label' => 'Rejected']
                                ][$school->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-question-circle', 'label' => 'Unknown'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusAttr['class'] }}">
                                <i class="fas {{ $statusAttr['icon'] }} mr-1"></i>
                                {{ $statusAttr['label'] }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                        <div>
                            <span class="block text-gray-500">Contact</span>
                            <span>{{ $school->phone }}</span>
                            @if($school->email)
                            <span class="block text-blue-500">{{ $school->email }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-gray-500">Owner</span>
                            <span>{{ $school->owner_name }}</span>
                            <span class="block">{{ $school->owner_phone }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t pt-3 flex justify-end space-x-3">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-indigo-600 hover:bg-indigo-50 p-2 rounded-md">
                                <i class="fas fa-exchange-alt mr-1"></i> Status
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" style="display: none;">
                                <div class="py-1">
                                    <form method="POST" action="{{ route('admin.schools.update-status', $school->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="active">
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
    
    // Filter schools
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('school-search');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('#schools-table-body tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
