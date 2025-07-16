@extends('admin.dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.trainers.index') }}" class="text-primary hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back to Trainers
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Trainer Profile</h1>
                <div class="flex space-x-2 mt-4 md:mt-0">
                    <a href="{{ route('admin.trainers.edit', $trainer) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-colors flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    
                    <form action="{{ route('admin.trainers.destroy', $trainer) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-sm transition-colors flex items-center"
                                onclick="return confirm('Are you sure you want to delete this trainer?')">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Profile Information -->
                <div class="md:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center text-white text-xl">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $trainer->name }}</h2>
                                <div class="flex items-center text-sm text-gray-600 mt-1">
                                    @if($trainer->status == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                                            <i class="fas fa-check-circle mr-1"></i> Active
                                        </span>
                                    @elseif($trainer->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                                            <i class="fas fa-times-circle mr-1"></i> Inactive
                                        </span>
                                    @endif
                                    @if($trainer->specialization)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $trainer->specialization }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="flex flex-col">
                                <span class="text-xs uppercase text-gray-500 font-medium">Email</span>
                                <span class="text-sm text-gray-700 mt-1">{{ $trainer->email }}</span>
                            </div>
                            
                            <div class="flex flex-col">
                                <span class="text-xs uppercase text-gray-500 font-medium">Phone</span>
                                <span class="text-sm text-gray-700 mt-1">{{ $trainer->phone ?? 'Not provided' }}</span>
                            </div>
                            
                            @if($trainer->location)
                            <div class="flex flex-col">
                                <span class="text-xs uppercase text-gray-500 font-medium">Location</span>
                                <span class="text-sm text-gray-700 mt-1">{{ $trainer->location }}</span>
                            </div>
                            @endif
                            
                            <div class="flex flex-col">
                                <span class="text-xs uppercase text-gray-500 font-medium">Joined</span>
                                <span class="text-sm text-gray-700 mt-1">{{ $trainer->created_at ? $trainer->created_at->format('M d, Y') : 'Unknown' }}</span>
                            </div>
                        </div>
                        
                        @if(!empty($trainer->expertise_areas) && is_array($trainer->expertise_areas) && count($trainer->expertise_areas) > 0)
                        <div class="mb-4">
                            <h3 class="text-md font-medium text-primary mb-2">Expertise Areas</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($trainer->expertise_areas as $expertise)
                                <span class="px-3 py-1 bg-primary bg-opacity-10 text-primary text-xs font-medium rounded-full">
                                    {{ $expertise }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($trainer->educational_background)
                        <div class="mb-4">
                            <h3 class="text-md font-medium text-primary mb-2">Educational Background</h3>
                            <div class="text-sm text-gray-600 bg-white rounded-lg p-4 border border-primary">
                                {!! nl2br(e($trainer->educational_background)) !!}
                            </div>
                        </div>
                        @endif

                        @if($trainer->relevant_experience)
                        <div class="mb-4">
                            <h3 class="text-md font-medium text-primary mb-2">Relevant Experience</h3>
                            <div class="text-sm text-gray-600 bg-white rounded-lg p-4 border border-primary">
                                {!! nl2br(e($trainer->relevant_experience)) !!}
                            </div>
                        </div>
                        @endif
                        
                        @if($trainer->bio)
                        <div class="mb-4">
                            <h3 class="text-md font-medium text-primary mb-2">Bio</h3>
                            <div class="text-sm text-gray-600 bg-white rounded-lg p-4 border border-primary">
                                {!! nl2br(e($trainer->bio)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Stats Panel -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 h-full">
                        <h3 class="text-md font-medium text-gray-700 mb-4">Statistics</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-white p-4 rounded-lg border border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Students</p>
                                        <p class="font-semibold">0</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg border border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Classes</p>
                                        <p class="font-semibold">0</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg border border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Subjects</p>
                                        <p class="font-semibold">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
