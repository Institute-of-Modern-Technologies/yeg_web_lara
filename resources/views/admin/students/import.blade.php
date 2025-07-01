@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.students.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Students</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Import Students</h1>
    </div>
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 bg-primary">
            <h1 class="text-xl font-bold text-white">CSV Import Tool</h1>
            <p class="text-white text-opacity-80">Upload a CSV file to import students</p>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <h2 class="font-semibold text-lg mb-3">Instructions:</h2>
                <ol class="list-decimal pl-6 space-y-2 text-gray-700">
                    <li>Download a <a href="{{ route('admin.students.export') }}" class="text-primary hover:underline">sample CSV template</a> or prepare a CSV file.</li>
                    <li>Ensure your CSV file has headers as the first row.</li>
                    <li>All fields are optional. Common fields include: Full Name, Age, Parent Contact, City, School, Program Type.</li>
                    <li>Map your columns below if they don't match our system field names.</li>
                    <li>Maximum file size: 2MB.</li>
                </ol>
            </div>
            
            <form action="{{ route('admin.students.import.validate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
                    <input type="file" id="csv_file" name="csv_file" accept=".csv" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                    @error('csv_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg">Column Mapping</h3>
                        <button type="button" id="toggleMapping" class="text-primary hover:underline text-sm">
                            Show Mapping Options
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Map your CSV columns to our system fields. Once you upload your CSV file, the dropdowns will be populated with your actual column headers.</p>
                    
                    <div id="mappingFields" class="hidden border rounded-lg p-4 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="columnMappingContainer">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <select name="map_full_name" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                                <select name="map_age" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <select name="map_phone" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <select name="map_email" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Contact</label>
                                <select name="map_parent_contact" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <select name="map_city" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                                <select name="map_school_id" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Can be school name or ID</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Program Type</label>
                                <select name="map_program_type_id" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Can be program name or ID</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="map_status" class="mapping-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select column...</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">active, inactive, or completed</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors mr-2">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-upload mr-2"></i>
                        Import Students
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleMapping').addEventListener('click', function() {
        const mappingFields = document.getElementById('mappingFields');
        if (mappingFields.classList.contains('hidden')) {
            mappingFields.classList.remove('hidden');
            this.textContent = 'Hide Mapping Options';
        } else {
            mappingFields.classList.add('hidden');
            this.textContent = 'Show Mapping Options';
        }
    });

    // CSV file parsing to get headers and populate dropdowns
    document.getElementById('csv_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show mapping options when a file is selected
            const mappingFields = document.getElementById('mappingFields');
            if (mappingFields.classList.contains('hidden')) {
                document.getElementById('toggleMapping').click();
            }
            
            // Use FileReader to read the CSV
            const reader = new FileReader();
            reader.onload = function(e) {
                const contents = e.target.result;
                const lines = contents.split('\n');
                
                if (lines.length > 0) {
                    // Get the headers from the first line
                    let headers = lines[0].split(',');
                    
                    // Clean up headers (remove quotes, trim whitespace)
                    headers = headers.map(header => {
                        // Remove double quotes if present
                        let cleaned = header.replace(/^"(.*)"$/, '$1');
                        // Remove single quotes if present
                        cleaned = cleaned.replace(/^'(.*)'$/, '$1');
                        // Trim whitespace
                        return cleaned.trim();
                    });
                    
                    // Populate all dropdown selects with the headers
                    const selects = document.querySelectorAll('.mapping-select');
                    selects.forEach(select => {
                        // Keep the default empty option
                        const defaultOption = select.options[0];
                        select.innerHTML = '';
                        select.appendChild(defaultOption);
                        
                        // Add options based on CSV headers
                        headers.forEach(header => {
                            if (header) { // Only add non-empty headers
                                const option = document.createElement('option');
                                option.value = header;
                                option.textContent = header;
                                
                                // Try to auto-select the most likely match based on field name
                                const fieldName = select.getAttribute('name').replace('map_', '');
                                const headerLower = header.toLowerCase();
                                if (headerLower.includes(fieldName) || 
                                    (fieldName === 'full_name' && (headerLower.includes('name') || headerLower.includes('student'))) ||
                                    (fieldName === 'parent_contact' && (headerLower.includes('parent') || headerLower.includes('contact') || headerLower.includes('guardian'))) ||
                                    (fieldName === 'school_id' && headerLower.includes('school')) ||
                                    (fieldName === 'program_type_id' && (headerLower.includes('program') || headerLower.includes('type'))) ||
                                    (fieldName === 'phone' && (headerLower.includes('phone') || headerLower.includes('mobile') || headerLower.includes('cell')))) {
                                    option.selected = true;
                                }
                                
                                select.appendChild(option);
                            }
                        });
                    });
                    
                    console.log('CSV headers loaded:', headers);
                }
            };
            reader.readAsText(file);
        }
    });
</script>
@endsection
