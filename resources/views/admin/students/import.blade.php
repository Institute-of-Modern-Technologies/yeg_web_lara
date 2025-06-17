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
            
            <form action="{{ route('admin.students.import.process') }}" method="POST" enctype="multipart/form-data">
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
                        <h3 class="font-semibold text-lg">Column Mapping (Optional)</h3>
                        <button type="button" id="toggleMapping" class="text-primary hover:underline text-sm">
                            Show Mapping Options
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">If your column headers don't match our field names, you can specify the mapping here.</p>
                    
                    <div id="mappingFields" class="hidden border rounded-lg p-4 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="map_full_name" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                                <input type="text" name="map_age" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="map_phone" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="text" name="map_email" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Contact</label>
                                <input type="text" name="map_parent_contact" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="map_city" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                                <input type="text" name="map_school_id" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                <p class="text-xs text-gray-500 mt-1">Can be school name or ID</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Program Type</label>
                                <input type="text" name="map_program_type_id" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                <p class="text-xs text-gray-500 mt-1">Can be program name or ID</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <input type="text" name="map_status" placeholder="CSV column name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                <p class="text-xs text-gray-500 mt-1">Default is 'active'</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleMapping');
        const mappingFields = document.getElementById('mappingFields');
        
        toggleBtn.addEventListener('click', function() {
            if (mappingFields.classList.contains('hidden')) {
                mappingFields.classList.remove('hidden');
                toggleBtn.textContent = 'Hide Mapping Options';
            } else {
                mappingFields.classList.add('hidden');
                toggleBtn.textContent = 'Show Mapping Options';
            }
        });
    });
</script>
@endsection
