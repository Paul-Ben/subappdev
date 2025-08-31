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
                        <li class="breadcrumb-item active">Create User</li>
                    </ol>
                </div>
                <h4 class="page-title">Create New User</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-add-line me-2"></i>Create New User Account
                    </h5>
                    <p class="text-muted mb-0">Add a new user to the system with appropriate roles and permissions</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-semibold text-uppercase text-muted mb-3">
                                    <i class="ri-information-line me-1"></i> Basic Information
                                </h6>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-user-line"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Enter user's full name" required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="Enter email address" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Information -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-semibold text-uppercase text-muted mb-3">
                                    <i class="ri-lock-line me-1"></i> Password Information
                                </h6>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-lock-line"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Enter password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Password must be at least 8 characters long</small>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-lock-line"></i></span>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Confirm password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Role Assignment -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-semibold text-uppercase text-muted mb-3">
                                    <i class="ri-shield-user-line me-1"></i> Role Assignment
                                </h6>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">User Roles</label>
                                <div class="row">
                                    @forelse($roles as $role)
                                        <div class="col-md-4 mb-3">
                                            <div class="card border">
                                                <div class="card-body p-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="role_{{ $role->id }}" name="roles[]" 
                                                               value="{{ $role->name }}" 
                                                               {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold" for="role_{{ $role->id }}">
                                                            <i class="ri-shield-user-line me-1"></i>{{ ucfirst($role->name) }}
                                                        </label>
                                                    </div>
                                                    <small class="text-muted">
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
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-warning">
                                                <i class="ri-alert-line me-2"></i>
                                                No roles are available. Please create roles first.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                @error('roles')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-semibold text-uppercase text-muted mb-3">
                                    <i class="ri-settings-line me-1"></i> Account Settings
                                </h6>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="email_verified" name="email_verified" value="1" 
                                                   {{ old('email_verified') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="email_verified">
                                                <i class="ri-checkbox-circle-line me-1"></i>Mark Email as Verified
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            If enabled, the user's email will be marked as verified and they won't need to verify it.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="ri-arrow-left-line me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-user-add-line me-1"></i> Create User Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        function togglePasswordVisibility(toggleBtn, passwordInput) {
            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.className = 'ri-eye-line';
                } else {
                    icon.className = 'ri-eye-off-line';
                }
            });
        }
        
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirm = document.getElementById('password_confirmation');
        
        if (togglePassword && password) {
            togglePasswordVisibility(togglePassword, password);
        }
        
        if (togglePasswordConfirm && passwordConfirm) {
            togglePasswordVisibility(togglePasswordConfirm, passwordConfirm);
        }
        
        // Password confirmation validation
        if (password && passwordConfirm) {
            passwordConfirm.addEventListener('input', function() {
                if (password.value !== passwordConfirm.value) {
                    passwordConfirm.setCustomValidity('Passwords do not match');
                } else {
                    passwordConfirm.setCustomValidity('');
                }
            });
        }
    });
</script>
@endpush