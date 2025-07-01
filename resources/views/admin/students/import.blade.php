@extends('admin.dashboard')

@section('content')
<div class="p-6 bg-gray-50">
    <div class="flex items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Import Students</h1>
    </div>
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm" role="alert">
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif
    
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm" role="alert">
        <p class="font-medium">{{ session('success') }}</p>
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
                    <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">Upload CSV File</label>
                    <div class="mt-1 flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-primary transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="csv_file" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none">
                                    <span>Click to select a file</span>
                                    <input id="csv_file" name="csv_file" type="file" accept=".csv" class="sr-only" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                CSV file up to 2MB
                            </p>
                            <p id="file-name" class="text-sm font-medium text-gray-900 mt-2 hidden"></p>
                        </div>
                    </div>
                    @error('csv_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg">Column Mapping</h3>
                        <button type="button" id="toggleMapping" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-primary bg-white hover:bg-blue-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            <span>Show Mapping Options</span>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Map your CSV columns to our system fields. Once you upload your CSV file, the dropdowns will be populated with your actual column headers.</p>
                    
                    <div id="mappingFields" class="hidden border rounded-lg p-6 bg-gradient-to-b from-gray-50 to-white shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="columnMappingContainer">
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
                
                <div class="flex items-center justify-between border-t pt-5 mt-6">
                    <a href="{{ route('admin.students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Students
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
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
        const buttonSpan = this.querySelector('span');
        const buttonIcon = this.querySelector('svg');
        
        if (mappingFields.classList.contains('hidden')) {
            mappingFields.classList.remove('hidden');
            buttonSpan.textContent = 'Hide Mapping Options';
            // Change icon to up arrow
            buttonIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />';
        } else {
            mappingFields.classList.add('hidden');
            buttonSpan.textContent = 'Show Mapping Options';
            // Change icon to down arrow
            buttonIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
        }
    });

    // Show file name when selected
    document.getElementById('csv_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileNameElement = document.getElementById('file-name');
            fileNameElement.textContent = file.name;
            fileNameElement.classList.remove('hidden');
            
            // Add visual feedback that file is selected
            const dropZone = this.closest('.border-dashed');
            dropZone.classList.add('border-primary', 'bg-blue-50');
            dropZone.classList.remove('border-gray-300');
            
            // Add checkmark icon
            const svgIcon = dropZone.querySelector('svg');
            svgIcon.innerHTML = '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="currentColor"/>';
            svgIcon.classList.remove('text-gray-400');
            svgIcon.classList.add('text-green-500');
            
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
