@extends('layouts.admin')

@section('title', 'Student Billing Details - ' . $student->full_name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Student Billing Details</h1>
            <p class="mb-0 text-muted">{{ $student->full_name }} - Payment Information</p>
        </div>
        <a href="{{ route('admin.billing.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Billing
        </a>
    </div>

    <div class="row">
        <!-- Student Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #950713;">Student Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="icon-circle bg-primary mx-auto mb-2" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                        <h5 class="font-weight-bold">{{ $student->full_name }}</h5>
                        <p class="text-muted">{{ $student->email }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Registration No:</strong></p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted mb-0">{{ $student->registration_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Program Type:</strong></p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted mb-0">{{ $student->programType->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>School:</strong></p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted mb-0">{{ $student->display_school_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Status:</strong></p>
                        </div>
                        <div class="col-sm-6">
                            <span class="badge badge-{{ $student->status == 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Summary -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #950713;">Billing Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-left-primary h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Amount to be Paid
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                GH₵ {{ number_format($amountToBePaid, 2) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-left-success h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Amount Paid
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                GH₵ {{ number_format($totalPaid, 2) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-left-{{ $balance > 0 ? 'warning' : 'success' }} h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-{{ $balance > 0 ? 'warning' : 'success' }} text-uppercase mb-1">
                                                Balance
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                GH₵ {{ number_format($balance, 2) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($fee)
                        <div class="mt-4">
                            <h6 class="font-weight-bold">Fee Structure Details:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Base Amount:</strong></td>
                                        <td>GH₵ {{ number_format($fee->amount, 2) }}</td>
                                    </tr>
                                    @if($fee->partner_discount > 0)
                                        <tr>
                                            <td><strong>Partner Discount:</strong></td>
                                            <td class="text-success">- GH₵ {{ number_format($fee->partner_discount, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="font-weight-bold">
                                        <td><strong>Final Amount:</strong></td>
                                        <td>GH₵ {{ number_format($amountToBePaid, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #950713;">Payment History</h6>
                </div>
                <div class="card-body">
                    @if($student->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Final Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                            <td>GH₵ {{ number_format($payment->amount, 2) }}</td>
                                            <td>
                                                @if($payment->discount > 0)
                                                    <span class="text-success">GH₵ {{ number_format($payment->discount, 2) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="font-weight-bold">GH₵ {{ number_format($payment->final_amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($payment->status == 'completed')
                                                    <a href="{{ route('admin.payments.receipt', $payment->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-receipt"></i> Receipt
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="4" class="text-right">Total Paid:</td>
                                        <td>GH₵ {{ number_format($totalPaid, 2) }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No payments recorded for this student.</p>
                        </div>
                    @endif
                </div>
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

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
@endsection
