@extends('admin.dashboardIndex')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
                        <li class="breadcrumb-item active">User Details</li>
                    </ol>
                </div>
                <h4 class="page-title">User Details: {{ $user->name }}</h4>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back to Users
                    </a>
                </div>
                <div class="d-flex gap-2">
                    @if($user->email_verified_at)
                        <form method="POST" action="{{ route('admin.users.toggleVerification', $user) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning" 
                                    onclick="return confirm('Are you sure you want to mark this email as unverified?')">
                                <i class="ri-close-circle-line me-1"></i> Mark Unverified
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.toggleVerification', $user) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success">
                                <i class="ri-checkbox-circle-line me-1"></i> Mark Verified
                            </button>
                        </form>
                    @endif
                    
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-info" 
                                    onclick="return confirm('Are you sure you want to impersonate this user?')">
                                <i class="ri-user-shared-line me-1"></i> Impersonate
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="ri-edit-line me-1"></i> Edit User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar-lg mx-auto">
                            <div class="avatar-title bg-primary rounded-circle fs-2">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    <!-- Status Badges -->
                    <div class="mb-3">
                        @if($user->email_verified_at)
                            <span class="badge bg-success me-1">
                                <i class="ri-checkbox-circle-line me-1"></i>Email Verified
                            </span>
                        @else
                            <span class="badge bg-warning me-1">
                                <i class="ri-error-warning-line me-1"></i>Email Not Verified
                            </span>
                        @endif
                        
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <span class="badge bg-info me-1">
                                    <i class="ri-shield-user-line me-1"></i>{{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">
                                <i class="ri-user-line me-1"></i>No Roles
                            </span>
                        @endif
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="mb-1">{{ $user->created_at->diffForHumans() }}</h5>
                                <p class="text-muted mb-0">Member Since</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-1">{{ $user->updated_at->diffForHumans() }}</h5>
                            <p class="text-muted mb-0">Last Updated</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Account Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-2"></i>Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">User ID:</td>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Full Name:</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Email:</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Email Status:</td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                            <small class="text-muted d-block">{{ $user->email_verified_at->format('M d, Y \\a\\t g:i A') }}</small>
                                        @else
                                            <span class="badge bg-warning">Not Verified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Account Created:</td>
                                    <td>
                                        {{ $user->created_at->format('M d, Y \\a\\t g:i A') }}
                                        <small class="text-muted d-block">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Last Updated:</td>
                                    <td>
                                        {{ $user->updated_at->format('M d, Y \\a\\t g:i A') }}
                                        <small class="text-muted d-block">{{ $user->updated_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Details and Activity -->
        <div class="col-lg-8">
            <!-- Roles and Permissions Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-shield-user-line me-2"></i>Roles and Permissions
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="row">
                            @foreach($user->roles as $role)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title bg-primary rounded">
                                                        <i class="ri-shield-user-line"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ ucfirst($role->name) }}</h6>
                                                    <p class="text-muted mb-0">
                                                        @switch($role->name)
                                                            @case('admin')
                                                                Full system access and management capabilities
                                                                @break
                                                            @case('user')
                                                                Standard user access with basic features
                                                                @break
                                                            @default
                                                                {{ ucfirst($role->name) }} role permissions
                                                        @endswitch
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-light text-muted rounded-circle">
                                    <i class="ri-user-line fs-2"></i>
                                </div>
                            </div>
                            <h5 class="text-muted">No Roles Assigned</h5>
                            <p class="text-muted mb-3">This user has not been assigned any roles yet.</p>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-1"></i> Assign Roles
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Account Activity Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-time-line me-2"></i>Account Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Account Created</h6>
                                <p class="text-muted mb-1">User account was created and registered in the system</p>
                                <small class="text-muted">{{ $user->created_at->format('M d, Y \\a\\t g:i A') }}</small>
                            </div>
                        </div>
                        
                        @if($user->email_verified_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Email Verified</h6>
                                    <p class="text-muted mb-1">User verified their email address</p>
                                    <small class="text-muted">{{ $user->email_verified_at->format('M d, Y \\a\\t g:i A') }}</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($user->updated_at != $user->created_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Profile Updated</h6>
                                    <p class="text-muted mb-1">User profile information was last updated</p>
                                    <small class="text-muted">{{ $user->updated_at->format('M d, Y \\a\\t g:i A') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-tools-line me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                                    <i class="ri-edit-line me-2"></i>Edit User Profile
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                @if($user->email_verified_at)
                                    <form method="POST" action="{{ route('admin.users.toggleVerification', $user) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning w-100" 
                                                onclick="return confirm('Are you sure you want to mark this email as unverified?')">
                                            <i class="ri-close-circle-line me-2"></i>Mark Unverified
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.toggleVerification', $user) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success w-100">
                                            <i class="ri-checkbox-circle-line me-2"></i>Verify Email
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @if($user->id !== auth()->id())
                            <div class="col-md-6 mb-3">
                                <div class="d-grid">
                                    <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-info w-100" 
                                                onclick="return confirm('Are you sure you want to impersonate this user?')">
                                            <i class="ri-user-shared-line me-2"></i>Impersonate User
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-grid">
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger w-100" 
                                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            <i class="ri-delete-bin-line me-2"></i>Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}

.avatar-lg {
    width: 80px;
    height: 80px;
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: #fff;
    font-weight: 600;
}
</style>
@endpush