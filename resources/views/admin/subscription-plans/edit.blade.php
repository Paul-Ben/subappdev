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
                            <li class="breadcrumb-item"><a href="{{ route('subscription-plans.index') }}">Subscription
                                    Plans</a></li>
                            <li class="breadcrumb-item active">Edit Plan</li>
                        </ol>
                        <div class="d-flex justify-content-end mt-2">
                            <a href="{{ route('subscription-plans.index') }}" class="btn btn-secondary ms-2">
                                <i class="ri-arrow-left-line me-1"></i> Back to Plans
                            </a>
                        </div>
                    </div>
                    <h4 class="page-title">Edit Subscription Plan</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="ri-edit-line me-2"></i>Edit Subscription Plan:
                            {{ $subscriptionPlan->name }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('subscription-plans.update', $subscriptionPlan) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Plan Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $subscriptionPlan->name) }}"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="billing_cycle" class="form-label">Billing Cycle <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('billing_cycle') is-invalid @enderror"
                                            id="billing_cycle" name="billing_cycle" required>
                                            <option value="">Select Billing Cycle</option>
                                            <option value="free"
                                                {{ old('billing_cycle', $subscriptionPlan->billing_cycle) == 'free' ? 'selected' : '' }}>
                                                Free</option>
                                            <option value="monthly"
                                                {{ old('billing_cycle', $subscriptionPlan->billing_cycle) == 'monthly' ? 'selected' : '' }}>
                                                Monthly</option>
                                            <option value="yearly"
                                                {{ old('billing_cycle', $subscriptionPlan->billing_cycle) == 'yearly' ? 'selected' : '' }}>
                                                Yearly</option>
                                        </select>
                                        @error('billing_cycle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price (₦) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0"
                                            class="form-control @error('price') is-invalid @enderror" id="price"
                                            name="price" value="{{ old('price', $subscriptionPlan->price) }}" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sort_order" class="form-label">Sort Order <span
                                                class="text-danger">*</span></label>
                                        <input type="number" min="0"
                                            class="form-control @error('sort_order') is-invalid @enderror" id="sort_order"
                                            name="sort_order"
                                            value="{{ old('sort_order', $subscriptionPlan->sort_order) }}" required>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $subscriptionPlan->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="max_participants" class="form-label">Max Participants <span
                                                class="text-danger">*</span></label>
                                        <input type="number" min="1"
                                            class="form-control @error('max_participants') is-invalid @enderror"
                                            id="max_participants" name="max_participants"
                                            value="{{ old('max_participants', $subscriptionPlan->max_participants) }}"
                                            required>
                                        @error('max_participants')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="meeting_duration_limit" class="form-label">Meeting Duration
                                            (minutes)</label>
                                        <input type="number" min="1"
                                            class="form-control @error('meeting_duration_limit') is-invalid @enderror"
                                            id="meeting_duration_limit" name="meeting_duration_limit"
                                            value="{{ old('meeting_duration_limit', $subscriptionPlan->meeting_duration_limit) }}"
                                            placeholder="Leave empty for unlimited">
                                        @error('meeting_duration_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="storage_limit" class="form-label">Storage Limit (GB)</label>
                                        <input type="number" min="1"
                                            class="form-control @error('storage_limit') is-invalid @enderror"
                                            id="storage_limit" name="storage_limit"
                                            value="{{ old('storage_limit', $subscriptionPlan->storage_limit) }}"
                                            placeholder="Leave empty for unlimited">
                                        @error('storage_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Features</h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="has_recording"
                                                name="has_recording" value="1"
                                                {{ old('has_recording', $subscriptionPlan->has_recording) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_recording">
                                                Meeting Recording
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="has_breakout_rooms"
                                                name="has_breakout_rooms" value="1"
                                                {{ old('has_breakout_rooms', $subscriptionPlan->has_breakout_rooms) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_breakout_rooms">
                                                Breakout Rooms
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="has_admin_tools"
                                                name="has_admin_tools" value="1"
                                                {{ old('has_admin_tools', $subscriptionPlan->has_admin_tools) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_admin_tools">
                                                Admin Tools
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1"
                                                {{ old('is_active', $subscriptionPlan->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active Plan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="border-top pt-3">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('subscription-plans.index') }}" class="btn btn-light">
                                                <i class="ri-arrow-left-line me-1"></i> Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line me-1"></i> Update Subscription Plan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- container -->
@endsection
