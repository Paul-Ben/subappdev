@extends('admin.dashboardIndex')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('subscription-plans.index') }}">Subscription Plans</a></li>
                            <li class="breadcrumb-item active">{{ $subscriptionPlan->name }}</li>
                        </ol>
                        <div class="d-flex justify-content-end mt-2 gap-2">
                            <a href="{{ route('subscription-plans.edit', $subscriptionPlan) }}" class="btn btn-warning">
                                <i class="ri-edit-line me-1"></i> Edit Plan
                            </a>
                            <a href="{{ route('subscription-plans.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Back to Plans
                            </a>
                        </div>
                    </div>
                    <h4 class="page-title">Subscription Plan Details</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <!-- Plan Overview -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar-lg bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <i class="ri-price-tag-3-line text-white fs-24"></i>
                        </div>
                        <h4 class="mb-1">{{ $subscriptionPlan->name }}</h4>
                        <p class="text-muted mb-3">{{ $subscriptionPlan->description ?? 'No description available' }}</p>
                        
                        <div class="mb-3">
                            <h2 class="text-primary mb-1">{{ $subscriptionPlan->formatted_price }}</h2>
                            <span class="badge bg-info-subtle text-info fs-13">{{ ucfirst($subscriptionPlan->billing_cycle) }}</span>
                        </div>

                        <div class="mb-3">
                            @if($subscriptionPlan->is_active)
                                <span class="badge bg-success fs-14"><i class="ri-check-line me-1"></i>Active Plan</span>
                            @else
                                <span class="badge bg-danger fs-14"><i class="ri-close-line me-1"></i>Inactive Plan</span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('subscription-plans.edit', $subscriptionPlan) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-1"></i> Edit Plan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Plan Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ri-bar-chart-line me-2"></i>Plan Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Active Subscriptions</span>
                            <span class="fw-bold">{{ $subscriptionPlan->activeSubscriptions()->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Total Subscriptions</span>
                            <span class="fw-bold">{{ $subscriptionPlan->subscriptions()->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Sort Order</span>
                            <span class="fw-bold">#{{ $subscriptionPlan->sort_order }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Details -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Plan Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted mb-3">Basic Information</h6>
                                <table class="table table-borderless mb-4">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold text-muted">Plan Name:</td>
                                            <td>{{ $subscriptionPlan->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Slug:</td>
                                            <td><code>{{ $subscriptionPlan->slug }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Price:</td>
                                            <td class="fw-bold text-primary">{{ $subscriptionPlan->formatted_price }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Billing Cycle:</td>
                                            <td><span class="badge bg-info-subtle text-info">{{ ucfirst($subscriptionPlan->billing_cycle) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Status:</td>
                                            <td>
                                                @if($subscriptionPlan->is_active)
                                                    <span class="badge bg-success"><i class="ri-check-line me-1"></i>Active</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="ri-close-line me-1"></i>Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted mb-3">Meeting Limits</h6>
                                <table class="table table-borderless mb-4">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold text-muted">Max Participants:</td>
                                            <td><i class="ri-group-line text-primary me-1"></i>{{ number_format($subscriptionPlan->max_participants) }} participants</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Meeting Duration:</td>
                                            <td><i class="ri-time-line text-primary me-1"></i>{{ $subscriptionPlan->meeting_duration_display }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Storage Limit:</td>
                                            <td><i class="ri-database-line text-primary me-1"></i>{{ $subscriptionPlan->storage_display }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Sort Order:</td>
                                            <td><span class="badge bg-secondary">#{{ $subscriptionPlan->sort_order }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ri-star-line me-2"></i>Plan Features</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center p-3 border rounded {{ $subscriptionPlan->has_recording ? 'bg-success-subtle border-success' : 'bg-light border-light' }}">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-{{ $subscriptionPlan->has_recording ? 'success' : 'secondary' }} rounded d-flex align-items-center justify-content-center">
                                            <i class="ri-record-circle-line text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Recording</h6>
                                        <p class="text-muted mb-0 fs-13">
                                            @if($subscriptionPlan->has_recording)
                                                <span class="text-success fw-semibold">Enabled</span>
                                            @else
                                                <span class="text-muted">Disabled</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center p-3 border rounded {{ $subscriptionPlan->has_breakout_rooms ? 'bg-primary-subtle border-primary' : 'bg-light border-light' }}">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-{{ $subscriptionPlan->has_breakout_rooms ? 'primary' : 'secondary' }} rounded d-flex align-items-center justify-content-center">
                                            <i class="ri-team-line text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Breakout Rooms</h6>
                                        <p class="text-muted mb-0 fs-13">
                                            @if($subscriptionPlan->has_breakout_rooms)
                                                <span class="text-primary fw-semibold">Enabled</span>
                                            @else
                                                <span class="text-muted">Disabled</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center p-3 border rounded {{ $subscriptionPlan->has_admin_tools ? 'bg-warning-subtle border-warning' : 'bg-light border-light' }}">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-{{ $subscriptionPlan->has_admin_tools ? 'warning' : 'secondary' }} rounded d-flex align-items-center justify-content-center">
                                            <i class="ri-settings-3-line text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Admin Tools</h6>
                                        <p class="text-muted mb-0 fs-13">
                                            @if($subscriptionPlan->has_admin_tools)
                                                <span class="text-warning fw-semibold">Enabled</span>
                                            @else
                                                <span class="text-muted">Disabled</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($subscriptionPlan->description)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ri-file-text-line me-2"></i>Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">{{ $subscriptionPlan->description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
    <!-- container -->
@endsection