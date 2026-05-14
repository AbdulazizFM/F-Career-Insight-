<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use InteractsWithSessionAuth;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Invalid email or password.',
            ]);
        }

        if (Str::lower(trim((string) $user->account_status)) === 'suspended') {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'This account is suspended.',
            ]);
        }

        session()->forget(['user_id', 'user_name', 'user_email', 'user_type', 'admin_id', 'admin_name']);
        session()->put([
            'user_id' => $user->user_id,
            'user_name' => $user->full_name,
            'user_email' => $user->email,
            'user_type' => $user->user_type ?: 'graduate',
        ]);

        $admin = $this->adminForUserId($user->user_id);
        if ($admin) {
            session()->put([
                'admin_id' => $admin->admin_id,
                'admin_name' => $admin->department ?: ('Admin #' . $admin->admin_id),
            ]);
        }

        $request->session()->regenerate();

        if ($admin) {
            return redirect()->route('admin.dashboard')->with('success', 'Signed in as admin.');
        }

        $userDashboardRoute =  'dashboard';

        return redirect()->route($userDashboardRoute)->with('success', 'Signed in successfully.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'account_status' => 'Active',
            'user_type' => 'graduate',
            'job_title' => $data['job_title'] ?? null,
            'company' => $data['company'] ?? null,
            'years_experience' => $data['years_experience'] ?? null,
        ]);

        session()->put([
            'user_id' => $user->user_id,
            'user_name' => $user->full_name,
            'user_email' => $user->email,
            'user_type' => $user->user_type ?: 'graduate',
        ]);

        $request->session()->regenerate();

        $userDashboardRoute = ($user->user_type ?: 'graduate') === 'professional' ? 'employee.dashboard' : 'dashboard';

        return redirect()->route($userDashboardRoute)->with('success', 'Account created successfully.');
    }

    public function logout(Request $request)
    {
        session()->forget(['user_id', 'user_name', 'user_email', 'user_type', 'admin_id', 'admin_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
