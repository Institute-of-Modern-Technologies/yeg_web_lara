@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Activity</h1>
        <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to List</span>
        </a>
    </div>
    
    <!-- Activity Form -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-tasks mr-2 text-primary"></i>
                <span>Activity Details</span>
            </h2>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.activities.update', $activity->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Activity Name</label>
                    <input type="text" 
                           class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}" 
                           id="name" name="name" value="{{ old('name', $activity->name) }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center gap-3 mt-6">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>Update Activity
                    </button>
                    <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
