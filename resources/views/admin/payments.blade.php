@extends('admin.dashboardIndex')

@section('title', 'Payment History')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payment History</h1>
    </div>

    <!-- Payment History Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Payment Transactions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Subscription Plan</th>
                            <th>Gateway</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img class="avatar-img rounded-circle" 
                                             src="{{ $payment->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($payment->user->name) . '&background=6f42c1&color=ffffff' }}" 
                                             alt="{{ $payment->user->name }}">
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $payment->user->name }}</h6>
                                        <small class="text-muted">{{ $payment->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-weight-bold text-success">
                                    {{ $payment->formatted_amount }}
                                </span>
                            </td>
                            <td>
                                @if($payment->subscription && $payment->subscription->subscriptionPlan)
                                    <span class="badge badge-info">
                                        {{ $payment->subscription->subscriptionPlan->name }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ ucfirst($payment->gateway) }}
                                </span>
                            </td>
                            <td>
                                @if($payment->isSuccessful())
                                    <span class="badge badge-success">Success</span>
                                @elseif($payment->isPending())
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($payment->isFailed())
                                    <span class="badge badge-danger">Failed</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <small class="text-muted">{{ $payment->created_at->format('M d, Y') }}</small><br>
                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($payment->isSuccessful())
                                        <a href="{{ route('transactions.receipt', $payment) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="View Receipt">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-sm btn-outline-info" 
                                            title="View Details" 
                                            data-toggle="modal" 
                                            data-target="#paymentModal{{ $payment->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-credit-card fa-3x mb-3"></i>
                                    <p>No payment records found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Payment Detail Modals -->
@foreach($payments as $payment)
<div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details - #{{ $payment->id }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Payment Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Payment ID:</strong></td>
                                <td>{{ $payment->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td>{{ $payment->formatted_amount }}</td>
                            </tr>
                            <tr>
                                <td><strong>Gateway:</strong></td>
                                <td>{{ ucfirst($payment->gateway) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Reference:</strong></td>
                                <td>{{ $payment->reference ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($payment->isSuccessful())
                                        <span class="badge badge-success">Success</span>
                                    @elseif($payment->isPending())
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($payment->isFailed())
                                        <span class="badge badge-danger">Failed</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Date:</strong></td>
                                <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">User Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $payment->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $payment->user->email }}</td>
                            </tr>
                        </table>
                        
                        @if($payment->subscription && $payment->subscription->subscriptionPlan)
                        <h6 class="font-weight-bold mt-3">Subscription Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Plan:</strong></td>
                                <td>{{ $payment->subscription->subscriptionPlan->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Duration:</strong></td>
                                <td>{{ $payment->subscription->subscriptionPlan->duration_in_days }} days</td>
                            </tr>
                        </table>
                        @endif
                    </div>
                </div>
                
                @if($payment->metadata)
                <div class="mt-3">
                    <h6 class="font-weight-bold">Additional Details</h6>
                    <pre class="bg-light p-2 rounded">{{ json_encode($payment->metadata, JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                @if($payment->isSuccessful())
                    <a href="{{ route('transactions.receipt', $payment) }}" class="btn btn-primary">
                        <i class="fas fa-receipt"></i> View Receipt
                    </a>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('styles')
<style>
.avatar {
    width: 40px;
    height: 40px;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endpush