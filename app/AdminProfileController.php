<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Http\Requests\AdminProfileUpdateRequest;
use App\Models\Admin;

class AdminProfileController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $admin = $this->currentAdmin();
        $admin = Admin::with('user')->findOrFail($admin->admin_id);

        return view('admin.profile', compact('admin'));
    }

    public function update(AdminProfileUpdateRequest $request)
    {
        $admin = $this->currentAdmin();
        $admin = Admin::findOrFail($admin->admin_id);
        $data = $request->validated();

        $admin->department = $data['department'] ?? null;
        $admin->access_level = $data['access_level'] ?? null;
        $admin->security_clearance_level = $data['security_clearance_level'] ?? null;
        $admin->two_factor_enabled = (bool) ($data['two_factor_enabled'] ?? false);
        $admin->save();

        session()->put([
            'admin_name' => $admin->department ?: ('Admin #' . $admin->admin_id),
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Admin profile updated successfully.');
    }
}
