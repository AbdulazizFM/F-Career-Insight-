<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        $adminId = session('admin_id');

        if ($adminId && Admin::where('admin_id', $adminId)->exists()) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
