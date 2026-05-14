@extends('layouts.admin')

@section('page-title', 'Report Flags')
@section('page-subtitle', 'Review and resolve reported content')

@section('content')
    <x-page-header
        title="Report Flags"
        subtitle="Moderate user reports and update review status"
        :breadcrumbs="[
            ['label' => 'Admin Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Report Flags']
        ]"
    />

    <div class="panel-card">
        <div class="panel-body p-0">
            <x-data-table id="adminReportFlagsTable" class="table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Reporter</th>
                        <th>Target</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Reviewed By</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportFlags as $flag)
                        <tr>
                            <td>#{{ $flag->report_id }}</td>
                            <td>{{ optional($flag->reporter)->full_name ?? 'Unknown' }}</td>
                            <td>{{ $flag->target_type }} #{{ $flag->target_id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $flag->reason }}</div>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($flag->description, 90) }}</small>
                            </td>
                            <td>{{ $flag->status ?: 'Pending' }}</td>
                            <td>{{ optional($flag->reviewer)->department ?? '-' }}</td>
                            <td>{{ $flag->created_at ? $flag->created_at->format('Y-m-d H:i') : '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reviewFlagModal{{ $flag->report_id }}" title="Review">
                                        <i class="bi bi-check2-square"></i>
                                    </button>
                                </div>

                                <div class="modal fade" id="reviewFlagModal{{ $flag->report_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-sm">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Review Report Flag #{{ $flag->report_id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.report-flags.review', $flag->report_id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Reporter</label>
                                                        <input type="text" class="form-control" value="{{ optional($flag->reporter)->full_name ?? 'Unknown' }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Target</label>
                                                        <input type="text" class="form-control" value="{{ $flag->target_type }} #{{ $flag->target_id }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason</label>
                                                        <input type="text" class="form-control" value="{{ $flag->reason }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control" rows="4" readonly>{{ $flag->description }}</textarea>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label">Review Status</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="Reviewed" {{ strtolower((string) $flag->status) === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                                            <option value="Dismissed" {{ strtolower((string) $flag->status) === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Review</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><x-empty-state icon="bi-flag" title="No report flags" message="No report flags are available at this time." /></td></tr>
                    @endforelse
                </tbody>
            </x-data-table>
        </div>
    </div>
@endsection
