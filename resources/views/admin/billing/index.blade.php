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
        <div class="table-container" style="position: relative; overflow: visible;">
            <div class="overflow-x-auto overflow-y-visible">
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
                                    <div class="table-dropdown relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" class="bg-gray-100 rounded-full p-1 hover:bg-gray-200 focus:outline-none">
                                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="dropdown-menu" style="display: none;">
                                            <div class="py-1" role="menu" aria-orientation="vertical">
                                                <a href="{{ route('admin.payments.student', $student->id) }}" class="text-gray-700 block px-4 py-2 text-xs hover:bg-gray-100">
                                                    <i class="fas fa-history mr-2"></i> History
                                                </a>
                                                <button type="button" onclick="openPaymentModal({{ $student->id }})" class="text-gray-700 block w-full text-left px-4 py-2 text-xs hover:bg-gray-100">
                                                    <i class="fas fa-plus mr-2"></i> Pay
                                                </button>
                                                <button type="button" onclick="viewReceipt({{ $student->id }})" class="text-gray-700 block w-full text-left px-4 py-2 text-xs hover:bg-gray-100">
                                                    <i class="fas fa-receipt mr-2"></i> View Receipt
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
<style>
/* Simple dropdown positioning for table */
.table-dropdown {
    position: relative;
}

