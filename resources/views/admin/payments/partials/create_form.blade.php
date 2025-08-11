<!-- Payment Creation Form -->
<form id="paymentForm" action="{{ route('admin.payments.store') }}" method="POST" class="space-y-4">
    @csrf
    <input type="hidden" name="student_id" value="{{ $student->id }}">
    
    <div class="mb-6 bg-blue-50 p-3 rounded-lg">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Student Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500">Full Name</p>
                <p class="text-sm font-medium">{{ $student->full_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Program</p>
                <p class="text-sm font-medium">{{ $student->programType->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    <div class="mb-6 bg-yellow-50 p-3 rounded-lg">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Payment Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-500">Amount To Be Paid</p>
                <p class="text-sm font-medium">GHC {{ number_format($amountToBePaid, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Amount Already Paid</p>
                <p class="text-sm font-medium">GHC {{ number_format($previousPayments, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Outstanding Balance</p>
                <p class="text-sm font-medium">GHC {{ number_format($balance, 2) }}</p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Payment Amount <span class="text-red-500">*</span></label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">GHC</span>
                </div>
                <input type="number" name="amount" id="amount" step="0.01" min="0" 
                    class="block w-full pl-12 pr-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    placeholder="0.00" required autocomplete="off">
            </div>
        </div>
        
        <div>
            <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">GHC</span>
                </div>
                <input type="number" name="discount" id="discount" step="0.01" min="0" 
                    class="block w-full pl-12 pr-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    placeholder="0.00" value="0" autocomplete="off">
            </div>
        </div>
    </div>
    
    <div>
        <label for="final_amount" class="block text-sm font-medium text-gray-700 mb-1">Final Amount <span class="text-red-500">*</span></label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">GHC</span>
            </div>
            <input type="number" name="final_amount" id="final_amount" step="0.01" min="0" 
                class="block w-full pl-12 pr-4 py-2 text-sm border border-gray-300 rounded-md bg-gray-50 font-semibold"
                placeholder="0.00" required readonly>
        </div>
    </div>
    
    <div>
        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
        <select id="payment_method" name="payment_method" 
            class="focus:ring-admin-purple focus:border-admin-purple block w-full sm:text-sm border-gray-300 rounded-md">
            <option value="cash" selected>Cash</option>
            <option value="mobile_money" disabled>Mobile Money (Coming Soon)</option>
            <option value="bank_transfer" disabled>Bank Transfer (Coming Soon)</option>
        </select>
    </div>
    
    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
        <textarea id="notes" name="notes" rows="2" 
            class="focus:ring-admin-purple focus:border-admin-purple block w-full sm:text-sm border-gray-300 rounded-md"
            placeholder="Any additional notes about this payment..."></textarea>
    </div>
    
    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
        <button type="button" onclick="Swal.close()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm text-gray-700 font-medium">
            Cancel
        </button>
        <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 rounded-lg text-base font-semibold text-white flex items-center shadow-lg">
            <i class="fas fa-credit-card mr-2"></i> Pay
        </button>
    </div>
</form>
