<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class CheckAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $userId = session('user_id');

        if (! $userId) {
            return redirect()->route('login')->with('error', 'Please sign in to continue.');
        }

        $admin = Admin::where('user_id', $userId)->first();

        if (! $admin) {
            abort(403, 'Admin access required.');
        }

        if (! session()->has('admin_id')) {
            session()->put([
                'admin_id' => $admin->admin_id,
                'admin_name' => $admin->department ?: ('Admin #' . $admin->admin_id),
            ]);
        }

        return $next($request);
    }
}
