@extends('user.dashboardIndex')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Transaction History</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment History</h5>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                            <td>{{ $transaction->payment_reference }}</td>
                                            <td>{{ $transaction->subscription->subscriptionPlan->name ?? 'N/A' }}</td>
                                            <td>₦{{ number_format($transaction->amount / 100, 2) }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($transaction->status === 'completed') bg-success
                                                    @elseif($transaction->status === 'pending') bg-warning
                                                    @else bg-danger @endif">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.receipt', $transaction->id) }}" 
                                                   class="btn btn-sm btn-outline-primary me-2">
                                                    <i class="ri-eye-line"></i> View
                                                </a>
                                                <a href="{{ route('transactions.download', $transaction->id) }}" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="ri-download-line"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="ri-file-list-line text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="mb-3">No transactions found</h5>
                            <p class="text-muted mb-4">You haven't made any payments yet.</p>
                            <a href="{{ route('subscription.plans') }}" class="btn btn-primary">
                                <i class="ri-eye-line me-1"></i> View Plans
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection