<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        if (session()->has('user_id')) {
            $role = session('user_type');
            if ($role === null) {
                $user = User::find(session('user_id'));
                $role = $user ? ($user->user_type ?: 'graduate') : 'graduate';
                session()->put('user_type', $role);
            }

            return redirect()->route($role === 'professional' ? 'employee.dashboard' : 'dashboard');
        }

        return $next($request);
    }
}
