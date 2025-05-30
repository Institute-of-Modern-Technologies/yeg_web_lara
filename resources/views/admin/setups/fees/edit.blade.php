@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Program Fee</h1>
        <a href="{{ route('admin.fees.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to Fees</span>
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Please fix the following errors:</p>
        <ul class="list-disc ml-5 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-money-bill-wave mr-2 text-primary"></i>
                <span>Fee Information</span>
            </h2>
        </div>
        
        <form action="{{ route('admin.fees.update', $fee) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="program_type_id" class="block text-sm font-medium text-gray-700 mb-1">Program Type <span class="text-red-500">*</span></label>
                    <select name="program_type_id" id="program_type_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                        <option value="">Select Program Type</option>
                        @foreach($programTypes as $programType)
                            <option value="{{ $programType->id }}" data-name="{{ strtolower($programType->name) }}" {{ (old('program_type_id', $fee->program_type_id) == $programType->id) ? 'selected' : '' }}>
                                {{ $programType->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('program_type_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div id="school_selection_div">
                    <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">School (Optional)</label>
                    <select name="school_id" id="school_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value="">Standard Fee (All Schools)</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ (old('school_id', $fee->school_id) == $school->id) ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-sm mt-1">
                        Leave blank to set a standard fee for all schools.
                    </p>
                    @error('school_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Fee Amount (GHC) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="amount" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" value="{{ old('amount', $fee->amount) }}" min="0" step="0.01" required>
                    <p class="text-gray-500 text-sm mt-1">
                        Standard program fee amount in Ghana Cedis.
                    </p>
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Partner School Discount (for non-In School programs) -->
                <div id="partner_discount_div">
                    <label for="partner_discount" class="block text-sm font-medium text-gray-700 mb-1">Partner School Discount (GHC) <span class="text-red-500">*</span></label>
                    <input type="number" name="partner_discount" id="partner_discount" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" value="{{ old('partner_discount', $fee->partner_discount) }}" min="0" step="0.01" required>
                    <p class="text-gray-500 text-sm mt-1">
                        Discount amount for partner schools in Ghana Cedis.
                    </p>
                    @error('partner_discount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- School Commission (for In School program only) -->
                <div id="school_commission_div" class="hidden">
                    <label for="school_commission" class="block text-sm font-medium text-gray-700 mb-1">School Commission (GHC) <span class="text-red-500">*</span></label>
                    <input type="number" name="school_commission" id="school_commission" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" value="{{ old('school_commission', $fee->school_commission ?? 0) }}" min="0" step="0.01">
                    <p class="text-gray-500 text-sm mt-1">
                        Commission amount for schools in Ghana Cedis.
                    </p>
                    @error('school_commission')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- IMT Commission (for In School program only) -->
                <div id="imt_commission_div" class="hidden">
                    <label for="imt_commission" class="block text-sm font-medium text-gray-700 mb-1">IMT Commission (GHC) <span class="text-red-500">*</span></label>
                    <input type="number" name="imt_commission" id="imt_commission" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" value="{{ old('imt_commission', $fee->imt_commission ?? 0) }}" min="0" step="0.01">
                    <p class="text-gray-500 text-sm mt-1">
                        IMT commission amount in Ghana Cedis.
                    </p>
                    @error('imt_commission')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ (old('is_active', $fee->is_active) ? 'checked' : '') }} class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">
                        Only active fees will be applied during registration.
                    </p>
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('admin.fees.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-save mr-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const programTypeSelect = document.getElementById('program_type_id');
    const schoolSelectionDiv = document.getElementById('school_selection_div');
    const schoolSelect = document.getElementById('school_id');
    const partnerDiscountDiv = document.getElementById('partner_discount_div');
    const partnerDiscountInput = document.getElementById('partner_discount');
    const schoolCommissionDiv = document.getElementById('school_commission_div');
    const schoolCommissionInput = document.getElementById('school_commission');
    const imtCommissionDiv = document.getElementById('imt_commission_div');
    const imtCommissionInput = document.getElementById('imt_commission');
    
    // Function to toggle fields based on program type
    function toggleFields() {
        const selectedOption = programTypeSelect.options[programTypeSelect.selectedIndex];
        const programName = selectedOption.getAttribute('data-name');
        
        if (programName === 'in school') {
            // For "In School" program type
            schoolSelectionDiv.classList.remove('hidden');
            schoolSelect.required = true;
            
            // Show the school and IMT commission fields
            schoolCommissionDiv.classList.remove('hidden');
            schoolCommissionInput.required = true;
            imtCommissionDiv.classList.remove('hidden');
            imtCommissionInput.required = true;
            
            // Hide partner discount field
            partnerDiscountDiv.classList.add('hidden');
            partnerDiscountInput.required = false;
        } else {
            // For other program types
            // Hide school selection for non-in-school programs
            schoolSelectionDiv.classList.add('hidden');
            schoolSelect.required = false;
            schoolSelect.value = '';
            
            // Hide the school and IMT commission fields
            schoolCommissionDiv.classList.add('hidden');
            schoolCommissionInput.required = false;
            imtCommissionDiv.classList.add('hidden');
            imtCommissionInput.required = false;
            
            // Show partner discount field
            partnerDiscountDiv.classList.remove('hidden');
            partnerDiscountInput.required = true;
        }
    }
    
    // Initial toggle based on default selection
    toggleFields();
    
    // Add event listener for program type change
    programTypeSelect.addEventListener('change', toggleFields);
});
</script>
@endsection
