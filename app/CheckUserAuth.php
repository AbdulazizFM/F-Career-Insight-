<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserAuth
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (! session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please sign in to continue.');
        }

        return $next($request);
    }
}
