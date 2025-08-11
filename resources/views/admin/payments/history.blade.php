@extends('admin.dashboard')

@section('title', 'Payment History - ' . $student->full_name)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Payment History</h1>
            <p class="text-gray-600 mt-1">{{ $student->full_name }} - {{ $student->registration_number }}</p>
        </div>
        <div>
            <a href="{{ route('admin.billing.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg mr-2">
                <i class="fas fa-arrow-left mr-2"></i> Back to Billing
            </a>
            <button onclick="openPaymentModal({{ $student->id }})" class="bg-admin-purple hover:bg-admin-purple-dark text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i> Add Payment
            </button>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="card mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Student Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Full Name</p>
                    <p class="text-lg font-medium">{{ $student->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Registration Number</p>
                    <p class="text-lg font-medium">{{ $student->registration_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Program</p>
                    <p class="text-lg font-medium">{{ $student->programType->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">School</p>
                    <p class="text-lg font-medium">{{ $student->display_school_name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Summary -->
    @php
        $totalPaid = $payments->sum('final_amount');
        $amountToBePaid = $student->fee_amount ?? 0;
        $balance = $amountToBePaid - $totalPaid;
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Amount to be Paid -->
        <div class="card shadow-lg rounded-lg overflow-hidden border-l-4 border-blue-500">
            <div class="bg-gradient-to-r from-blue-50 to-white p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-1">Amount to be Paid</p>
                        <p class="text-2xl font-bold text-gray-800">GH₵ {{ number_format($amountToBePaid, 2) }}</p>
                    </div>
                    <div class="bg-blue-500 p-3 rounded-lg shadow-md">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Paid -->
        <div class="card shadow-lg rounded-lg overflow-hidden border-l-4 border-green-500">
            <div class="bg-gradient-to-r from-green-50 to-white p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-green-600 uppercase tracking-wider mb-1">Total Paid</p>
                        <p class="text-2xl font-bold text-gray-800">GH₵ {{ number_format($totalPaid, 2) }}</p>
                    </div>
                    <div class="bg-green-500 p-3 rounded-lg shadow-md">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance -->
        <div class="card shadow-lg rounded-lg overflow-hidden border-l-4 {{ $balance > 0 ? 'border-red-500' : 'border-green-500' }}">
            <div class="bg-gradient-to-r {{ $balance > 0 ? 'from-red-50' : 'from-green-50' }} to-white p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }} uppercase tracking-wider mb-1">Balance</p>
                        <p class="text-2xl font-bold text-gray-800">GH₵ {{ number_format($balance, 2) }}</p>
                    </div>
                    <div class="{{ $balance > 0 ? 'bg-red-500' : 'bg-green-500' }} p-3 rounded-lg shadow-md">
                        <i class="fas {{ $balance > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }} text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History Table -->
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Payment History ({{ $payments->count() }} payments)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">GH₵ {{ number_format($payment->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">GH₵ {{ number_format($payment->discount ?? 0, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-green-600">GH₵ {{ number_format($payment->final_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.payments.receipt', $payment->id) }}" 
                                   class="text-admin-purple hover:text-admin-purple-dark mr-3">
                                    <i class="fas fa-receipt mr-1"></i> Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-credit-card text-4xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium mb-2">No payments found</p>
                                    <p class="text-sm mb-4">This student hasn't made any payments yet.</p>
                                    <button onclick="openPaymentModal({{ $student->id }})" 
                                            class="bg-admin-purple hover:bg-admin-purple-dark text-white px-4 py-2 rounded-lg transition-colors duration-200 inline-flex items-center">
                                        <i class="fas fa-plus mr-2"></i>
                                        Add First Payment
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Modal for payment entry (reuse the same function from billing page)
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
