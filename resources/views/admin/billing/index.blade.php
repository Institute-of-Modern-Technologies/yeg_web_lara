@extends('admin.dashboard')

@section('title', 'Student Billing Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Student Billing</h1>
            <p class="text-gray-600 mt-1">Manage student payments and billing information</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Students Card -->
        <div class="card shadow-lg rounded-lg overflow-hidden border-l-4 border-blue-500">
            <div class="bg-gradient-to-r from-blue-50 to-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-1">Total Students</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalStudents) }}</p>
                    </div>
                    <div class="bg-blue-500 p-3 rounded-lg shadow-md">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amount to be Paid Card -->
        <div class="card shadow-lg rounded-lg overflow-hidden border-l-4 border-green-500">
            <div class="bg-gradient-to-r from-green-50 to-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-green-600 uppercase tracking-wider mb-1">Amount to be Paid</p>
                        <p class="text-2xl font-bold text-gray-800">GH₵ {{ number_format($totalAmountToBePaid, 2) }}</p>
                    </div>
                    <div class="bg-green-500 p-3 rounded-lg shadow-md">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amount Paid Card -->
        <div class="card shadow-lg rounded-lg overflow-hidden border-l-4 border-indigo-500">
            <div class="bg-gradient-to-r from-indigo-50 to-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-indigo-600 uppercase tracking-wider mb-1">Amount Paid</p>
                        <p class="text-2xl font-bold text-gray-800">GH₵ {{ number_format($totalAmountPaid, 2) }}</p>
                    </div>
                    <div class="bg-indigo-500 p-3 rounded-lg shadow-md">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outstanding Balance Card -->
        <div class="card shadow-lg rounded-lg overflow-hidden border-l-4 border-yellow-500">
            <div class="bg-gradient-to-r from-yellow-50 to-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-yellow-600 uppercase tracking-wider mb-1">Outstanding Balance</p>
                        <p class="text-2xl font-bold text-gray-800">GH₵ {{ number_format($totalOutstandingBalance, 2) }}</p>
                    </div>
                    <div class="bg-yellow-500 p-3 rounded-lg shadow-md">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Search & Filter</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.billing.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-xs font-medium text-gray-700 mb-2">Search Student</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-admin-purple focus:border-admin-purple" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by name or registration number">
                    </div>
                    <div>
                        <label for="program_type" class="block text-xs font-medium text-gray-700 mb-2">Program Type</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-admin-purple focus:border-admin-purple" 
                                id="program_type" 
                                name="program_type">
                            <option value="">All Programs</option>
                            @foreach($programTypes as $programType)
                                <option value="{{ $programType->id }}" {{ request('program_type') == $programType->id ? 'selected' : '' }}>
                                    {{ $programType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="payment_status" class="block text-xs font-medium text-gray-700 mb-2">Payment Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-admin-purple focus:border-admin-purple" 
                                id="payment_status" 
                                name="payment_status">
                            <option value="">All Status</option>
                            <option value="fully_paid" {{ request('payment_status') == 'fully_paid' ? 'selected' : '' }}>Fully Paid</option>
                            <option value="partially_paid" {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">&nbsp;</label>
                        <button type="submit" class="w-full bg-admin-purple hover:bg-admin-purple-dark text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Student Billing Table -->
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Student Billing Information</h3>
        </div>
        <div>
            <div class="overflow-hidden">
                <table class="w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Student</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Program</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">School</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Amount Due</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Amount Paid</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Balance</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                            @php
                                $amountToBePaid = $student->fee_amount ?? 0;
                                $totalPaid = $student->total_paid ?? 0;
                                $balance = $amountToBePaid - $totalPaid;
                                
                                if ($balance <= 0) {
                                    $paymentStatus = 'Fully Paid';
                                    $statusClass = 'bg-green-100 text-green-800';
                                } elseif ($totalPaid > 0) {
                                    $paymentStatus = 'Partially Paid';
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                } else {
                                    $paymentStatus = 'Unpaid';
                                    $statusClass = 'bg-red-100 text-red-800';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">
                                    <div class="text-xs font-medium text-gray-900 truncate">{{ $student->full_name }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $student->registration_number }}</div>
                                </td>
                                <td class="px-6 py-4 overflow-hidden">
                                    <div class="text-xs truncate">{{ $student->programType->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 overflow-hidden">
                                    <div class="text-xs truncate">{{ $student->display_school_name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-semibold text-gray-900">GH₵ {{ number_format($amountToBePaid, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-semibold text-green-600">GH₵ {{ number_format($totalPaid, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-semibold {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">GH₵ {{ number_format($balance, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ $paymentStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" class="bg-gray-100 rounded-full p-1 hover:bg-gray-200 focus:outline-none">
                                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" style="display: none;">
                                            <div class="py-1" role="menu" aria-orientation="vertical">
                                                <a href="{{ route('admin.payments.student', $student->id) }}" class="text-gray-700 block px-4 py-2 text-xs hover:bg-gray-100">
                                                    <i class="fas fa-history mr-2"></i> History
                                                </a>
                                                <button type="button" onclick="openPaymentModal({{ $student->id }})" class="text-gray-700 block w-full text-left px-4 py-2 text-xs hover:bg-gray-100">
                                                    <i class="fas fa-plus mr-2"></i> Pay
                                                </button>
                                                <a href="{{ route('admin.billing.show', $student->id) }}" class="text-gray-700 block px-4 py-2 text-xs hover:bg-gray-100">
                                                    <i class="fas fa-eye mr-2"></i> View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium mb-2">No students found</p>
                                        <p class="text-xs mb-4">No students match your current search criteria.</p>
                                        <a href="{{ route('admin.billing.index') }}" 
                                           class="bg-admin-purple hover:bg-admin-purple-dark text-white px-4 py-2 rounded-lg transition-colors duration-200 inline-flex items-center">
                                            <i class="fas fa-refresh mr-2"></i>
                                            Clear Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-6 px-6 pb-6">
                <div class="text-xs text-gray-700">
                    Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() ?? 0 }} results
                </div>
                <div>
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Add any billing-specific JavaScript here if needed
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit search form on select change for better UX
    const searchForm = document.querySelector('form[method="GET"]');
    const selectElements = searchForm.querySelectorAll('select');
    
    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            searchForm.submit();
        });
    });
});
</script>
@endsection
