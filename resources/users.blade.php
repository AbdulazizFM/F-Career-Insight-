@extends('layouts.admin')

@section('content')
    @if(isset($user))
        <div class="panel-card mb-4">
            <div class="panel-head"><h5 class="mb-0">User details</h5></div>
            <div class="panel-body">
                <div class="row g-3">
                    <div class="col-md-4"><strong>Name:</strong> {{ $user->full_name }}</div>
                    <div class="col-md-4"><strong>Email:</strong> {{ $user->email }}</div>
                    <div class="col-md-4"><strong>Status:</strong> {{ $user->account_status }}</div>
                </div>
            </div>
        </div>
    @endif

    <x-page-header
        title="Users"
        subtitle="Manage account status and lifecycle"
        :breadcrumbs="[
            ['label' => 'Admin Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Users']
        ]"
    />

    <div class="panel-card">
        <div class="panel-body p-0">
            <x-data-table id="adminUsersTable" class="table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Evaluations</th>
                        <th>Purchases</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $item)
                        <tr>
                            <td>{{ $item->full_name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->account_status }}</td>
                            <td>{{ $item->evaluations_count }}</td>
                            <td>{{ $item->job_purchases_count }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#showUserModal{{ $item->user_id }}" title="View user">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="modal fade" id="showUserModal{{ $item->user_id }}" tabindex="-1" aria-labelledby="showUserModalLabel{{ $item->user_id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                                            @php
                                                $statusIsSuspended = strtolower((string) $item->account_status) === 'suspended';
                                            @endphp
                                            <div class="modal-content border-0 shadow-sm user-details-modal">
                                                <div class="modal-header user-details-header">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="user-details-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($item->full_name, 0, 1)) }}</div>
                                                        <div>
                                                            <h5 class="modal-title mb-1" id="showUserModalLabel{{ $item->user_id }}">{{ $item->full_name }}</h5>
                                                            <div class="small text-muted">{{ $item->email }}</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge rounded-pill {{ $statusIsSuspended ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' }}">
                                                        {{ $item->account_status }}
                                                    </span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body user-details-body">
                                                    <div class="row g-3 mb-4">
                                                        <div class="col-md-3 col-6">
                                                            <div class="user-meta-tile">
                                                                <div class="small text-muted">User ID</div>
                                                                <div class="fw-semibold">#{{ $item->user_id }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6">
                                                            <div class="user-meta-tile">
                                                                <div class="small text-muted">Joined</div>
                                                                <div class="fw-semibold">{{ $item->created_at ? $item->created_at->format('Y-m-d') : '-' }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6">
                                                            <div class="user-meta-tile">
                                                                <div class="small text-muted">Messages Sent</div>
                                                                <div class="fw-semibold">{{ $item->messages_count }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6">
                                                            <div class="user-meta-tile">
                                                                <div class="small text-muted">Complaints</div>
                                                                <div class="fw-semibold">{{ $item->complaints_count }}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-4">
                                                        <div class="col-md-3">
                                                            <div class="user-stat-card h-100">
                                                                <div class="small text-muted">Evaluations</div>
                                                                <div class="h5 mb-0">{{ $item->evaluations_count }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="user-stat-card h-100">
                                                                <div class="small text-muted">Purchases</div>
                                                                <div class="h5 mb-0">{{ $item->job_purchases_count }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="user-stat-card h-100">
                                                                <div class="small text-muted">Active Plan</div>
                                                                <div class="h6 mb-0">{{ optional($item->subscription)->plan_type ?? 'No Plan' }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="user-stat-card h-100">
                                                                <div class="small text-muted">Plan Status</div>
                                                                <div class="h6 mb-0">{{ optional($item->subscription)->status ?? 'N/A' }}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="user-section-card mb-4">
                                                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2"><i class="bi bi-journal-check text-primary"></i>Subscription</h6>
                                                        @if($item->subscription)
                                                            <div class="row g-3">
                                                                <div class="col-md-2"><span class="text-muted small d-block">Plan</span><strong>{{ $item->subscription->plan_type }}</strong></div>
                                                                <div class="col-md-2"><span class="text-muted small d-block">Status</span><strong>{{ $item->subscription->status }}</strong></div>
                                                                <div class="col-md-2"><span class="text-muted small d-block">Price</span><strong>{{ $item->subscription->price }} SAR</strong></div>
                                                                <div class="col-md-3"><span class="text-muted small d-block">Start</span><strong>{{ $item->subscription->start_date }}</strong></div>
                                                                <div class="col-md-3"><span class="text-muted small d-block">End</span><strong>{{ $item->subscription->end_date }}</strong></div>
                                                            </div>
                                                        @else
                                                            <div class="text-muted">No subscription record.</div>
                                                        @endif
                                                    </div>

                                                    <div class="user-section-card mb-4">
                                                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2"><i class="bi bi-star text-primary"></i>Evaluations</h6>
                                                        @if($item->evaluations->count())
                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-striped align-middle user-section-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Job Title</th>
                                                                            <th>Major</th>
                                                                            <th>Rating</th>
                                                                            <th>Created</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($item->evaluations as $evaluation)
                                                                            <tr>
                                                                                <td>{{ optional($evaluation->subMajor)->sub_major_name ?? '-' }}</td>
                                                                                <td>{{ optional(optional($evaluation->subMajor)->major)->major_name ?? '-' }}</td>
                                                                                <td>{{ $evaluation->rating }}/5</td>
                                                                                <td>{{ $evaluation->created_at ? $evaluation->created_at->format('Y-m-d') : '-' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <div class="text-muted">No evaluations submitted.</div>
                                                        @endif
                                                    </div>

                                                    <div class="user-section-card mb-4">
                                                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2"><i class="bi bi-bag-check text-primary"></i>Purchases</h6>
                                                        @if($item->jobPurchases->count())
                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-striped align-middle user-section-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Job Title</th>
                                                                            <th>Major</th>
                                                                            <th>Amount</th>
                                                                            <th>Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($item->jobPurchases as $purchase)
                                                                            <tr>
                                                                                <td>{{ optional($purchase->subMajor)->sub_major_name ?? '-' }}</td>
                                                                                <td>{{ optional(optional($purchase->subMajor)->major)->major_name ?? '-' }}</td>
                                                                                <td>{{ $purchase->price }} SAR</td>
                                                                                <td>{{ $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d H:i') : '-' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <div class="text-muted">No purchases found.</div>
                                                        @endif
                                                    </div>

                                                    <div class="user-section-card mb-0">
                                                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2"><i class="bi bi-exclamation-circle text-primary"></i>Complaints</h6>
                                                        @if($item->complaints->count())
                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-striped align-middle user-section-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Title</th>
                                                                            <th>Status</th>
                                                                            <th>Resolved At</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($item->complaints as $complaint)
                                                                            <tr>
                                                                                <td>{{ $complaint->title }}</td>
                                                                                <td>{{ $complaint->status }}</td>
                                                                                <td>{{ $complaint->resolved_at ?? '-' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <div class="text-muted">No complaints submitted.</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $item->user_id }}" title="Edit user">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <div class="modal fade" id="editUserModal{{ $item->user_id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $item->user_id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-sm">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editUserModalLabel{{ $item->user_id }}">Edit User Account</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.users.update', $item->user_id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Full Name</label>
                                                            <input type="text" name="full_name" class="form-control" value="{{ $item->full_name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control" value="{{ $item->email }}" required>
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label">Account Status</label>
                                                            <select name="account_status" class="form-select" required>
                                                                <option value="Active" {{ strtolower((string) $item->account_status) === 'active' ? 'selected' : '' }}>Active</option>
                                                                <option value="Suspended" {{ strtolower((string) $item->account_status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @if(strtolower((string) $item->account_status) === 'suspended')
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#activateUserModal{{ $item->user_id }}" title="Activate">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <x-confirm-modal
                                            :id="'activateUserModal' . $item->user_id"
                                            title="Activate User?"
                                            body="This will restore account access for this user."
                                            action-label="Activate"
                                            action-color="success"
                                            icon="bi-check-circle"
                                            icon-color="success"
                                            :form-action="route('admin.users.activate', $item->user_id)"
                                            form-method="POST"
                                        />
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#suspendUserModal{{ $item->user_id }}" title="Suspend">
                                            <i class="bi bi-ban"></i>
                                        </button>
                                        <x-confirm-modal
                                            :id="'suspendUserModal' . $item->user_id"
                                            title="Suspend User?"
                                            body="The user will lose access until reactivated by an admin."
                                            action-label="Suspend"
                                            action-color="warning"
                                            icon="bi-ban"
                                            icon-color="warning"
                                            :form-action="route('admin.users.suspend', $item->user_id)"
                                            form-method="POST"
                                        />
                                    @endif

                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $item->user_id }}" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <x-confirm-modal
                                        :id="'deleteUserModal' . $item->user_id"
                                        title="Delete User?"
                                        body="This cannot be undone. All related account data will be removed."
                                        action-label="Delete"
                                        action-color="danger"
                                        icon="bi-trash"
                                        icon-color="danger"
                                        :form-action="route('admin.users.destroy', $item->user_id)"
                                        form-method="POST"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-empty-state icon="bi-people" title="No users found" message="No user records are available yet." /></td></tr>
                    @endforelse
                </tbody>
            </x-data-table>
        </div>
    </div>
@endsection
