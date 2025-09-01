@extends('user.dashboardIndex')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Payment Receipt</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Receipt #{{ $payment->payment_reference }}</h5>
                </div>
                <div class="card-body">
                    <!-- Receipt Header -->
                    <div class="text-center mb-4">
                        <h2 class="mb-2">Payment Receipt</h2>
                        <p class="text-muted">Thank you for your payment!</p>
                    </div>

                    <!-- Receipt Details -->
                    <div class="row mb-4">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Customer Information</h5>
                            <div>
                                <p><strong>Name:</strong> {{ $user->name }}</p>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Customer ID:</strong> #{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Payment Information</h5>
                            <div>
                                <p><strong>Reference:</strong> {{ $payment->payment_reference }}</p>
                                <p><strong>Date:</strong> {{ $payment->created_at->format('M d, Y H:i A') }}</p>
                                <p><strong>Gateway:</strong> {{ ucfirst($payment->gateway) }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="badge 
                                        @if($payment->status === 'completed') bg-success
                                        @elseif($payment->status === 'pending') bg-warning
                                        @else bg-danger @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Details -->
                    @if($payment->subscription && $payment->subscription->subscriptionPlan)
                    <div class="mb-4">
                        <h5 class="mb-3">Subscription Details</h5>
                        <div class="bg-light rounded p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Plan:</strong> {{ $payment->subscription->subscriptionPlan->name }}</p>
                                    <p><strong>Billing Cycle:</strong> {{ ucfirst($payment->subscription->subscriptionPlan->billing_cycle) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Start Date:</strong> {{ $payment->subscription->starts_at->format('M d, Y') }}</p>
                                    <p><strong>End Date:</strong> {{ $payment->subscription->ends_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Amount Breakdown -->
                    <div class="mb-4">
                        <h5 class="mb-3">Amount Breakdown</h5>
                        <div class="bg-light rounded p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subscription Fee:</span>
                                <span>₦{{ number_format($payment->amount / 100, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>₦0.00</span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between h5">
                                <span>Total Amount:</span>
                                <span>₦{{ number_format($payment->amount / 100, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center text-muted small">
                        <p>This is an automatically generated receipt.</p>
                        <p class="mt-2">For any questions, please contact our support team.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary me-2">
                            <i class="ri-arrow-left-line me-1"></i> Back to Transactions
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="ri-printer-line me-1"></i> Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .bg-light {
            background-color: #f8f9fa !important;
        }
    }
</style>
@endsection