@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Program Fees</h1>
        <a href="{{ route('admin.fees.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Fee</span>
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

    <!-- Search Bar -->
    <div class="mb-6">
        <form action="{{ route('admin.fees.index') }}" method="GET" class="flex">
            <div class="relative flex-grow max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Search fees...">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <button type="submit" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Search
            </button>
        </form>
    </div>

    <!-- Fee List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-money-bill-wave mr-2 text-primary"></i>
                <span>Program Fees</span>
            </h2>
        </div>

        @if($fees->isEmpty())
        <div class="p-6 text-center text-gray-500">
            <p>No program fees found. Click "Add New Fee" to create one.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Program
                        </th>
                        <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            School
                        </th>
                        <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Partner Discount
                        </th>
                        <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            School Comm.
                        </th>
                        <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IMT Comm.
                        </th>
                        <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($fees as $fee)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2 whitespace-nowrap">
                            <div class="text-xs font-medium text-gray-900">{{ $fee->programType->name }}</div>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-900">{{ $fee->school ? $fee->school->name : 'All Schools' }}</div>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-900">{{ number_format($fee->amount, 2) }}</div>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-900">{{ number_format($fee->partner_discount, 2) }}</div>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-900">{{ $fee->school_commission ? number_format($fee->school_commission, 2) : '-' }}</div>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-900">{{ $fee->imt_commission ? number_format($fee->imt_commission, 2) : '-' }}</div>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <span class="px-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $fee->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap text-right text-xs font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.fees.edit', $fee) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.fees.toggle-status', $fee) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $fee->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}" title="{{ $fee->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $fee->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.fees.destroy', $fee) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $fees->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    // Confirm delete
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this fee?')) {
                this.submit();
            }
        });
    });
</script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete confirmation
        const deleteForms = document.querySelectorAll('.delete-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
