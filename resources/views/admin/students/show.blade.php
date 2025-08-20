@extends('admin.dashboard')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Student Profile Header -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-primary to-blue-700 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="flex items-center">
                    <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-primary text-2xl font-bold shadow-md">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                    <div class="ml-6">
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold text-white">{{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }}</h1>
                            @switch($student->status)
                                @case('active')
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-200 text-green-800">
                                        Active
                                    </span>
                                    @break
                                @case('inactive')
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-200 text-red-800">
                                        Inactive
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-200 text-yellow-800">
                                        Pending Approval
                                    </span>
                                    @break
                                @default
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-800">
                                        {{ ucfirst($student->status) }}
                                    </span>
                            @endswitch
                        </div>
                        <div class="mt-2 text-blue-100">
                            <p class="flex items-center">
                                <i class="fas fa-id-card mr-2"></i> 
                                <span class="font-mono">{{ $student->registration_number }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                    @if($student->status == 'pending')
                        <form method="POST" action="{{ route('admin.students.approve', $student->id) }}" class="inline-block">@csrf
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-red-800 transition-colors flex items-center">
                            <i class="fas fa-user-check mr-2"></i> Approve
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm font-medium hover:bg-blue-600 transition-colors flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <button onclick="confirmDelete({{ $student->id }})" class="px-4 py-2 bg-red-500 text-white rounded-md text-sm font-medium hover:bg-red-600 transition-colors flex items-center">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm font-medium hover:bg-gray-600 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                    <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="p-6">
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex items-center px-4 py-2 bg-blue-50 rounded-md">
                    <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>
                    <div>
                        <p class="text-xs text-gray-500">Program Type</p>
                        <p class="font-medium">{{ $student->programType->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex items-center px-4 py-2 bg-green-50 rounded-md">
                    <i class="fas fa-school text-green-500 mr-2"></i>
                    <div>
                        <p class="text-xs text-gray-500">School</p>
                        <p class="font-medium">{{ $student->school->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex items-center px-4 py-2 bg-purple-50 rounded-md">
                    <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                    <div>
                        <p class="text-xs text-gray-500">Registration Date</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($student->created_at)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Content -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden" x-data="{ activeTab: '{{ session('active_tab', 'personal') }}' }">
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button @click="activeTab = 'personal'" :class="{ 'border-primary text-primary': activeTab === 'personal', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'personal' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    <i class="fas fa-user mr-2"></i> Personal Info
                </button>
                <button @click="activeTab = 'program'" :class="{ 'border-primary text-primary': activeTab === 'program', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'program' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    <i class="fas fa-graduation-cap mr-2"></i> Program Details
                </button>
                <button @click="activeTab = 'payment'" :class="{ 'border-primary text-primary': activeTab === 'payment', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'payment' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    <i class="fas fa-credit-card mr-2"></i> Payment History
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            <!-- Personal Information Tab -->
            <div x-show="activeTab === 'personal'" class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="flex bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center mr-4">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email Address</p>
                                <p class="font-medium text-gray-900">{{ $student->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-green-100 text-green-500 flex items-center justify-center mr-4">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Phone Number</p>
                                <p class="font-medium text-gray-900">{{ $student->phone }}</p>
                            </div>
                        </div>
                        
                        @if($student->date_of_birth)
                        <div class="flex bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-500 flex items-center justify-center mr-4">
                                <i class="fas fa-birthday-cake"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Date of Birth</p>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($student->gender)
                        <div class="flex bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center mr-4">
                                <i class="fas fa-venus-mars"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Gender</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($student->gender) }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-6">
                        @if($student->address)
                        <div class="flex bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-500 flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Address</p>
                                <p class="font-medium text-gray-900">{{ $student->address }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-500 flex items-center justify-center mr-4">
                                <i class="fas fa-city"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">City</p>
                                <p class="font-medium text-gray-900">{{ $student->city }}</p>
                            </div>
                        </div>
                        
                        @if($student->region)
                        <div class="flex bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-500 flex items-center justify-center mr-4">
                                <i class="fas fa-map"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Region</p>
                                <p class="font-medium text-gray-900">{{ $student->region }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Program Information Tab -->
            <div x-show="activeTab === 'program'" style="display: none;">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Program Details</h3>
                
                <!-- Stage Management Section - New Addition -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 mb-8">
                    <div class="bg-gradient-to-r from-[#950713] to-red-800 px-6 py-4">
                        <h4 class="font-semibold text-white flex items-center text-lg">
                            <i class="fas fa-layer-group mr-2"></i> Stage Information
                        </h4>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Current Stage</p>
                                <div class="flex items-center">
                                    @if($student->stage)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-flag-checkered mr-1"></i> {{ $student->stage->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> No Stage Assigned
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-3">
                                    {{ $student->stage->description ?? 'No description available.' }}
                                </p>
                            </div>
                            
                            <div class="flex space-x-3">
                                <button type="button" 
                                    onclick="document.getElementById('promote-stage-modal').classList.remove('hidden');"
                                    class="flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-arrow-circle-up mr-2"></i> Promote
                                </button>
                                
                                <button type="button" 
                                    onclick="document.getElementById('repeat-stage-modal').classList.remove('hidden');"
                                    class="flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <i class="fas fa-redo mr-2"></i> Repeat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <!-- Program Type Card -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg p-6 text-white shadow-md">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs text-blue-100">Program Type</p>
                                    <p class="text-xl font-bold mt-1">{{ $student->programType->name ?? 'N/A' }}</p>
                                </div>
                                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                                    <i class="fas fa-graduation-cap text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Registration Details -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <h5 class="font-semibold text-gray-900 mb-4">Registration Details</h5>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Registration Number:</span>
                                    <span class="font-mono font-medium text-gray-900">{{ $student->registration_number }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Registration Date:</span>
                                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($student->created_at)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <span class="font-medium {{ $student->status == 'active' ? 'text-green-600' : ($student->status == 'pending' ? 'text-yellow-600' : 'text-red-600') }}">{{ ucfirst($student->status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- School Information -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-school text-lg text-primary mr-3"></i>
                                <h5 class="font-semibold text-gray-900">School Information</h5>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-building text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">School Name</p>
                                        <p class="font-medium text-[#950713]">{{ $student->display_school_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Program Details -->
                        @if($student->programType)
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-book text-lg text-primary mr-3"></i>
                                <h5 class="font-semibold text-gray-900">Program Information</h5>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-graduation-cap text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Program Type</p>
                                        <p class="font-medium text-gray-900">{{ $student->programType->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $student->programType->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Payment History Tab -->
            <div x-show="activeTab === 'payment'" style="display: none;">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Payment History</h3>
                
                @if($student->payments->count() > 0)
                    <!-- Payment Records Table -->
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Reference #
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Final Amount
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Method
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($student->payments as $payment)
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                            {{ $payment->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500">
                                            {{ $payment->reference_number }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500">
                                            ₵{{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                            ₵{{ number_format($payment->final_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500">
                                            {{ ucfirst($payment->payment_method) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm">
                                            <button type="button" onclick="openReceiptModal({{ $payment->id }})" class="bg-primary hover:bg-red-800 text-white text-xs py-1 px-2 rounded">
                                                <i class="fas fa-receipt mr-1"></i> View Receipt
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Payment Details Row -->
                                    <tr class="bg-gray-50">
                                        <td colspan="6" class="px-4 py-2 text-sm">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <span class="font-medium">Discount:</span> ₵{{ number_format($payment->discount, 2) }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Notes:</span> {{ $payment->notes ?? '-' }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- No Payment Records Message -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 text-center">
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="bg-gray-100 rounded-full p-6 mb-4">
                                <i class="fas fa-receipt text-gray-400 text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No Payment Records</h3>
                            <p class="text-gray-500 max-w-md">There are no payment records available for this student at this time.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Stage Assignment Modal -->
<div
    x-data="{ open: false }"
    @open-modal.window="if($event.detail.id === 'change-stage-modal') open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
    style="display: none;"
>
    <!-- Backdrop -->
    <div x-show="open" class="fixed inset-0 transform" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
    </div>
    
    <!-- Modal -->
    <div x-show="open" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div class="bg-[#950713] px-4 py-3 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white">Manually Assign Stage</h3>
            <button @click="open = false" class="text-white hover:text-gray-200 focus:outline-none">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.students.change-stage', $student->id) }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="mb-4">
                    <p class="mb-2 text-sm text-gray-600">Select a stage to assign to <strong>{{ $student->full_name }}</strong>:</p>
                    <select name="stage_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#950713] focus:border-transparent">
                        @php
                        $stages = \App\Models\Stage::orderBy('order')->get();
                        @endphp
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" {{ $student->stage_id == $stage->id ? 'selected' : '' }}>
                                {{ $stage->name }} {{ $stage->level ? '- ' . $stage->level : '' }} (Order: {{ $stage->order }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-md mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                This will override the normal stage progression. Use this feature to correct stage assignments or handle special cases.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#950713] text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Assign Stage
                </button>
                <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Promote Stage Modal -->
<div id="promote-stage-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-arrow-circle-up text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mt-2">Promote Student to Next Stage</h3>
            <form action="{{ route('admin.students.promote-stage', $student->id) }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="active_tab" value="program">
                <div class="mt-4">
                    <div class="px-4">
                        <div class="mb-4">
                            <label for="promote_stage_id" class="block text-sm font-medium text-gray-700 mb-2">Select Stage:</label>
                            <select name="stage_id" id="promote_stage_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[#950713] focus:border-[#950713]">
                                @php
                                $nextStages = $stages->where('status', 'active')->where('order', '>', optional($student->stage)->order ?? 0)->sortBy('order');
                                @endphp
                                
                                @foreach($nextStages as $stage)
                                    <option value="{{ $stage->id }}">
                                        {{ $stage->name }} (Level {{ $stage->order }})
                                    </option>
                                @endforeach
                                
                                @if($nextStages->isEmpty())
                                    <option disabled>No higher stages available</option>
                                @endif
                            </select>
                            <p class="text-sm text-green-600 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                This will promote the student to the selected stage.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Promote Student
                    </button>
                    <button type="button" onclick="document.getElementById('promote-stage-modal').classList.add('hidden');" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Repeat Stage Modal -->
<div id="repeat-stage-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <i class="fas fa-redo text-yellow-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mt-2">Assign Student to Repeat a Stage</h3>
            <form action="{{ route('admin.students.repeat-stage', $student->id) }}" method="POST">
                @csrf
                <input type="hidden" name="active_tab" value="program">
                <div class="mt-4">
                    <div class="px-4">
                        <div class="mb-4">
                            <label for="repeat_stage_id" class="block text-sm font-medium text-gray-700 mb-2">Select Stage to Repeat:</label>
                            <select name="stage_id" id="repeat_stage_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[#950713] focus:border-[#950713]">
                                @foreach($stages->where('status', 'active')->sortBy('order') as $stage)
                                    <option value="{{ $stage->id }}" {{ $student->stage_id == $stage->id ? 'selected' : '' }}>
                                        {{ $stage->name }} (Level {{ $stage->order }}) {{ $student->stage_id == $stage->id ? '(Current)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-yellow-600 mt-2">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                This will assign the student to repeat the selected stage.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Assign to Repeat
                    </button>
                    <button type="button" onclick="document.getElementById('repeat-stage-modal').classList.add('hidden');" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 max-w-4xl">
        <div class="bg-white rounded-lg shadow-xl">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-gray-100 py-3 px-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-gray-900">Payment Receipt</h3>
                <button type="button" class="close-receipt-modal text-gray-400 hover:text-gray-500" onclick="closeReceiptModal()">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <!-- Modal Body - Receipt Content -->
            <div id="receipt-content" class="p-4">
                <!-- Receipt will be loaded here via AJAX -->
                <div class="flex justify-center items-center py-12">
                    <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-primary" role="status">
                        <span class="hidden">Loading...</span>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer - Action Buttons -->
            <div class="bg-gray-100 px-4 py-3 flex flex-wrap gap-3 justify-center md:justify-end rounded-b-lg">
                <!-- Print Button -->
                <button id="print-receipt" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-print mr-2"></i> Print Receipt
                </button>
                
                <!-- Send via WhatsApp Button - Direct Implementation -->
                <button type="button" id="whatsapp-button" onclick="testWhatsAppFunction(); return false;" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fab fa-whatsapp mr-2"></i> Send via WhatsApp
                </button>
                
                <!-- Send via Email Button -->
                <button id="email-receipt" class="bg-primary hover:bg-red-800 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-envelope mr-2"></i> Send via Email
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/receipt-modal.js') }}"></script>
<script src="{{ asset('js/tab-persistence.js') }}"></script>

<script>
    // Function to download PDF receipt directly without page navigation
    function downloadReceiptPDF(url) {
        // Show loading indicator
        const loadingToast = Swal.fire({
            title: 'Downloading PDF',
            text: 'Please wait while we prepare your download...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Fetch the PDF content
        fetch(url)
            .then(response => response.blob())
            .then(blob => {
                // Create a download link
                const downloadUrl = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                
                // Extract filename from URL or use default
                const filename = url.substring(url.lastIndexOf('/') + 1) || 'receipt.pdf';
                a.download = 'receipt-' + filename;
                
                // Trigger download
                document.body.appendChild(a);
                a.click();
                
                // Clean up
                setTimeout(() => {
                    document.body.removeChild(a);
                    URL.revokeObjectURL(downloadUrl);
                    loadingToast.close();
                    
                    // Success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Download Complete!',
                        text: 'The PDF receipt has been downloaded to your device.',
                        timer: 3000
                    });
                }, 1000);
            })
            .catch(error => {
                console.error('Error downloading PDF:', error);
                loadingToast.close();
                
                // Error message
                Swal.fire({
                    icon: 'error',
                    title: 'Download Failed',
                    text: 'There was a problem downloading the receipt. Please try again.',
                    confirmButtonText: 'Try Again',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Try opening in new tab as fallback
                        window.open(url, '_blank');
                    }
                });
            });
    }

    // Student delete confirmation
    function confirmDelete(studentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById(`delete-form-${studentId}`).submit();
                
                // Show deletion in progress message
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    }

    // WhatsApp button now uses direct onclick handler
    // No need for complex overrides

    // WhatsApp functionality for receipt sending
    function testWhatsAppFunction() {
        const studentId = window.location.pathname.split('/').pop();
        
        // Get bill information for this student
        fetch(`/admin/billing/get-bill-info/${studentId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Use parent contact if available
                const phoneToUse = data.parent_contact || data.phone || '';
                
                // Show WhatsApp modal with pre-filled information
                Swal.fire({
                    title: 'Send Receipt via WhatsApp',
                    html: `
                        <div class="text-left">
                            <label class="block text-sm font-medium text-gray-700 mb-2">${data.has_parent_contact ? 'Parent Contact Number:' : 'Phone Number:'}</label>
                            <input type="text" id="whatsapp-phone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md mb-4" 
                                   placeholder="Enter phone number">
                            <p class="mb-4 text-sm text-gray-700">✅ Payment receipt will be sent as PDF</p>
                            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Message:</label>
                            <textarea id="whatsapp-message" rows="8" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                      placeholder="Enter WhatsApp message">${data.whatsapp_message}</textarea>
                        </div>
                    `,
                    width: 600,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fab fa-whatsapp mr-2"></i> Send via WhatsApp',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#25D366',
                    didOpen: () => {
                        // Set the phone value after modal opens with multiple attempts
                        const setPhoneValue = () => {
                            const phoneInput = document.getElementById('whatsapp-phone');
                            if (phoneInput) {
                                phoneInput.value = phoneToUse;
                                phoneInput.focus();
                                phoneInput.blur();
                                console.log('Phone value set to:', phoneToUse);
                            } else {
                                console.error('Phone input not found');
                            }
                        };
                        
                        // Try multiple times to ensure it works
                        setTimeout(setPhoneValue, 50);
                        setTimeout(setPhoneValue, 100);
                        setTimeout(setPhoneValue, 200);
                    },
                    preConfirm: () => {
                        const phone = document.getElementById('whatsapp-phone').value.trim();
                        const message = document.getElementById('whatsapp-message').value.trim();
                        
                        if (!phone) {
                            Swal.showValidationMessage('Please enter a phone number');
                            return false;
                        }
                        
                        if (!message) {
                            Swal.showValidationMessage('Please enter a message');
                            return false;
                        }
                        
                        return { phone, message };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { phone, message } = result.value;
                        
                        // Format phone number
                        let formattedPhone = phone.replace(/[^0-9]/g, '');
                        if (!formattedPhone.startsWith('233') && formattedPhone.length === 10) {
                            formattedPhone = '233' + formattedPhone.substring(1);
                        }
                        
                        // Generate and open PDF receipt, then WhatsApp
                        if (data.has_pdf_receipt && data.receipt_url) {
                            // Show loading
                            Swal.fire({
                                title: 'Preparing PDF Receipt',
                                text: 'Generating PDF receipt for WhatsApp...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                            
                            // Create WhatsApp URL but don't open automatically
                            const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(message)}`;
                            
                            // Instead of opening windows directly, provide buttons to user
                            setTimeout(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'PDF Receipt & WhatsApp Ready!',
                                        html: `<div class="text-left">
                                            <p><strong>Your receipt is ready!</strong> Use the buttons below to:</p>
                                            <div class="mt-4 mb-3">
                                                <button onclick="downloadReceiptPDF('${data.receipt_url}')" 
                                                    class="mb-2 w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                                                    <i class="fas fa-download mr-2"></i> Download PDF Receipt
                                                </button>
                                            </div>
                                            <div class="mb-3">
                                                <button onclick="window.open('${whatsappUrl}', '_blank')" 
                                                    class="w-full px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none">
                                                    <i class="fab fa-whatsapp mr-2"></i> Open WhatsApp
                                                </button>
                                            </div>
                                            <p class="text-sm mt-3"><strong>Note:</strong> To send the PDF via WhatsApp, download it first, then attach it in the WhatsApp conversation.</p>
                                            <p class="text-sm">Sending to: <strong>${phone}</strong></p>
                                        </div>`,
                                        confirmButtonText: 'Got it!'
                                    });
                            }, 1000);
                        } else {
                            // No PDF available, just prepare WhatsApp URL for button
                            const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(message)}`;
                            
                            // Show modal with button to open WhatsApp
                            Swal.fire({
                                icon: 'info',
                                title: 'Message Ready',
                                html: `<div class="text-left">
                                    <p><strong>Your WhatsApp message is ready to send!</strong></p>
                                    <p class="text-sm mb-3">⚠️ No PDF receipt available for this payment.</p>
                                    <div class="mb-3">
                                        <button onclick="window.open('${whatsappUrl}', '_blank')" 
                                            class="w-full px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none">
                                            <i class="fab fa-whatsapp mr-2"></i> Open WhatsApp
                                        </button>
                                    </div>
                                    <p class="text-sm">Sending to: <strong>${phone}</strong></p>
                                </div>`,
                                confirmButtonText: 'Done'
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to load student information.'
                });
            }
        })
        .catch(error => {
            console.error('Error loading student info:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load student information. Please try again.'
            });
        });
    }
</script>