.table-dropdown .dropdown-menu {
    position: absolute !important;
    z-index: 1000 !important;
    right: 0;
    top: 100%;
    min-width: 140px;
    background: white;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

/* For last few rows, show dropdown upward */
tbody tr:nth-last-child(-n+3) .dropdown-menu {
    top: auto !important;
    bottom: 100% !important;
}

/* For dropdowns near the right edge, position to left */
tbody tr td:last-child .dropdown-menu {
    right: 0 !important;
    left: auto !important;
}

/* Ensure table container allows dropdown overflow */
.table-container {
    overflow: visible !important;
}

/* Basic dropdown item styling */
.dropdown-menu a,
.dropdown-menu button {
    display: block;
    width: 100%;
    padding: 8px 16px;
    color: #333;
    text-decoration: none;
    background: transparent;
    border: 0;
    text-align: left;
}

.dropdown-menu a:hover,
.dropdown-menu button:hover {
    background-color: #f5f5f5;
}
</style>

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

// Generate Bill function with WhatsApp and Email options
function generateBill(studentId) {
    console.log('generateBill called with studentId:', studentId);
    
    // Show options modal
    Swal.fire({
        title: 'Send Bill',
        text: 'How would you like to send the billing statement?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fab fa-whatsapp mr-2"></i> Send via WhatsApp',
        cancelButtonText: '<i class="fas fa-envelope mr-2"></i> Send via Email',
        showDenyButton: true,
        denyButtonText: '<i class="fas fa-eye mr-2"></i> View Bill',
        confirmButtonColor: '#25D366',
        cancelButtonColor: '#3085d6',
        denyButtonColor: '#6c757d',
        reverseButtons: true,
        customClass: {
            confirmButton: 'swal2-confirm-whatsapp',
            cancelButton: 'swal2-cancel-email',
            denyButton: 'swal2-deny-view'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send via WhatsApp
            sendBillViaWhatsApp(studentId);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Send via Email
            sendBillViaEmail(studentId);
        } else if (result.isDenied) {
            // View Bill
            viewBill(studentId);
        }
    });
}

// View receipt function
function viewReceipt(studentId) {
    console.log('viewReceipt called with studentId:', studentId);
    
    // Show loading indicator
    Swal.fire({
        title: 'Loading...',
        text: 'Fetching receipt details',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Use fetch to check if student has payments before redirecting
    fetch(`/admin/billing/check-payments/${studentId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success) {
            // Redirect to the receipt page
            window.location.href = `/admin/billing/latest-receipt/${studentId}`;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'No Payments Found',
                text: data.message || 'This student has no payment records to generate a receipt.',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error checking payments:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while checking payment records. Please try again.',
            confirmButtonText: 'OK'
        });
    });
}

// Send bill via WhatsApp
function sendBillViaWhatsApp(studentId) {
    // First, get the bill information
    fetch(`/admin/billing/get-bill-info/${studentId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show input form with pre-filled message
            Swal.fire({
                title: 'Send Bill via WhatsApp',
                html: `
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student Phone Number:</label>
                        <input type="text" id="whatsapp-phone" value="${data.phone || ''}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md mb-4" 
                               placeholder="Enter phone number">
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Message:</label>
                        <textarea id="whatsapp-message" rows="12" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                  placeholder="Enter WhatsApp message">${data.whatsapp_message}</textarea>
                    </div>
                `,
                width: 600,
                showCancelButton: true,
                confirmButtonText: '<i class="fab fa-whatsapp mr-2"></i> Send via WhatsApp',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#25D366',
                preConfirm: () => {
                    const phone = document.getElementById('whatsapp-phone').value.trim();
                    const message = document.getElementById('whatsapp-message').value.trim();
                    
                    if (!phone) {
                        Swal.showValidationMessage('Please enter a phone number');
                        return false;
                    }
                    
                    if (!message) {
                        Swal.showValidationMessage('Please enter a message');
                        return false;
                    }
                    
                    return { phone, message };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { phone, message } = result.value;
                    
                    // Format phone number and create WhatsApp URL
                    let formattedPhone = phone.replace(/[^0-9]/g, '');
                    if (!formattedPhone.startsWith('233') && formattedPhone.length === 10) {
                        formattedPhone = '233' + formattedPhone.substring(1);
                    }
                    
                    const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(message)}`;
                    
                    // Open WhatsApp
                    window.open(whatsappUrl, '_blank');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'WhatsApp Opened!',
                        text: 'WhatsApp has been opened with your custom message.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to load bill information.'
            });
        }
    })
    .catch(error => {
        console.error('Error loading bill info:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while loading bill information.'
        });
    });
}

// Send bill via Email
function sendBillViaEmail(studentId) {
    // First, get the bill information
    fetch(`/admin/billing/get-bill-info/${studentId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show input form with pre-filled email content
            Swal.fire({
                title: 'Send Bill via Email',
                html: `
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student Email:</label>
                        <input type="email" id="email-address" value="${data.email || ''}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md mb-4" 
                               placeholder="Enter email address">
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Subject:</label>
                        <input type="text" id="email-subject" value="${data.email_subject || ''}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md mb-4" 
                               placeholder="Enter email subject">
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Message:</label>
                        <textarea id="email-message" rows="10" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                  placeholder="Enter email message">${data.email_message}</textarea>
                    </div>
                `,
                width: 700,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-envelope mr-2"></i> Send Email',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                preConfirm: () => {
                    const email = document.getElementById('email-address').value.trim();
                    const subject = document.getElementById('email-subject').value.trim();
                    const message = document.getElementById('email-message').value.trim();
                    
                    if (!email) {
                        Swal.showValidationMessage('Please enter an email address');
                        return false;
                    }
                    
                    if (!subject) {
                        Swal.showValidationMessage('Please enter an email subject');
                        return false;
                    }
                    
                    if (!message) {
                        Swal.showValidationMessage('Please enter an email message');
                        return false;
                    }
                    
                    return { email, subject, message };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { email, subject, message } = result.value;
                    
                    // Show sending indicator
                    Swal.fire({
                        title: 'Sending Email...',
                        text: 'Please wait while we send the email',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Send email with custom content
                    fetch(`/admin/billing/send-email/${studentId}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            email: email,
                            subject: subject,
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Email Sent!',
                                text: `Email sent successfully to ${email}`,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to send email. Please try again.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error sending email:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while sending email. Please try again.'
                        });
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to load bill information.'
            });
        }
    })
    .catch(error => {
        console.error('Error loading bill info:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while loading bill information.'
        });
    });
}

// View bill directly
function viewBill(studentId) {
    // Open bill in new tab/window
    window.open(`/admin/billing/generate/${studentId}`, '_blank');
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

// View receipt for student
function viewReceipt(studentId) {
    // Show loading indicator
    Swal.fire({
        title: 'Loading...',
        text: 'Checking for payment records',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // First check if the student has any payments before trying to show receipt
    fetch(`/admin/billing/check-payments/${studentId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Student has payments, open receipt in new tab
            window.location.href = `/admin/billing/latest-receipt/${studentId}`;
        } else {
            // No payments found
            Swal.fire({
                icon: 'info',
                title: 'No Payments',
                text: 'This student has no payment records yet.',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error checking payment records:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while checking for payment records. Please try again.'
        });
    });
}
</script>
@endsection
