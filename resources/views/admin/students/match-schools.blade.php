@extends('admin.dashboard')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Match Schools
    </h2>
    
    <!-- Session Status -->
    @if(session('status'))
    <div class="mb-4 px-4 py-2 border-l-4 border-green-500 bg-green-50 text-green-700">
        {{ session('status') }}
    </div>
    @endif
    
    <!-- Validation Errors -->
    @if($errors->any())
    <div class="mb-4 px-4 py-2 border-l-4 border-red-500 bg-red-50 text-red-700">
        <div class="font-medium">Please fix the following errors:</div>
        <ul class="mt-2 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <p class="mb-4">
            We found the following schools in your CSV file. Please select the correct school for each one:
        </p>
        
        <!-- Data Preview -->
        <div class="mb-6">
            <h3 class="font-semibold mb-2">CSV Data Preview (first 5 rows)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(!empty($csvPreview) && !empty($csvPreview[0]))
                                @foreach($csvPreview[0] as $field => $value)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $field }}
                                    </th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($csvPreview as $row)
                            <tr>
                                @foreach($row as $field => $value)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $value }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <form action="{{ route('admin.students.import.match_schools') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <h3 class="font-semibold mb-2">Match Schools</h3>
                
                <div class="grid gap-4">
                    @foreach($csvSchools as $schoolName)
                        <div class="flex items-center gap-4">
                            <div class="w-1/3">
                                <span class="font-medium">{{ $schoolName }}</span>
                            </div>
                            <div class="w-2/3">
                                <select name="school_mapping[{{ $schoolName }}]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">-- Select a school --</option>
                                    @foreach($availableSchools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                    <option value="create_new">+ Create new school "{{ $schoolName }}"</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.students.import') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Back
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Continue Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
