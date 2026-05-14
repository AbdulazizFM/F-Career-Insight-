@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --bs-primary: #1e3a8a;
            --primary-50: #f0f4ff;
            --primary-700: #1e3a8a;
            --primary-900: #0d1d4a;
            --ink: #0a0f2c;
            --ink-2: #242a45;
            --ink-3: #55597a;
            --ink-4: #9095b3;
            --line: #e8eaf3;
            --line-strong: #d5d9e8;
            --bg: #f7f8fc;
            --card: #ffffff;
        }

        body.auth-page {
            background: var(--bg);
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(30, 58, 138, 0.08), transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 100%, rgba(30, 58, 138, 0.05), transparent 60%),
                linear-gradient(180deg, #fafbff 0%, #f2f4fb 100%);
        }

        .auth-page-shell {
            min-height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.75rem 2.5rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            text-decoration: none;
        }

        .brand-mark {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-700), var(--primary-900));
            border-radius: 10px;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            object-fit: contain;
            background: #fff;
            border: 1px solid rgba(30, 58, 138, 0.12);
            padding: 3px;
        }

        .brand-name {
            font-size: 1.02rem;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: 0;
        }

        .header-link {
            color: var(--ink-3);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .header-link a {
            color: var(--ink);
            font-weight: 600;
            text-decoration: none;
            margin-left: 0.4rem;
        }

        .page-main {
            flex: 1;
            display: grid;
            place-items: center;
            padding: 1rem 1rem 2.5rem;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: var(--card);
            border-radius: 20px;
            padding: 2.3rem 2.3rem 2rem;
            border: 1px solid rgba(30, 58, 138, 0.08);
            box-shadow: 0 12px 28px rgba(10, 15, 44, 0.06);
        }

        .card-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.7rem 0.3rem 0.4rem;
            background: var(--primary-50);
            border: 1px solid rgba(30, 58, 138, 0.12);
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--primary-700);
            margin-bottom: 1.5rem;
        }

        .badge-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--primary-700);
        }

        .card-title {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.1;
            color: var(--ink);
            margin: 0 0 0.4rem;
        }

        .card-title em {
            font-style: normal;
            color: var(--primary-700);
        }

        .card-subtitle {
            color: var(--ink-3);
            font-size: 0.92rem;
            line-height: 1.5;
            margin: 0 0 1.8rem;
        }

        .field + .field {
            margin-top: 0.85rem;
        }

        .field-label-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 0.4rem;
        }

        .field-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--ink-2);
            margin: 0;
        }

        .field-input-wrap {
            position: relative;
            background: #fcfcff;
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .field-input-wrap:focus-within {
            border-color: var(--primary-700);
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        }

        .field-input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 0.85rem 1rem 0.85rem 2.7rem;
            font-size: 0.94rem;
            color: var(--ink);
            outline: none;
        }

        .field-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink-4);
            font-size: 1.05rem;
        }

        .field-suffix {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--ink-4);
            cursor: pointer;
            padding: 0.35rem;
        }

        .field-help-error {
            color: #dc2626;
            font-size: 0.78rem;
            margin-top: 0.3rem;
        }

        .btn-submit {
            width: 100%;
            margin-top: 1.4rem;
            background: linear-gradient(180deg, #2646a2 0%, var(--primary-700) 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            font-size: 0.94rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        @media (max-width: 640px) {
            .page-header {
                padding: 1.1rem 1rem;
            }

            .auth-card {
                padding: 1.6rem 1.2rem 1.4rem;
            }

            .card-title {
                font-size: 1.6rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="auth-page-shell">
        <header class="page-header">
            <a class="brand" href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Career Insights" class="brand-logo">
                <span class="brand-name">Career Insights</span>
            </a>
            <div class="header-link">
                <span>New here?</span>
                <a href="{{ route('register') }}">Create account</a>
            </div>
        </header>

        <main class="page-main">
            <div class="auth-card">
                <span class="card-badge">
                    <span class="badge-dot"></span>
                    Secure sign in
                </span>

                <h1 class="card-title">Welcome <em>back</em>.</h1>
                <p class="card-subtitle">Sign in to continue exploring career insights and managing your account.</p>

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="field">
                        <label class="field-label" for="email">Email address</label>
                        <div class="field-input-wrap">
                            <i class="bi bi-envelope field-icon"></i>
                            <input type="email" id="email" name="email" class="field-input" value="{{ old('email') }}" placeholder="you@company.com" required autocomplete="email">
                        </div>
                        @error('email')
                            <div class="field-help-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <div class="field-label-row">
                            <label class="field-label" for="password">Password</label>
                        </div>
                        <div class="field-input-wrap">
                            <i class="bi bi-lock field-icon"></i>
                            <input type="password" id="password" name="password" class="field-input" placeholder="Enter your password" required autocomplete="current-password">
                            <button type="button" class="field-suffix" data-toggle-password data-target="#password" aria-label="Show password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="field-help-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        Sign in to your account
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </main>
    </div>
@endsection
