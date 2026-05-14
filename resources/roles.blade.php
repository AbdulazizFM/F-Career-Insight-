<div class="col-12">
    <div class="panel-card">
        <div class="panel-head"><h5 class="mb-0">Roles</h5></div>
        <div class="panel-body p-0">
            <x-data-table id="adminRolesTable" class="table-striped">
                <thead class="table-light"><tr><th>Role</th><th>Sub Major</th><th>Evaluations</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $role->role_name }}</div>
                            <small class="text-muted d-block">Salary: {{ $role->salary_range ?: '-' }}</small>
                        </td>
                        <td>{{ optional($role->subMajor)->sub_major_name ?? '-' }}</td>
                        <td>{{ $role->evaluations_count }}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->role_id }}">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteRoleModal{{ $role->role_id }}">Delete</button>
                            <div class="modal fade" id="editRoleModal{{ $role->role_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.roles.update', $role->role_id) }}">
                                            @csrf
                                            <div class="modal-header"><h5 class="modal-title">Edit Role</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                            <div class="modal-body">
                                                <div class="mb-3"><label class="form-label">Sub Major</label><select name="sub_major_id" class="form-select" required>@foreach($subMajors as $s)<option value="{{ $s->sub_major_id }}" {{ (int)$s->sub_major_id === (int)$role->sub_major_id ? 'selected' : '' }}>{{ $s->sub_major_name }}</option>@endforeach</select></div>
                                                <div class="mb-3"><label class="form-label">Role Name</label><input type="text" name="role_name" class="form-control" value="{{ $role->role_name }}" required></div>
                                                <div class="mb-3"><label class="form-label">Salary Range</label><input type="text" name="salary_range" class="form-control" value="{{ $role->salary_range }}"></div>
                                                <div><label class="form-label">Challenges</label><textarea name="challenges" class="form-control" rows="3">{{ $role->challenges }}</textarea></div>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <x-confirm-modal :id="'deleteRoleModal'.$role->role_id" title="Delete Role?" body="This role can be deleted only if it has no evaluations." action-label="Delete" action-color="danger" icon="bi-trash" icon-color="danger" :form-action="route('admin.roles.destroy', $role->role_id)" form-method="POST" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><x-empty-state icon="bi-briefcase" title="No roles" message="No role records are available yet." /></td></tr>
                @endforelse
                </tbody>
            </x-data-table>
        </div>
    </div>
</div>

<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form method="POST" action="{{ route('admin.roles.store') }}">@csrf<div class="modal-header"><h5 class="modal-title">Add Role</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="mb-3"><label class="form-label">Sub Major</label><select name="sub_major_id" class="form-select" required><option value="">Select sub major</option>@foreach($subMajors as $s)<option value="{{ $s->sub_major_id }}">{{ $s->sub_major_name }}</option>@endforeach</select></div><div class="mb-3"><label class="form-label">Role Name</label><input type="text" name="role_name" class="form-control" required></div><div class="mb-3"><label class="form-label">Salary Range</label><input type="text" name="salary_range" class="form-control" placeholder="e.g. 8,000 - 12,000"></div><div><label class="form-label">Challenges</label><textarea name="challenges" class="form-control" rows="3"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Add</button></div></form></div></div></div>
