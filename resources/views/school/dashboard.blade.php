@extends('layouts.school')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-green-400 to-blue-500 bg-clip-text text-transparent">{{ $school->name }} Dashboard</h1>
        <p class="mt-2 text-gray-400">Welcome back! Here's an overview of your school's activity.</p>
    </div>

    <!-- Admin Permission Toggle -->
    <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-2xl shadow-2xl border border-gray-800 p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">Admin Management Permission</h3>
                <p class="text-sm text-gray-400 mt-1">
                    Allow YEG administrators to manage your students. You can revoke this permission at any time.
                </p>
            </div>
            <div class="flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" 
                           id="admin-permission-toggle" 
                           class="sr-only peer" 
                           {{ $school->allow_admin_management ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-500 peer-checked:to-green-600"></div>
                </label>
                <span class="ml-3 text-sm font-medium text-white">
                    {{ $school->allow_admin_management ? 'Enabled' : 'Disabled' }}
                </span>
            </div>
        </div>
        @if($school->allow_admin_management && $school->admin_permission_granted_at)
            <div class="mt-3 text-xs text-gray-500">
                Permission granted on {{ $school->admin_permission_granted_at->format('M d, Y \a\t g:i A') }}
                @if($school->admin_permission_granted_by)
                    by {{ $school->admin_permission_granted_by }}
                @endif
            </div>
        @endif
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-2xl shadow-2xl border border-gray-800 p-6 hover:shadow-blue-500/10 transition-all duration-200">
            <div class="flex items-center">
                <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-400">Total Students</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-blue-500 bg-clip-text text-transparent">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-2xl shadow-2xl border border-gray-800 p-6 hover:shadow-green-500/10 transition-all duration-200">
            <div class="flex items-center">
                <div class="p-4 rounded-2xl bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-400">Active Students</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-green-400 to-green-500 bg-clip-text text-transparent">{{ $activeStudents }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-2xl shadow-2xl border border-gray-800 p-6 hover:shadow-purple-500/10 transition-all duration-200">
            <div class="flex items-center">
                <div class="p-4 rounded-2xl bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg">
                    <i class="fas fa-user-cog text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-400">With Accounts</h3>
                    <p class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-purple-500 bg-clip-text text-transparent">{{ $studentsWithAccounts }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-2xl shadow-2xl border border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('school.students.index') }}" 
                   class="flex items-center p-4 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-blue-500/25">
                    <i class="fas fa-users mr-3 text-lg"></i>
                    <span class="font-medium">Manage Students</span>
                </a>
                <a href="#" 
                   class="flex items-center p-4 text-gray-300 bg-gradient-to-r from-gray-800 to-gray-850 rounded-xl hover:from-gray-700 hover:to-gray-800 hover:text-white transition-all duration-200">
                    <i class="fas fa-chart-bar mr-3 text-lg"></i>
                    <span class="font-medium">View Reports</span>
                </a>
                <a href="#" 
                   class="flex items-center p-4 text-gray-300 bg-gradient-to-r from-gray-800 to-gray-850 rounded-xl hover:from-gray-700 hover:to-gray-800 hover:text-white transition-all duration-200">
                    <i class="fas fa-cog mr-3 text-lg"></i>
                    <span class="font-medium">School Settings</span>
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-2xl shadow-2xl border border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
            <div class="space-y-3">
                @if($students->count() > 0)
                    @foreach($students->take(3) as $student)
                        <div class="flex items-center p-4 bg-gradient-to-r from-gray-800 to-gray-850 rounded-xl hover:from-gray-750 hover:to-gray-800 transition-all duration-200">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-lg">
                                {{ substr($student->full_name ?: $student->first_name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-white">{{ $student->full_name ?: $student->first_name . ' ' . $student->last_name }}</p>
                                <p class="text-xs text-gray-400">Added {{ $student->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clock text-3xl text-gray-600 mb-2"></i>
                        <p class="text-gray-400 text-sm">No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 for modals -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Admin Permission Toggle
document.getElementById('admin-permission-toggle').addEventListener('change', function() {
    const allowAdmin = this.checked;
    
    fetch('{{ route("school.toggle-admin-permission") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            allow_admin: allowAdmin
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Permission Updated',
                text: data.message,
                timer: 3000,
                showConfirmButton: false
            });
            
            // Update the status text
            const statusText = document.querySelector('.ml-3.text-sm.font-medium.text-gray-900');
            statusText.textContent = allowAdmin ? 'Enabled' : 'Disabled';
            
            // Reload page to update timestamp
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
            // Revert toggle
            this.checked = !allowAdmin;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating permission.'
        });
        // Revert toggle
        this.checked = !allowAdmin;
    });
});
</script>
@endsection
