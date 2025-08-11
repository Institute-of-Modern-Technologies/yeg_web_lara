@extends('layouts.admin')

@section('title', 'Student Billing Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Student Billing</h1>
            <p class="mb-0 text-muted">Manage student payments and billing information</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalStudents) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Amount to be Paid</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">GH₵ {{ number_format($totalAmountToBePaid, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Amount Paid</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">GH₵ {{ number_format($totalAmountPaid, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Balance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">GH₵ {{ number_format($totalBalance, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #950713;">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.billing.index') }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Search Students</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by name, email, or registration number...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment_status">Payment Status</label>
                            <select class="form-control" id="payment_status" name="payment_status">
                                <option value="">All Students</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Fully Paid</option>
                                <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partially Paid</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Billing Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #950713;">Student Billing Information</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Registration No.</th>
                            <th>Program Type</th>
                            <th>School</th>
                            <th>Amount to be Paid</th>
                            <th>Amount Paid</th>
                            <th>Balance</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                $amountToBePaid = $student->fee_amount ?? 0;
                                $totalPaid = $student->total_paid ?? 0;
                                $balance = $amountToBePaid - $totalPaid;
                                
                                if ($balance <= 0) {
                                    $statusClass = 'success';
                                    $statusText = 'Fully Paid';
                                } elseif ($totalPaid > 0) {
                                    $statusClass = 'warning';
                                    $statusText = 'Partially Paid';
                                } else {
                                    $statusClass = 'danger';
                                    $statusText = 'Unpaid';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $student->full_name }}</div>
                                            <div class="text-muted small">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->registration_number ?? 'N/A' }}</td>
                                <td>{{ $student->programType->name ?? 'N/A' }}</td>
                                <td>{{ $student->display_school_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="font-weight-bold text-primary">
                                        GH₵ {{ number_format($amountToBePaid, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-success">
                                        GH₵ {{ number_format($totalPaid, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-weight-bold {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                        GH₵ {{ number_format($balance, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.billing.show', $student) }}">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.students.show', $student) }}">
                                                <i class="fas fa-user"></i> Student Profile
                                            </a>
                                            @if($student->payments->count() > 0)
                                                <div class="dropdown-divider"></div>
                                                <h6 class="dropdown-header">Payment Receipts</h6>
                                                @foreach($student->payments->where('status', 'completed') as $payment)
                                                    <a class="dropdown-item" href="{{ route('admin.payments.receipt', $payment->id) }}">
                                                        <i class="fas fa-receipt"></i> Receipt #{{ $payment->id }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <p>No students found matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} 
                    of {{ $students->total() }} results
                </div>
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
@endsection
