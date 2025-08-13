@extends('layouts.school')

@section('title', 'School Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">
            Welcome back, {{ $school->name }}!
        </h1>
        <p class="text-gray-400">
            Manage your students and monitor your school's progress from this dashboard.
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Students -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200 text-sm font-medium">Total Students</p>
                    <p class="text-3xl font-bold text-white">{{ $totalStudents }}</p>
                </div>
                <div class="bg-blue-500/30 rounded-lg p-3">
                    <i class="fas fa-users text-2xl text-blue-200"></i>
                </div>
            </div>
        </div>

        <!-- Active Students -->
        <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-200 text-sm font-medium">Active Students</p>
                    <p class="text-3xl font-bold text-white">{{ $activeStudents }}</p>
                </div>
                <div class="bg-green-500/30 rounded-lg p-3">
                    <i class="fas fa-user-check text-2xl text-green-200"></i>
                </div>
            </div>
        </div>

        <!-- Students with Accounts -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-200 text-sm font-medium">Students with Accounts</p>
                    <p class="text-3xl font-bold text-white">{{ $studentsWithAccounts }}</p>
                </div>
                <div class="bg-purple-500/30 rounded-lg p-3">
                    <i class="fas fa-user-graduate text-2xl text-purple-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Student Management -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-graduation-cap mr-2 text-blue-400"></i>
                Student Management
            </h3>
            <p class="text-gray-400 mb-4">
                Add, edit, and manage your school's students. Each student automatically gets a user account.
            </p>
            <a href="{{ route('school.students.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-users mr-2"></i>
                Manage Students
            </a>
        </div>

        <!-- Admin Permissions -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-shield-alt mr-2 text-green-400"></i>
                Admin Permissions
            </h3>
            <p class="text-gray-400 mb-4">
                Control whether administrators can view and manage your students in the admin panel.
            </p>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-300">Admin Access:</span>
                <button id="toggleAdminPermission" 
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-800 {{ $school->admin_can_manage ? 'bg-green-600' : 'bg-gray-600' }}"
                        data-enabled="{{ $school->admin_can_manage ? 'true' : 'false' }}">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $school->admin_can_manage ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
                <span id="permissionStatus" class="text-sm font-medium {{ $school->admin_can_manage ? 'text-green-400' : 'text-gray-400' }}">
                    {{ $school->admin_can_manage ? 'Enabled' : 'Disabled' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Activity (Placeholder for future enhancement) -->
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
        <h3 class="text-xl font-semibold text-white mb-4">
            <i class="fas fa-clock mr-2 text-yellow-400"></i>
            Recent Activity
        </h3>
        <div class="text-center py-8">
            <i class="fas fa-chart-line text-4xl text-gray-600 mb-4"></i>
            <p class="text-gray-400">Activity tracking coming soon...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleAdminPermission');
    const statusText = document.getElementById('permissionStatus');
    
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            const isEnabled = this.dataset.enabled === 'true';
            
            // Show loading state
            this.disabled = true;
            statusText.textContent = 'Updating...';
            
            fetch('{{ route("school.toggle-admin-permission") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newState = data.admin_can_manage;
                    
                    // Update button state
                    this.dataset.enabled = newState.toString();
                    this.className = this.className.replace(
                        newState ? 'bg-gray-600' : 'bg-green-600',
                        newState ? 'bg-green-600' : 'bg-gray-600'
                    );
                    
                    // Update toggle position
                    const toggle = this.querySelector('span');
                    toggle.className = toggle.className.replace(
                        newState ? 'translate-x-1' : 'translate-x-6',
                        newState ? 'translate-x-6' : 'translate-x-1'
                    );
                    
                    // Update status text
                    statusText.textContent = newState ? 'Enabled' : 'Disabled';
                    statusText.className = statusText.className.replace(
                        newState ? 'text-gray-400' : 'text-green-400',
                        newState ? 'text-green-400' : 'text-gray-400'
                    );
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        background: '#1f2937',
                        color: '#f9fafb',
                        confirmButtonColor: '#10b981'
                    });
                } else {
                    throw new Error(data.error || 'Unknown error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update admin permission. Please try again.',
                    background: '#1f2937',
                    color: '#f9fafb',
                    confirmButtonColor: '#ef4444'
                });
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    }
});
</script>
@endsection
