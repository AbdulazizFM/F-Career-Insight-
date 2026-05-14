@extends('layouts.admin')

@section('content')
    <x-page-header
        title="Complaints"
        subtitle="Review, investigate, and resolve user complaints"
        :breadcrumbs="[
            ['label' => 'Admin Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Complaints']
        ]"
    />

    <div class="panel-card">
        <div class="panel-body p-0">
            <x-data-table id="adminComplaintsTable" class="table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Resolved by</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ optional($item->user)->full_name ?? '' }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ optional($item->admin)->department ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#showComplaintModal{{ $item->complaint_id }}" title="View details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if(strtolower((string) $item->status) !== 'resolved')
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#resolveComplaintModal{{ $item->complaint_id }}" title="Resolve">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <x-confirm-modal
                                            :id="'resolveComplaintModal' . $item->complaint_id"
                                            title="Resolve Complaint?"
                                            body="This will mark the complaint as resolved."
                                            action-label="Resolve"
                                            action-color="success"
                                            icon="bi-check-circle"
                                            icon-color="success"
                                            :form-action="route('admin.complaints.resolve', $item->complaint_id)"
                                            form-method="POST"
                                        />
                                    @endif
                                </div>

                                <div class="modal fade" id="showComplaintModal{{ $item->complaint_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content border-0 shadow-sm">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Complaint Details #{{ $item->complaint_id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control" value="{{ $item->title }}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Status</label>
                                                        <input type="text" class="form-control" value="{{ $item->status ?: 'Pending' }}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">User</label>
                                                        <input type="text" class="form-control" value="{{ optional($item->user)->full_name ?? '-' }}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Resolved By</label>
                                                        <input type="text" class="form-control" value="{{ optional($item->admin)->department ?? '-' }}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Created At</label>
                                                        <input type="text" class="form-control" value="{{ $item->created_at ? $item->created_at->format('Y-m-d H:i') : '-' }}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Resolved At</label>
                                                        <input type="text" class="form-control" value="{{ $item->resolved_at ? \Carbon\Carbon::parse($item->resolved_at)->format('Y-m-d H:i') : '-' }}" readonly>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control" rows="6" readonly>{{ $item->description }}</textarea>
                                                    </div>

                                                    @if($item->evaluation)
                                                        <div class="col-12"><hr class="my-1"></div>
                                                        <div class="col-12">
                                                            <h6 class="mb-2">Linked Evaluation</h6>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Evaluation ID</label>
                                                            <input type="text" class="form-control" value="{{ $item->evaluation->evaluation_id }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Evaluation Author</label>
                                                            <input type="text" class="form-control" value="{{ optional($item->evaluation->user)->full_name ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Role</label>
                                                            <input type="text" class="form-control" value="{{ optional($item->evaluation->role)->role_name ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Sub Major</label>
                                                            <input type="text" class="form-control" value="{{ optional(optional($item->evaluation->role)->subMajor)->sub_major_name ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">Evaluation Experience</label>
                                                            <textarea class="form-control" rows="5" readonly>{{ trim(strip_tags((string) $item->evaluation->experience)) }}</textarea>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                @if($item->evaluation && $item->evaluation->user && strtolower((string) $item->evaluation->user->account_status) !== 'suspended')
                                                    <form method="POST" action="{{ route('admin.users.suspend', $item->evaluation->user->user_id) }}" class="me-auto">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="bi bi-slash-circle me-1"></i>Block Evaluation Author
                                                        </button>
                                                    </form>
                                                @endif
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><x-empty-state icon="bi-exclamation-circle" title="No complaints found" message="No complaint records are available yet." /></td></tr>
                    @endforelse
                </tbody>
            </x-data-table>
        </div>
    </div>
@endsection

@push('scripts')
    @if(isset($complaint))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modalElement = document.getElementById('showComplaintModal{{ $complaint->complaint_id }}');
                if (!modalElement || typeof bootstrap === 'undefined') {
                    return;
                }
                var modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
                modalInstance.show();
            });
        </script>
    @endif
@endpush
