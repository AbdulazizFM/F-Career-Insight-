<div class="col-12">
    <div class="panel-card">
        <div class="panel-head"><h5 class="mb-0">Sub Majors</h5></div>
        <div class="panel-body p-0">
            <x-data-table id="adminSubMajorsTable" class="table-striped">
                <thead class="table-light"><tr><th>Sub Major</th><th>Major</th><th>Roles</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse($subMajors as $subMajor)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $subMajor->sub_major_name }}</div>
                        </td>
                        <td>{{ optional($subMajor->major)->major_name ?? '-' }}</td>
                        <td>{{ $subMajor->roles_count }}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editSubMajorModal{{ $subMajor->sub_major_id }}">Edit</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSubMajorModal{{ $subMajor->sub_major_id }}">Delete</button>
                            <div class="modal fade" id="editSubMajorModal{{ $subMajor->sub_major_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.submajors.update', $subMajor->sub_major_id) }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header"><h5 class="modal-title">Edit Sub Major</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                            <div class="modal-body">
                                                <div class="mb-3"><label class="form-label">Major</label><select name="major_id" class="form-select" required>@foreach($majors as $m)<option value="{{ $m->major_id }}" {{ (int)$m->major_id === (int)$subMajor->major_id ? 'selected' : '' }}>{{ $m->major_name }}</option>@endforeach</select></div>
                                                <div class="mb-3"><label class="form-label">Sub Major Name</label><input type="text" name="sub_major_name" class="form-control" value="{{ $subMajor->sub_major_name }}" required></div>
                                                <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ $subMajor->description }}</textarea></div>
                                                <div class="mb-3"><label class="form-label">Image URL (optional)</label><input type="text" name="image_path" class="form-control" value="{{ $subMajor->image_path }}"></div>
                                                <div><label class="form-label">Upload Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <x-confirm-modal :id="'deleteSubMajorModal'.$subMajor->sub_major_id" title="Delete Sub Major?" body="This sub major can be deleted only if it has no evaluations." action-label="Delete" action-color="danger" icon="bi-trash" icon-color="danger" :form-action="route('admin.submajors.destroy', $subMajor->sub_major_id)" form-method="POST" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><x-empty-state icon="bi-diagram-3" title="No sub majors" message="No sub major records are available yet." /></td></tr>
                @endforelse
                </tbody>
            </x-data-table>

        </div>
    </div>
</div>

<div class="modal fade" id="addSubMajorModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form method="POST" action="{{ route('admin.submajors.store') }}" enctype="multipart/form-data">@csrf<div class="modal-header"><h5 class="modal-title">Add Sub Major</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="mb-3"><label class="form-label">Major</label><select name="major_id" class="form-select" required><option value="">Select major</option>@foreach($majors as $major)<option value="{{ $major->major_id }}">{{ $major->major_name }}</option>@endforeach</select></div><div class="mb-3"><label class="form-label">Sub Major Name</label><input type="text" name="sub_major_name" class="form-control" required></div><div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div><div class="mb-3"><label class="form-label">Image URL (optional)</label><input type="text" name="image_path" class="form-control"></div><div><label class="form-label">Upload Image</label><input type="file" name="image" class="form-control" accept="image/*"></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Add</button></div></form></div></div></div>
