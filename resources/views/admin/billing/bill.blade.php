@extends('admin.dashboard')

@section('title', 'Student Bill - ' . $student->full_name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Student Bill</h1>
            <p class="text-gray-600 mt-1">Billing statement for {{ $student->full_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.billing.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Billing
            </a>
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-print mr-2"></i> Print Bill
            </button>
            <button onclick="downloadPDF()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-download mr-2"></i> Download PDF
            </button>
        </div>
    </div>

    <!-- Bill Content -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden" id="billContent">
        <!-- Bill Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 text-white p-8">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-3xl font-bold mb-2">BILLING STATEMENT</h2>
                    <p class="text-purple-100">Young Experts Group</p>
                    <p class="text-purple-100 text-sm">Educational Excellence Program</p>
                </div>
                <div class="text-right">
                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <p class="text-sm text-purple-100">Bill Number</p>
                        <p class="text-xl font-bold">{{ $billNumber }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bill Details -->
        <div class="p-8">
            <!-- Student & Bill Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Student Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-user mr-2 text-purple-600"></i> Student Information
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="font-medium text-gray-900">{{ $student->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Registration Number</p>
                            <p class="font-medium text-gray-900">{{ $student->registration_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Program</p>
                            <p class="font-medium text-gray-900">{{ $student->programType->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">School</p>
                            <p class="font-medium text-gray-900">{{ $student->display_school_name ?? 'N/A' }}</p>
                        </div>
                        @if($student->email)
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium text-gray-900">{{ $student->email }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Bill Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-file-invoice mr-2 text-purple-600"></i> Bill Information
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Bill Date</p>
                            <p class="font-medium text-gray-900">{{ $billDate }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Due Date</p>
                            <p class="font-medium text-gray-900">{{ $dueDate }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            @if($balance <= 0)
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Paid in Full
                                </span>
                            @elseif($totalPaid > 0)
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Partially Paid
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Unpaid
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Summary -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-calculator mr-2 text-purple-600"></i> Billing Summary
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-dollar-sign text-2xl text-blue-600"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Total Amount Due</p>
                        <p class="text-2xl font-bold text-gray-900">GH₵ {{ number_format($amountToBePaid, 2) }}</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-credit-card text-2xl text-green-600"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Amount Paid</p>
                        <p class="text-2xl font-bold text-green-600">GH₵ {{ number_format($totalPaid, 2) }}</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-{{ $balance > 0 ? 'red' : 'green' }}-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-{{ $balance > 0 ? 'exclamation-triangle' : 'check-circle' }} text-2xl text-{{ $balance > 0 ? 'red' : 'green' }}-600"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Outstanding Balance</p>
                        <p class="text-2xl font-bold text-{{ $balance > 0 ? 'red' : 'green' }}-600">GH₵ {{ number_format($balance, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($payments->count() > 0)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-history mr-2 text-purple-600"></i> Payment History
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-700">Date</th>
                                <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-700">Amount</th>
                                <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-700">Discount</th>
                                <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-700">Final Amount</th>
                                <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-700">Method</th>
                                <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-200 px-4 py-3 text-sm">{{ $payment->created_at->format('M d, Y') }}</td>
                                <td class="border border-gray-200 px-4 py-3 text-sm font-medium">GH₵ {{ number_format($payment->amount, 2) }}</td>
                                <td class="border border-gray-200 px-4 py-3 text-sm">GH₵ {{ number_format($payment->discount ?? 0, 2) }}</td>
                                <td class="border border-gray-200 px-4 py-3 text-sm font-semibold text-green-600">GH₵ {{ number_format($payment->final_amount, 2) }}</td>
                                <td class="border border-gray-200 px-4 py-3 text-sm capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                                <td class="border border-gray-200 px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Payment Instructions -->
            @if($balance > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-3">
                    <i class="fas fa-info-circle mr-2"></i> Payment Instructions
                </h3>
                <div class="text-sm text-yellow-700 space-y-2">
                    <p><strong>Outstanding Amount:</strong> GH₵ {{ number_format($balance, 2) }}</p>
                    <p><strong>Due Date:</strong> {{ $dueDate }}</p>
                    <p><strong>Payment Methods:</strong> Cash, Mobile Money, Bank Transfer</p>
                    <p><strong>Contact:</strong> Please contact the admin office for payment arrangements.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Bill Footer -->
        <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
            <div class="text-center text-sm text-gray-500">
                <p><strong>Young Experts Group</strong> - Educational Excellence Program</p>
                <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
                <p class="mt-2">This is an official billing statement. Please keep for your records.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function downloadPDF() {
    Swal.fire({
        title: 'Generating PDF...',
        text: 'Please wait while we prepare your bill for download',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // For now, we'll use the browser's print functionality
    // In the future, this can be enhanced with a proper PDF library
    setTimeout(() => {
        Swal.close();
        window.print();
    }, 1000);
}

// Print styles
const printStyles = `
    <style media="print">
        body * { visibility: hidden; }
        #billContent, #billContent * { visibility: visible; }
        #billContent { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        @page { margin: 0.5in; }
    </style>
`;
document.head.insertAdjacentHTML('beforeend', printStyles);
</script>
@endsection
