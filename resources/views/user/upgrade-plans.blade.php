@extends('user.dashboardIndex')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Upgrade Your Plan</h4>
            </div>
        </div>
    </div>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-xxl-10">
            <div class="text-center mb-4">
                <h3 class="mb-2">Choose Your Plan</h3>
                <h5 class="text-muted">
                    Select the plan that fits your needs and upgrade instantly.
                </h5>
                
                @if($currentSubscription)
                <div class="alert alert-info mt-3 d-inline-block">
                    <strong>Current Plan:</strong> 
                    {{ $currentSubscription->subscriptionPlan->name }} 
                    (₦{{ number_format($currentSubscription->subscriptionPlan->price / 100, 2) }}/{{ $currentSubscription->subscriptionPlan->billing_cycle }})
                </div>
                @endif
            </div>

            <!-- Plans -->
            <div class="row justify-content-center my-3">
                @foreach($availablePlans as $plan)
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                    <div class="card h-100 {{ $plan->billing_cycle === 'yearly' ? 'border-primary' : '' }}">
                        <div class="p-3">
                            @if($plan->billing_cycle === 'yearly')
                                <div class="badge bg-primary-subtle text-primary fs-14 px-2 py-1 mb-3">Most Popular</div>
                                <div class="avatar-md d-flex align-items-center justify-content-center bg-primary-subtle text-primary border border-primary mb-3">
                                    <i class="ri-vip-crown-line fs-36"></i>
                                </div>
                            @elseif($plan->billing_cycle === 'monthly')
                                <div class="badge bg-info-subtle text-info fs-14 px-2 py-1 mb-3">{{ ucfirst($plan->billing_cycle) }} Plan</div>
                                <div class="avatar-md d-flex align-items-center justify-content-center bg-info-subtle text-info border border-info mb-3">
                                    <i class="ri-calendar-line fs-36"></i>
                                </div>
                            @else
                                <div class="badge bg-secondary-subtle text-secondary fs-14 px-2 py-1 mb-3">{{ $plan->name }}</div>
                                <div class="avatar-md d-flex align-items-center justify-content-center bg-secondary-subtle text-secondary border border-secondary mb-3">
                                    <i class="ri-star-line fs-36"></i>
                                </div>
                            @endif
                            
                            <h2 class="">₦{{ number_format($plan->price / 100, 2) }} <span class="fw-medium fs-16">/ {{ $plan->billing_cycle }}</span></h2>
                            <h5 class="fw-medium mb-0">{{ $plan->description ?? 'Perfect for your needs' }}</h5>
                            @if($plan->billing_cycle === 'yearly')
                            <p class="text-success fs-14 mt-2 mb-0">Save with annual billing</p>
                            @endif
                        </div>
                        <hr class="m-0">

                        <div class="p-3 d-flex flex-column h-100">
                            <ul class="flex-grow-1 list-unstyled d-flex flex-column gap-2 mb-0">
                                <!-- Meeting Duration -->
                                <li class="fs-15">
                                    <i class="ri-check-line text-success fs-20 lh-sm me-2"></i>
                                    Meeting Duration: {{ $plan->meeting_duration_display }}
                                </li>
                                
                                <!-- Max Participants -->
                                <li class="fs-15">
                                    <i class="ri-check-line text-success fs-20 lh-sm me-2"></i>
                                    Up to {{ $plan->max_participants }} participants
                                </li>
                                
                                <!-- Storage -->
                                <li class="fs-15">
                                    <i class="ri-check-line text-success fs-20 lh-sm me-2"></i>
                                    Storage: {{ $plan->storage_display }}
                                </li>
                                
                                <!-- Recording -->
                                <li class="fs-15">
                                    <i class="{{ $plan->has_recording ? 'ri-check-line text-success' : 'ri-close-line text-danger' }} fs-20 lh-sm me-2"></i>
                                    Recording capabilities
                                </li>
                                
                                <!-- Breakout Rooms -->
                                <li class="fs-15">
                                    <i class="{{ $plan->has_breakout_rooms ? 'ri-check-line text-success' : 'ri-close-line text-danger' }} fs-20 lh-sm me-2"></i>
                                    Breakout rooms
                                </li>
                                
                                <!-- Admin Tools -->
                                <li class="fs-15">
                                    <i class="{{ $plan->has_admin_tools ? 'ri-check-line text-success' : 'ri-close-line text-danger' }} fs-20 lh-sm me-2"></i>
                                    Advanced admin tools
                                </li>
                                
                                <!-- Basic Features (always included) -->
                                <li class="fs-15">
                                    <i class="ri-check-line text-success fs-20 lh-sm me-2"></i>
                                    HD video & audio
                                </li>
                                
                                <li class="fs-15">
                                    <i class="ri-check-line text-success fs-20 lh-sm me-2"></i>
                                    Screen sharing
                                </li>
                                
                                <li class="fs-15">
                                    <i class="ri-check-line text-success fs-20 lh-sm me-2"></i>
                                    Chat messaging
                                </li>
                            </ul>

                            <div class="flex-shrink-0">
                                <form action="{{ route('subscription.initiate-upgrade') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                    <button type="submit" class="btn w-100 {{ $plan->billing_cycle === 'yearly' ? 'btn-primary' : 'btn-info' }}">
                                        Upgrade to {{ $plan->name }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div> <!-- end Pricing_card -->
                </div> <!-- end col -->
                @endforeach
            </div>
            <!-- end row -->

            <!-- Back to Dashboard -->
            <div class="text-center mt-4">
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Back to Dashboard
                </a>
            </div>

        </div> <!-- end col-->
    </div>
    <!-- end row -->

</div> <!-- container -->
@endsection