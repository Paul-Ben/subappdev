@extends('user.dashboardIndex')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Welcome, {{ Auth::user()->name }}!</h5>
                    <p class="card-text">You're currently subscribed to our 
                        @if(Auth::user()->activeSubscription())
                            <strong>{{ Auth::user()->activeSubscription()->subscriptionPlan->name }}</strong>
                            @if(Auth::user()->activeSubscription()->ends_at)
                                <br><small class="text-muted">Expires on {{ Auth::user()->activeSubscription()->ends_at->format('M d, Y') }}</small>
                            @endif
                        @else
                            <strong>Free Plan</strong>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Plan Features -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Your Plan Features</h5>
                </div>
                <div class="card-body">
                    @if(Auth::user()->activeSubscription())
                        @php
                            $plan = Auth::user()->activeSubscription()->subscriptionPlan;
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <h6>{{ $plan->name }}</h6>
                                <p class="text-muted">{{ $plan->description }}</p>
                                <ul class="list-unstyled">
                                    @if($plan->name === 'Free Plan')
                                        <li><i class="ri-check-line text-success me-2"></i>Basic virtual meetings</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Up to 3 participants</li>
                                        <li><i class="ri-check-line text-success me-2"></i>30-minute meeting duration</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Basic screen sharing</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Chat messaging</li>
                                    @elseif($plan->name === 'Monthly Plan')
                                        <li><i class="ri-check-line text-success me-2"></i>Unlimited virtual meetings</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Up to 50 participants</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Unlimited meeting duration</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Advanced screen sharing</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Recording capabilities</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Priority support</li>
                                    @elseif($plan->name === 'Yearly Plan')
                                        <li><i class="ri-check-line text-success me-2"></i>All Monthly Plan features</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Up to 100 participants</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Advanced analytics</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Custom branding</li>
                                        <li><i class="ri-check-line text-success me-2"></i>API access</li>
                                        <li><i class="ri-check-line text-success me-2"></i>Dedicated support</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <h3 class="text-primary">₦{{ number_format($plan->price / 100, 2) }}</h3>
                                    <p class="text-muted">{{ $plan->name === 'Yearly Plan' ? 'per year' : ($plan->name === 'Monthly Plan' ? 'per month' : 'free forever') }}</p>
                                    @if($plan->name !== 'Yearly Plan')
                                        <a href="{{ route('subscription.plans') }}" class="btn btn-primary">Upgrade Plan</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h6>No Active Subscription</h6>
                            <p>You don't have an active subscription. Please contact support or choose a plan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-video-add-line text-primary" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-3">Start Meeting</h5>
                    <p class="card-text">Create a new virtual meeting instantly</p>
                    <a href="#" class="btn btn-primary">Start Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-calendar-line text-success" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-3">Schedule Meeting</h5>
                    <p class="card-text">Plan your meetings in advance</p>
                    <a href="#" class="btn btn-success">Schedule</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-group-line text-info" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-3">Join Meeting</h5>
                    <p class="card-text">Enter a meeting with an ID</p>
                    <a href="#" class="btn btn-info">Join</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-file-list-line text-secondary" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-3">Transaction History</h5>
                    <p class="card-text">View your payment history and receipts</p>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">View History</a>
                </div>
            </div>
        </div>
        @if(Auth::user()->activeSubscription() && Auth::user()->activeSubscription()->subscriptionPlan->name === 'Free Plan')
        <div class="col-md-4 mt-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-arrow-up-line text-warning" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-3">Upgrade Plan</h5>
                    <p class="card-text">Get more features with premium plans</p>
                    <a href="{{ route('subscription.plans') }}" class="btn btn-warning">Upgrade Now</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection