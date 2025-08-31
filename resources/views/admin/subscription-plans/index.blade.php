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
										<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
										<li class="breadcrumb-item active">Subscription Plans</li>
									</ol>
								</div>
								<div class="d-flex justify-content-end mt-2">
									<a href="{{route('subscription-plans.create')}}" class="btn btn-primary">
										<i class="ri-add-line me-1"></i> Add New Plan
									</a>
								</div>
								<h4 class="page-title">Subscription Plans</h4>
							</div>
						</div>
					</div>
					<!-- end page title -->

					@if(session('success'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							{{ session('success') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					@if(session('error'))
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							{{ session('error') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title">Subscription Plans Management</h4>
									<p class="text-muted mb-0">Manage your subscription plans, pricing, and features</p>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-centered table-nowrap mb-0">
											<thead class="table-dark">
												<tr>
													<th>Plan Details</th>
													<th>Pricing</th>
													<th>Limits</th>
													<th>Features</th>
													<th>Status</th>
													<th class="text-center">Actions</th>
												</tr>
											</thead>
											<tbody>
												@forelse($plans as $plan)
													<tr>
														<td>
															<div class="d-flex align-items-center">
																<div class="flex-shrink-0">
																	<div class="avatar-sm bg-primary rounded d-flex align-items-center justify-content-center">
																		<i class="ri-price-tag-3-line text-white fs-16"></i>
																	</div>
																</div>
																<div class="flex-grow-1 ms-3">
																	<h5 class="mb-1 fw-semibold">{{ $plan->name }}</h5>
																	@if($plan->description)
																		<p class="text-muted mb-0 fs-13">{{ Str::limit($plan->description, 60) }}</p>
																	@endif
																</div>
															</div>
														</td>
														<td>
															<div class="d-flex flex-column">
																<span class="fw-bold fs-15 text-dark">{{ $plan->formatted_price }}</span>
																<span class="badge bg-info-subtle text-info mt-1">{{ ucfirst($plan->billing_cycle) }}</span>
															</div>
														</td>
														<td>
															<div class="d-flex flex-column gap-1">
																<small class="text-muted"><i class="ri-group-line me-1"></i>{{ $plan->max_participants }} participants</small>
																<small class="text-muted"><i class="ri-time-line me-1"></i>{{ $plan->meeting_duration_display }}</small>
																<small class="text-muted"><i class="ri-database-line me-1"></i>{{ $plan->storage_display }}</small>
															</div>
														</td>
														<td>
															<div class="d-flex flex-wrap gap-1">
																@if($plan->has_recording)
																	<span class="badge bg-success-subtle text-success"><i class="ri-record-circle-line me-1"></i>Recording</span>
																@endif
																@if($plan->has_breakout_rooms)
																	<span class="badge bg-primary-subtle text-primary"><i class="ri-team-line me-1"></i>Breakout</span>
																@endif
																@if($plan->has_admin_tools)
																	<span class="badge bg-warning-subtle text-warning"><i class="ri-settings-3-line me-1"></i>Admin</span>
																@endif
															</div>
														</td>
														<td>
															@if($plan->is_active)
																<span class="badge bg-success fs-12"><i class="ri-check-line me-1"></i>Active</span>
															@else
																<span class="badge bg-danger fs-12"><i class="ri-close-line me-1"></i>Inactive</span>
															@endif
														</td>
														<td class="text-center">
															<div class="dropdown">
																<a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																	<i class="ri-more-2-fill"></i>
																</a>
																<div class="dropdown-menu dropdown-menu-end">
																	<a href="{{route('subscription-plans.show', $plan)}}" class="dropdown-item"><i class="ri-eye-line me-1"></i>View Details</a>
																	<a href="{{route('subscription-plans.edit', $plan)}}" class="dropdown-item"><i class="ri-edit-line me-1"></i>Edit Plan</a>
																	<div class="dropdown-divider"></div>
																	<a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this plan?')) { document.getElementById('delete-form-{{$plan->id}}').submit(); }"><i class="ri-delete-bin-line me-1"></i>Delete Plan</a>
																</div>
															</div>
															<form id="delete-form-{{$plan->id}}" action="{{route('subscription-plans.destroy', $plan)}}" method="POST" style="display: none;">
																@csrf
																@method('DELETE')
															</form>
														</td>
													</tr>
												@empty
													<tr>
														<td colspan="6" class="text-center py-5">
															<div class="text-muted">
																<div class="avatar-lg bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
																	<i class="ri-price-tag-3-line fs-24 text-muted"></i>
																</div>
																<h5 class="mt-2 mb-1">No subscription plans found</h5>
																<p class="text-muted mb-3">Create your first subscription plan to get started with your service offerings.</p>
																<a href="{{route('subscription-plans.create')}}" class="btn btn-primary">
																	<i class="ri-add-line me-1"></i> Create First Plan
																</a>
															</div>
														</td>
													</tr>
												@endforelse
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
				<!-- container -->
@endsection