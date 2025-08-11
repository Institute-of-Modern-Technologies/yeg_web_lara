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
        <div>
            <!-- Test Modal Button -->
            <button onclick="alert('JavaScript is working!')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg mr-2">
                Test JS
            </button>
            <button onclick="testModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg mr-2">
                Test Modal
            </button>
            <button onclick="openPaymentModal(1)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                Test Payment Modal
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Students Card -->
        <div class="card shadow-lg rounded-lg overflow-hidden border-l-4 border-blue-500">
            <div class="bg-gradient-to-r from-blue-50 to-white p-3">
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
            <div class="bg-gradient-to-r from-green-50 to-white p-3">
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
            <div class="bg-gradient-to-r from-indigo-50 to-white p-3">
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
            <div class="bg-gradient-to-r from-yellow-50 to-white p-3">
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
                                                <button type="button" onclick="generateBill({{ $student->id }})" class="text-gray-700 block w-full text-left px-4 py-2 text-xs hover:bg-gray-100">
                                                    <i class="fas fa-file-invoice mr-2"></i> Generate Bill
                                                </button>
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

// Test function to verify SweetAlert2 is working
function testModal() {
    console.log('testModal called');
    
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded!');
        alert('SweetAlert2 is not loaded!');
        return;
    }
    
    console.log('SweetAlert2 is available, showing test modal...');
    
    Swal.fire({
        title: 'Test Modal',
        text: 'SweetAlert2 is working correctly!',
        icon: 'success',
        confirmButtonText: 'Great!'
    });
}

// Modal for payment entry
function openPaymentModal(studentId) {
    console.log('openPaymentModal called with studentId:', studentId);
    
    // Check if Swal is available
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 (Swal) is not loaded!');
        alert('Error: SweetAlert2 library is not loaded. Please refresh the page.');
        return;
    }
    
    console.log('SweetAlert2 is available, showing loading modal...');
    
    // Show loading indicator or feedback
    Swal.fire({
        title: 'Loading...',
        text: 'Preparing payment form',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Fetch student details and open payment modal
    fetch(`/admin/students/${studentId}/payment/create`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        Swal.close();
        
        // Create modal with payment form
        Swal.fire({
            title: 'Add New Payment',
            html: html,
            width: '600px',
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                container: 'payment-modal-container'
            },
            didOpen: () => {
                // Initialize any form elements if needed
                const paymentForm = document.getElementById('paymentForm');
                if (paymentForm) {
                    paymentForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        submitPaymentForm(this);
                    });
                    
                    // Initialize live calculation for payment form
                    initializePaymentCalculation();
                }
            }
        });
    })
    .catch(error => {
        console.error('Error loading payment form:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load payment form. Please try again.'
        });
    });
}

// Initialize payment form calculation
function initializePaymentCalculation() {
    // Add event listeners for live calculation
    const amountInput = document.getElementById('amount');
    const discountInput = document.getElementById('discount');
    
    if (amountInput && discountInput) {
        amountInput.addEventListener('input', calculateFinalAmount);
        discountInput.addEventListener('input', calculateFinalAmount);
        
        // Calculate initial value
        calculateFinalAmount();
    }
}

// Calculate final amount live
function calculateFinalAmount() {
    const amountInput = document.getElementById('amount');
    const discountInput = document.getElementById('discount');
    const finalAmountInput = document.getElementById('final_amount');
    
    if (amountInput && discountInput && finalAmountInput) {
        const amount = parseFloat(amountInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const finalAmount = Math.max(0, amount - discount).toFixed(2);
        
        finalAmountInput.value = finalAmount;
        
        console.log('Calculated final amount:', finalAmount, 'from amount:', amount, 'minus discount:', discount);
    }
}

// Generate Bill function
function generateBill(studentId) {
    console.log('generateBill called with studentId:', studentId);
    
    // Show loading indicator
    Swal.fire({
        title: 'Generating Bill...',
        text: 'Preparing student billing statement',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Generate bill via AJAX
    fetch(`/admin/billing/generate/${studentId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Failed to generate bill');
    })
    .then(blob => {
        Swal.close();
        
        // Create download link for PDF
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = `student-bill-${studentId}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Bill Generated!',
            text: 'The billing statement has been downloaded successfully.',
            confirmButtonText: 'OK'
        });
    })
    .catch(error => {
        console.error('Error generating bill:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to generate bill. Please try again.'
        });
    });
}

function submitPaymentForm(form) {
    const formData = new FormData(form);
    const submitUrl = form.getAttribute('action');
    
    // Show loading indicator
    Swal.fire({
        title: 'Processing...',
        text: 'Saving payment information',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Submit the form via AJAX
    fetch(submitUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Payment successfully recorded',
                confirmButtonText: 'View Receipt',
                showCancelButton: true,
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed && data.receipt_url) {
                    window.location.href = data.receipt_url;
                } else {
                    // Refresh the current page to show updated data
                    window.location.reload();
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to record payment. Please try again.'
            });
        }
    })
    .catch(error => {
        console.error('Error submitting payment:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while processing your request. Please try again.'
        });
    });
}
</script>
@endsection
