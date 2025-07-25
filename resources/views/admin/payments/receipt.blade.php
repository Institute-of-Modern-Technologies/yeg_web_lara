<!-- Receipt Modal Content -->
<div class="bg-white rounded-lg shadow-lg max-w-2xl mx-auto p-0 border border-gray-200">
    <!-- Receipt Header -->
    <div class="bg-primary text-white px-6 py-4 rounded-t-lg">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Payment Receipt</h3>
            <div>
                <span class="text-sm">Receipt #: <span class="font-mono">{{ $payment->reference_number }}</span></span>
            </div>
        </div>
    </div>
    
    <!-- Receipt Body -->
    <div class="p-6">
        <!-- School Info -->
        <div class="flex justify-between items-start border-b border-gray-200 pb-4 mb-4">
            <div>
                <h4 class="font-bold text-lg text-primary">Institute of Modern Technologies</h4>
                <p class="text-gray-600 text-sm">Ghana Branch</p>
                <p class="text-gray-600 text-sm">imtghanabranch@gmail.com</p>
            </div>
            <div class="text-right">
                <p class="text-gray-600 text-sm">Date: {{ $payment->created_at->format('M d, Y') }}</p>
                <p class="text-gray-600 text-sm">Time: {{ $payment->created_at->format('h:i A') }}</p>
            </div>
        </div>
        
        <!-- Student Info -->
        <div class="border-b border-gray-200 pb-4 mb-4">
            <h4 class="font-semibold mb-2">Student Information</h4>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <p class="text-sm"><span class="font-medium">Name:</span> {{ $payment->student->first_name }} {{ $payment->student->last_name }}</p>
                </div>
                <div>
                    <p class="text-sm"><span class="font-medium">ID:</span> {{ $payment->student->registration_number }}</p>
                </div>
                @if($payment->student->email)
                <div data-student-email="{{ $payment->student->email }}">
                    <p class="text-sm"><span class="font-medium">Email:</span> {{ $payment->student->email }}</p>
                </div>
                @endif
                @if($payment->student->phone)
                <div data-student-phone="{{ $payment->student->phone }}">
                    <p class="text-sm"><span class="font-medium">Phone:</span> {{ $payment->student->phone }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="border-b border-gray-200 pb-4 mb-4">
            <h4 class="font-semibold mb-2">Payment Details</h4>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-2 gap-y-2">
                    <div>
                        <p class="text-sm"><span class="font-medium">Amount:</span> ₵{{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm"><span class="font-medium">Discount:</span> ₵{{ number_format($payment->discount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm"><span class="font-medium">Payment Method:</span> {{ ucfirst($payment->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-sm"><span class="font-medium">Status:</span> 
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-sm"><span class="font-medium">Notes:</span> {{ $payment->notes ?? 'No additional notes' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Final Amount -->
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-md">
            <div class="font-bold">Final Amount</div>
            <div class="font-bold text-lg text-primary">₵{{ number_format($payment->final_amount, 2) }}</div>
        </div>
    </div>
    
    <!-- Receipt Footer -->
    <div class="bg-gray-50 px-6 py-4 rounded-b-lg text-center">
        <p class="text-sm text-gray-600">Thank you for your payment. This is an official receipt from the Institute of Modern Technologies.</p>
    </div>
</div>
