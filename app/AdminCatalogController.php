<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Http\Requests\StoreMajorRequest;
use App\Http\Requests\StoreSubMajorRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Http\Requests\UpdateSubMajorRequest;
use App\Models\Major;
use App\Models\Role;
use App\Models\SubMajor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;

class AdminCatalogController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        return redirect()->route('admin.catalog.majors');
    }

    public function majors()
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('viewAny', Major::class);

        $majors = Major::withCount('subMajors')
            ->select(['major_id', 'major_name', 'description', 'image_path'])
            ->orderBy('major_name')
            ->get();

        return view('admin.catalog.majors-page', compact('majors'));
    }

    public function subMajors()
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('viewAny', SubMajor::class);

        $majors = Major::orderBy('major_name')->get(['major_id', 'major_name']);
        $subMajors = SubMajor::with('major')
            ->withCount('roles')
            ->withCount('evaluations')
            ->select(['sub_major_id', 'major_id', 'sub_major_name', 'description', 'image_path'])
            ->orderBy('sub_major_name')
            ->get();

        return view('admin.catalog.submajors-page', compact('majors', 'subMajors'));
    }

    public function roles()
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('viewAny', SubMajor::class);

        $subMajors = SubMajor::orderBy('sub_major_name')->get(['sub_major_id', 'sub_major_name']);
        $roles = Role::with('subMajor.major')
            ->withCount('evaluations')
            ->orderBy('role_name')
            ->get();

        return view('admin.catalog.roles-page', compact('subMajors', 'roles'));
    }

    public function storeMajor(StoreMajorRequest $request)
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('create', Major::class);

        $data = $request->validated();
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeUploadedImage($request->file('image'));
        }

        Major::create($data);

        return redirect()->route('admin.catalog.majors')->with('success', 'Major added successfully.');
    }

    public function updateMajor(UpdateMajorRequest $request, $id)
    {
        $actor = $this->requireUser();
        $major = Major::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $major);

        $data = $request->validated();
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeUploadedImage($request->file('image'));
        }

        $major->update($data);

        return redirect()->route('admin.catalog.majors')->with('success', 'Major updated successfully.');
    }

    public function destroyMajor($id)
    {
        $actor = $this->requireUser();
        $major = Major::withCount('subMajors')->findOrFail($id);
        Gate::forUser($actor)->authorize('delete', $major);

        if ($major->sub_majors_count > 0) {
            return redirect()->route('admin.catalog.majors')->with('error', 'Cannot delete a major that has sub majors.');
        }

        $major->delete();

        return redirect()->route('admin.catalog.majors')->with('success', 'Major deleted successfully.');
    }

    public function storeSubMajor(StoreSubMajorRequest $request)
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('create', SubMajor::class);

        $data = $request->validated();
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeUploadedImage($request->file('image'));
        }

        SubMajor::create($data);

        return redirect()->route('admin.catalog.submajors')->with('success', 'Sub major added successfully.');
    }

    public function updateSubMajor(UpdateSubMajorRequest $request, $id)
    {
        $actor = $this->requireUser();
        $subMajor = SubMajor::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $subMajor);

        $data = $request->validated();
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeUploadedImage($request->file('image'));
        }

        $subMajor->update($data);

        return redirect()->route('admin.catalog.submajors')->with('success', 'Sub major updated successfully.');
    }

    public function destroySubMajor($id)
    {
        $actor = $this->requireUser();
        $subMajor = SubMajor::withCount('evaluations')->findOrFail($id);
        Gate::forUser($actor)->authorize('delete', $subMajor);

        if ($subMajor->evaluations_count > 0) {
            return redirect()->route('admin.catalog.submajors')->with('error', 'Cannot delete a sub major that has evaluations.');
        }

        $subMajor->delete();

        return redirect()->route('admin.catalog.submajors')->with('success', 'Sub major deleted successfully.');
    }

    public function storeRole()
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('create', SubMajor::class);

        $data = request()->validate([
            'sub_major_id' => ['required', 'exists:SUBMAJOR,sub_major_id'],
            'role_name' => ['required', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:255'],
            'challenges' => ['nullable', 'string'],
        ]);

        Role::create($data);

        return redirect()->route('admin.catalog.roles')->with('success', 'Role added successfully.');
    }

    public function updateRole($id)
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('update', SubMajor::class);

        $role = Role::findOrFail($id);
        $data = request()->validate([
            'sub_major_id' => ['required', 'exists:SUBMAJOR,sub_major_id'],
            'role_name' => ['required', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:255'],
            'challenges' => ['nullable', 'string'],
        ]);

        $role->update($data);

        return redirect()->route('admin.catalog.roles')->with('success', 'Role updated successfully.');
    }

    public function destroyRole($id)
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('delete', SubMajor::class);

        $role = Role::withCount('evaluations')->findOrFail($id);
        if ($role->evaluations_count > 0) {
            return redirect()->route('admin.catalog.roles')->with('error', 'Cannot delete a role that has evaluations.');
        }

        $role->delete();

        return redirect()->route('admin.catalog.roles')->with('success', 'Role deleted successfully.');
    }

    private function storeUploadedImage(UploadedFile $file): string
    {
        $dir = public_path('uploads');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $fileName = uniqid('major_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $fileName);

        return '/uploads/' . $fileName;
    }
}
