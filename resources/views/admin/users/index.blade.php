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
                        <li class="breadcrumb-item active">User Management</li>
                    </ol>
                </div>
                <h4 class="page-title">User Management</h4>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label fw-semibold">Search Users</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-search-line"></i></span>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Search by name or email...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label fw-semibold">Filter by Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-semibold">Email Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-search-line me-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="ri-team-line me-2"></i>Users Management
                        </h5>
                        <p class="text-muted mb-0">Manage all user accounts and their permissions</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Add New User
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>User Details</th>
                                        <th>Roles</th>
                                        <th>Email Status</th>
                                        <th>Joined Date</th>
                                        <th>Last Activity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="ri-user-line text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($user->roles->count() > 0)
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-info-subtle text-info me-1">
                                                            <i class="ri-shield-user-line me-1"></i>{{ ucfirst($role->name) }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        <i class="ri-user-line me-1"></i>No Role
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ri-checkbox-circle-line me-1"></i>Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="ri-error-warning-line me-1"></i>Unverified
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-medium">{{ $user->created_at->format('M d, Y') }}</span>
                                                <br>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if($user->updated_at)
                                                    <span class="fw-medium">{{ $user->updated_at->format('M d, Y') }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" 
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-2-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                                                <i class="ri-eye-line me-2"></i>View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                                                <i class="ri-edit-line me-2"></i>Edit User
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.toggle-verification', $user) }}" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="dropdown-item">
                                                                    @if($user->email_verified_at)
                                                                        <i class="ri-close-circle-line me-2"></i>Mark Unverified
                                                                    @else
                                                                        <i class="ri-checkbox-circle-line me-2"></i>Mark Verified
                                                                    @endif
                                                                </button>
                                                            </form>
                                                        </li>
                                                        @if($user->id !== auth()->id())
                                                            <li>
                                                                <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="ri-user-shared-line me-2"></i>Impersonate
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="ri-delete-bin-line me-2"></i>Delete User
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                                    </div>
                                    {{ $users->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="avatar-lg bg-light rounded-circle mx-auto mb-4">
                                <i class="ri-team-line fs-1 text-muted"></i>
                            </div>
                            <h5 class="mb-3">No users found</h5>
                            <p class="text-muted mb-4">
                                @if(request()->hasAny(['search', 'role', 'status']))
                                    No users match your current filters. Try adjusting your search criteria.
                                @else
                                    There are no users in the system yet.
                                @endif
                            </p>
                            @if(!request()->hasAny(['search', 'role', 'status']))
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line me-1"></i> Create First User
                                </a>
                            @else
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                    <i class="ri-refresh-line me-1"></i> Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Impersonation Notice -->
    @if(session('impersonate_original_user'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="ri-user-shared-line me-2"></i>
                <strong>Impersonating User:</strong> You are currently viewing the system as another user.
                <form method="POST" action="{{ route('admin.users.stop-impersonating') }}" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-warning">
                        <i class="ri-logout-box-line me-1"></i> Stop Impersonating
                    </button>
                </form>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const statusSelect = document.getElementById('status');
        
        [roleSelect, statusSelect].forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    });
</script>
@endpush