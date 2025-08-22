@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Partner Schools Debug</h1>
    
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-bold mb-4">Partner Schools from Database</h2>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Image Path</th>
                    <th class="py-2 px-4 border-b">Active</th>
                    <th class="py-2 px-4 border-b">File Exists Check</th>
                    <th class="py-2 px-4 border-b">Image Preview</th>
                </tr>
            </thead>
            <tbody>
                @foreach(App\Models\PartnerSchool::all() as $school)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $school->id }}</td>
                    <td class="py-2 px-4 border-b">{{ $school->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $school->image_path }}</td>
                    <td class="py-2 px-4 border-b">{{ $school->is_active ? 'Yes' : 'No' }}</td>
                    <td class="py-2 px-4 border-b">
                        Raw path check: {{ file_exists(public_path($school->image_path)) ? 'Yes' : 'No' }}<br>
                        Storage path check: {{ file_exists(public_path('storage/' . $school->image_path)) ? 'Yes' : 'No' }}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <img src="{{ asset('/storage/' . $school->image_path) }}" class="h-16 w-auto">
                        <div>URL: {{ asset('/storage/' . $school->image_path) }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Storage Path Information</h2>
        <div class="mb-4">
            <strong>public_path():</strong> {{ public_path() }}
        </div>
        <div class="mb-4">
            <strong>storage_path():</strong> {{ storage_path() }}
        </div>
        <div>
            <strong>asset('/'):</strong> {{ asset('/') }}
        </div>
    </div>
</div>
@endsection
