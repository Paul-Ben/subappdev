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
										<li class="breadcrumb-item active">Subscriptions</li>
									</ol>
								</div>
								<h4 class="page-title">Active Subscriptions</h4>
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
									<h4 class="card-title">Subscribed Users</h4>
									<p class="text-muted mb-0">View all users with active subscriptions and their plan details</p>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-centered table-nowrap mb-0">
											<thead class="table-dark">
												<tr>
													<th>User Details</th>
													<th>Subscription Plan</th>
													<th>Amount Paid</th>
													<th>Duration</th>
													<th>Status</th>
													<th class="text-center">Actions</th>
												</tr>
											</thead>
											<tbody>
												@forelse($subscriptions as $subscription)
													<tr>
														<td>
															<div class="d-flex align-items-center">
																<div class="flex-shrink-0">
																	<div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center">
																		<i class="ri-user-line text-white fs-16"></i>
																	</div>
																</div>
																<div class="flex-grow-1 ms-3">
																	<h5 class="mb-1 fw-semibold">{{ $subscription->user->name }}</h5>
																	<p class="text-muted mb-0 fs-13">{{ $subscription->user->email }}</p>
																</div>
															</div>
														</td>
														<td>
															<div class="d-flex flex-column">
																<span class="fw-semibold text-primary">{{ $subscription->subscriptionPlan->name }}</span>
																<small class="text-muted">{{ Str::limit($subscription->subscriptionPlan->description, 40) }}</small>
															</div>
														</td>
														<td>
															<div class="d-flex flex-column">
																<span class="fw-semibold">₦{{ number_format($subscription->amount_paid, 2) }}</span>
																<small class="text-muted">{{ $subscription->currency }}</small>
															</div>
														</td>
														<td>
															<div class="d-flex flex-column">
																<span class="fw-semibold">{{ $subscription->starts_at->format('M d, Y') }}</span>
																@if($subscription->ends_at)
																	<small class="text-muted">to {{ $subscription->ends_at->format('M d, Y') }}</small>
																@else
																	<small class="text-muted">Lifetime</small>
																@endif
															</div>
														</td>
														<td>
															@if($subscription->status === 'active')
																@if($subscription->ends_at && $subscription->ends_at->isPast())
																	<span class="badge bg-warning">Expired</span>
																@else
																	<span class="badge bg-success">Active</span>
																@endif
															@elseif($subscription->status === 'cancelled')
																<span class="badge bg-danger">Cancelled</span>
															@else
																<span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
															@endif
														</td>
														<td class="text-center">
															<div class="dropdown">
																<a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																	<i class="ri-more-2-fill"></i>
																</a>
																<div class="dropdown-menu dropdown-menu-end">
																	<a href="{{ route('admin.users.show', $subscription->user->id) }}" class="dropdown-item">
																		<i class="ri-eye-line me-1"></i> View User
																	</a>
																	<a href="{{ route('transactions.index') }}?user_id={{ $subscription->user->id }}" class="dropdown-item">
																		<i class="ri-file-list-line me-1"></i> View Payments
																	</a>
																	<div class="dropdown-divider"></div>
																	@if($subscription->status === 'active')
																		<a href="#" class="dropdown-item text-danger" onclick="confirmCancelSubscription({{ $subscription->id }})">
																			<i class="ri-close-circle-line me-1"></i> Cancel Subscription
																		</a>
																	@endif
																</div>
															</div>
														</td>
													</tr>
												@empty
													<tr>
														<td colspan="6" class="text-center py-4">
															<div class="d-flex flex-column align-items-center">
																<i class="ri-inbox-line text-muted" style="font-size: 3rem;"></i>
																<h5 class="text-muted mt-2">No Active Subscriptions</h5>
																<p class="text-muted">There are currently no users with active subscriptions.</p>
															</div>
														</td>
													</tr>
												@endforelse
											</tbody>
										</table>
									</div>

									<!-- Pagination -->
									@if($subscriptions->hasPages())
										<div class="d-flex justify-content-between align-items-center mt-3">
											<div class="text-muted">
												Showing {{ $subscriptions->firstItem() }} to {{ $subscriptions->lastItem() }} of {{ $subscriptions->total() }} results
											</div>
											<div>
												{{ $subscriptions->links() }}
											</div>
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>

				</div>
				<!-- container -->

				<script>
				function confirmCancelSubscription(subscriptionId) {
					if (confirm('Are you sure you want to cancel this subscription? This action cannot be undone.')) {
						// Add your cancel subscription logic here
						console.log('Cancel subscription:', subscriptionId);
						// You can implement an AJAX call or form submission here
					}
				}
				</script>

@endsection