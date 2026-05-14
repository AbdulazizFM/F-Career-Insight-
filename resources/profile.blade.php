@extends('layouts.admin')

@section('page-title', 'Admin Profile')
@section('page-subtitle', 'Manage administrator account settings')

@section('content')
    <x-page-header
        title="Admin Profile"
        subtitle="Update your department, access level, and security settings"
        :breadcrumbs="[
            ['label' => 'Admin Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Profile']
        ]"
    />

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="panel-card">
                <div class="panel-head"><h5 class="mb-0">Account Settings</h5></div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Linked User</label>
                                <input type="text" class="form-control" value="{{ optional($admin->user)->full_name ?? 'N/A' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" value="{{ optional($admin->user)->email ?? 'N/A' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" value="{{ old('department', $admin->department) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Access Level</label>
                                <input type="text" name="access_level" class="form-control" value="{{ old('access_level', $admin->access_level) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Security Clearance</label>
                                <input type="number" name="security_clearance_level" min="0" max="10" class="form-control" value="{{ old('security_clearance_level', $admin->security_clearance_level) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block">Two Factor Authentication</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="two_factor_enabled" value="1" id="twoFactorSwitch" {{ old('two_factor_enabled', $admin->two_factor_enabled) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="twoFactorSwitch">Enable 2FA</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Login IP</label>
                                <input type="text" class="form-control" value="{{ $admin->last_login_ip ?: '-' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Updated At</label>
                                <input type="text" class="form-control" value="{{ $admin->updated_at ?: '-' }}" readonly>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
