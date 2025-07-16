<!-- Confirmation Agreement Section -->
<div class="p-6 sm:p-8 form-section bg-gray-50 border-b border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
        <span class="bg-[#950713] text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">
            <i class="fas fa-check"></i>
        </span>
        Confirmation
    </h2>
    
    <div class="space-y-6">
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input type="checkbox" name="confirmation_agreement" id="confirmation_agreement" class="w-5 h-5 rounded text-[#950713] focus:ring-[#950713]" {{ old('confirmation_agreement', $trainer->confirmation_agreement) ? 'checked' : '' }} value="1" required>
            </div>
            <div class="ml-3">
                <label for="confirmation_agreement" class="text-gray-700">I confirm that the information provided is accurate and complete</label>
                <p class="text-gray-500 text-xs mt-1">By checking this box, you acknowledge that all details provided can be used for trainer evaluation and communication purposes.</p>
            </div>
        </div>
        @error('confirmation_agreement')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Form Submit Buttons -->
<div class="p-6 sm:p-8 flex justify-end space-x-3">
    <a href="{{ route('admin.trainers.index') }}" class="px-5 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
        Cancel
    </a>
    <button type="submit" class="px-5 py-2 bg-[#950713] text-white rounded-md hover:bg-red-900 transition-colors">
        Update Trainer
    </button>
</div>
