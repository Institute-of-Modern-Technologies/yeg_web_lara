@extends('admin.dashboard')

@section('content')
<div class="p-6">
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900">Student Details</h1>
                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ ucfirst($student->status) }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500">Registration #: <span class="font-mono font-medium">{{ $student->registration_number }}</span></p>
        </div>
        
        <div class="flex space-x-2">
            <a href="{{ route('admin.students.edit', $student->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition-colors flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <button onclick="confirmDelete({{ $student->id }})" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 transition-colors flex items-center">
                <i class="fas fa-trash mr-2"></i> Delete
            </button>
            <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-medium hover:bg-gray-700 transition-colors flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Student Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Profile Summary -->
        <div class="p-6 flex items-center space-x-6 border-b border-gray-200">
            <div class="w-24 h-24 rounded-full bg-primary flex items-center justify-center text-white text-3xl font-bold">
                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">
                    {{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }}
                </h3>
                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <span class="flex items-center text-gray-600">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        {{ $student->programType->name ?? 'N/A' }}
                    </span>
                    <span class="flex items-center text-gray-600">
                        <i class="fas fa-school mr-2"></i>
                        {{ $student->school->name ?? 'N/A' }}
                    </span>
                    <span class="flex items-center text-gray-600">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Registered {{ \Carbon\Carbon::parse($student->created_at)->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Tabs Navigation -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <div class="flex space-x-4" x-data="{ activeTab: 'personal' }">
                <button @click="activeTab = 'personal'" :class="{ 'text-primary border-primary': activeTab === 'personal', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'personal' }" class="px-3 py-2 font-medium text-sm border-b-2 focus:outline-none">
                    <i class="fas fa-user mr-2"></i> Personal Info
                </button>
                <button @click="activeTab = 'program'" :class="{ 'text-primary border-primary': activeTab === 'program', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'program' }" class="px-3 py-2 font-medium text-sm border-b-2 focus:outline-none">
                    <i class="fas fa-graduation-cap mr-2"></i> Program Details
                </button>
                <button @click="activeTab = 'payment'" :class="{ 'text-primary border-primary': activeTab === 'payment', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'payment' }" class="px-3 py-2 font-medium text-sm border-b-2 focus:outline-none">
                    <i class="fas fa-credit-card mr-2"></i> Payment History
                </button>
            </div>
        </div>
        
        <!-- Tab Contents -->
        <div x-data="{ activeTab: 'personal' }" class="p-6">
            <!-- Personal Information Tab -->
            <div x-show="activeTab === 'personal'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information Card -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="grid md:grid-cols-2 gap-6 p-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div class="flex items-center border-b pb-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Email Address</p>
                                    <p class="font-medium text-gray-900">{{ $student->email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center border-b pb-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 text-green-500 flex items-center justify-center mr-3">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Phone Number</p>
                                    <p class="font-medium text-gray-900">{{ $student->phone }}</p>
                                </div>
                            </div>
                            
                            @if($student->date_of_birth)
                            <div class="flex items-center border-b pb-3">
                                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-500 flex items-center justify-center mr-3">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Date of Birth</p>
                                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($student->gender)
                            <div class="flex items-center pb-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center mr-3">
                                    <i class="fas fa-venus-mars"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Gender</p>
                                    <p class="font-medium text-gray-900">{{ ucfirst($student->gender) }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Right Column -->
                        <div class="space-y-4">
                            @if($student->address)
                            <div class="flex items-center border-b pb-3">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-500 flex items-center justify-center mr-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Address</p>
                                    <p class="font-medium text-gray-900">{{ $student->address }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="flex items-center border-b pb-3">
                                <div class="w-8 h-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center mr-3">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">City</p>
                                    <p class="font-medium text-gray-900">{{ $student->city }}</p>
                                </div>
                            </div>
                            
                            @if($student->region)
                            <div class="flex items-center pb-3">
                                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-500 flex items-center justify-center mr-3">
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
            </div>
            
            <!-- Program Information Tab -->
            <div x-show="activeTab === 'program'" class="grid grid-cols-1 gap-6" style="display: none;">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-6">Program Details</h4>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="flex flex-col space-y-4">
                                <!-- Program Type Card -->
                                <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg p-4 text-white">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-xs text-blue-100">Program Type</p>
                                            <p class="text-xl font-bold mt-1">{{ $student->programType->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="p-2 bg-white bg-opacity-20 rounded-full">
                                            <i class="fas fa-graduation-cap text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Registration Details -->
                                <div class="bg-white border rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3">Registration Details</h5>
                                    
                                    <div class="grid grid-cols-1 gap-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">Registration Number:</span>
                                            <span class="font-mono font-medium text-gray-900">{{ $student->registration_number }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">Status:</span>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">Registration Date:</span>
                                            <span class="text-gray-900">{{ \Carbon\Carbon::parse($student->created_at)->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col space-y-4">
                                <!-- School Card -->
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-4 text-white">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-xs text-indigo-100">School</p>
                                            <p class="text-xl font-bold mt-1">{{ $student->school->name ?? 'Not applicable' }}</p>
                                        </div>
                                        <div class="p-2 bg-white bg-opacity-20 rounded-full">
                                            <i class="fas fa-school text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional School Info -->
                                @if($student->school)
                                <div class="bg-white border rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3">School Information</h5>
                                    
                                    <div class="grid grid-cols-1 gap-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">School Code:</span>
                                            <span class="text-gray-900">{{ $student->school->code ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">Location:</span>
                                            <span class="text-gray-900">{{ $student->school->location ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment History Tab -->
            <div x-show="activeTab === 'payment'" class="grid grid-cols-1 gap-6" style="display: none;">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-lg font-bold text-gray-900">Payment History</h4>
                            <div>
                                @if($student->status === 'active')
                                <a href="#" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 transition-colors">
                                    <i class="fas fa-plus-circle mr-2"></i> Add Payment
                                </a>
                                @endif
                            </div>
                        </div>
                        
                        @if(count($student->payments) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($student->payments as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GHC {{ number_format($payment->amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($payment->status)
                                                    @case('completed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                                        @break
                                                    @default
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($payment->status) }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Payment Summary -->
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="p-4 bg-white rounded-lg shadow text-center">
                                        <p class="text-sm text-gray-500">Total Paid</p>
                                        <p class="text-2xl font-bold text-green-600">GHC {{ number_format($student->payments->where('status', 'completed')->sum('amount'), 2) }}</p>
                                    </div>
                                    <div class="p-4 bg-white rounded-lg shadow text-center">
                                        <p class="text-sm text-gray-500">Outstanding Balance</p>
                                        <p class="text-2xl font-bold text-red-600">GHC {{ number_format(($student->programType->fee ?? 0) - $student->payments->where('status', 'completed')->sum('amount'), 2) }}</p>
                                    </div>
                                    <div class="p-4 bg-white rounded-lg shadow text-center">
                                        <p class="text-sm text-gray-500">Last Payment</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            @if($student->payments->count() > 0)
                                                {{ \Carbon\Carbon::parse($student->payments->sortByDesc('created_at')->first()->created_at)->format('M d, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-lg">
                                <div class="text-gray-500 mb-2"><i class="fas fa-file-invoice-dollar text-4xl"></i></div>
                                <h5 class="text-lg font-medium text-gray-900">No Payment Records</h5>
                                <p class="text-gray-500 mt-1">This student doesn't have any payment records yet.</p>
                                @if($student->status === 'active')
                                <div class="mt-4">
                                    <a href="#" class="px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                                        <i class="fas fa-plus-circle mr-2"></i> Add First Payment
                                    </a>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

<script>
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
</script>
