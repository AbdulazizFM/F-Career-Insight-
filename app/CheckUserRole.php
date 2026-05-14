<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (! session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please sign in to continue.');
        }

        return $next($request);
    }
}
