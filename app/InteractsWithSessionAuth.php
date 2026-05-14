<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Admin;
use App\Models\User;

trait InteractsWithSessionAuth
{
    protected function currentUser(): ?User
    {
        $userId = session('user_id');

        return $userId ? User::find($userId) : null;
    }

    protected function currentAdmin(): ?Admin
    {
        $adminId = session('admin_id');

        return $adminId ? Admin::find($adminId) : null;
    }

    protected function adminForUserId($userId): ?Admin
    {
        if (! $userId) {
            return null;
        }

        return Admin::where('user_id', $userId)->first();
    }

    protected function requireUser(): ?User
    {
        return $this->currentUser();
    }

    protected function requireAdmin(): ?Admin
    {
        return $this->currentAdmin();
    }
}
