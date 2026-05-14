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
            max-width: 760px;
            background: var(--card);
            border-radius: 20px;
            padding: 2.3rem 2.3rem 2rem;
            border: 1px solid rgba(30, 58, 138, 0.08);
            box-shadow: 0 12px 28px rgba(10, 15, 44, 0.06);
        }

        .password-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .double-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
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
            margin-bottom: 1.3rem;
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
            margin: 0 0 1.5rem;
        }

        .field + .field {
            margin-top: 0.75rem;
        }

        .double-row .field + .field,
        .password-row .field + .field {
            margin-top: 0;
        }

        .professional-fields {
            margin-top: 0.75rem;
        }

        .field-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--ink-2);
            margin-bottom: 0.4rem;
        }

        .required-mark {
            color: #dc2626;
            margin-left: 0.18rem;
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
            padding: 0.8rem 1rem 0.8rem 2.6rem;
            font-size: 0.92rem;
            color: var(--ink);
            outline: none;
        }

        .field-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink-4);
            font-size: 1.02rem;
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
            margin-top: 1.2rem;
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

        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 0.55rem;
            margin-top: 1rem;
        }

        .terms-row input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: var(--primary-700);
            margin-top: 2px;
        }

        .terms-row label {
            font-size: 0.82rem;
            color: var(--ink-3);
            line-height: 1.4;
        }

        .terms-row a {
            color: var(--primary-700);
            font-weight: 600;
            text-decoration: none;
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

            .password-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .double-row {
                grid-template-columns: 1fr;
                gap: 0;
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
                <span>Have an account?</span>
                <a href="{{ route('login') }}">Sign in</a>
            </div>
        </header>

        <main class="page-main">
            <div class="auth-card">
                <span class="card-badge">
                    <span class="badge-dot"></span>
                    Get started
                </span>

                <h1 class="card-title">Create your <em>account</em>.</h1>
                <p class="card-subtitle">Takes less than a minute. No credit card required.</p>

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    <div class="double-row">
                        <div class="field">
                            <label class="field-label" for="full_name">Full name <span class="required-mark">*</span></label>
                            <div class="field-input-wrap">
                                <i class="bi bi-person field-icon"></i>
                                <input type="text" id="full_name" name="full_name" class="field-input" value="{{ old('full_name') }}" required autocomplete="name">
                            </div>
                            @error('full_name')
                                <div class="field-help-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="email">Email <span class="required-mark">*</span></label>
                            <div class="field-input-wrap">
                                <i class="bi bi-envelope field-icon"></i>
                                <input type="email" id="email" name="email" class="field-input" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                            @error('email')
                                <div class="field-help-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="professional-fields" data-professional-fields>
                        <div class="field">
                            <label class="field-label" for="job_title">Job title</label>
                            <div class="field-input-wrap">
                                <i class="bi bi-award field-icon"></i>
                                <input type="text" id="job_title" name="job_title" class="field-input" value="{{ old('job_title') }}">
                            </div>
                            @error('job_title')
                                <div class="field-help-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="double-row">
                            <div class="field">
                                <label class="field-label" for="company">Company</label>
                                <div class="field-input-wrap">
                                    <i class="bi bi-building field-icon"></i>
                                    <input type="text" id="company" name="company" class="field-input" value="{{ old('company') }}">
                                </div>
                                @error('company')
                                    <div class="field-help-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label class="field-label" for="years_experience">Years of experience</label>
                                <div class="field-input-wrap">
                                    <i class="bi bi-graph-up-arrow field-icon"></i>
                                    <input type="number" min="0" max="50" id="years_experience" name="years_experience" class="field-input" value="{{ old('years_experience') }}">
                                </div>
                                @error('years_experience')
                                    <div class="field-help-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="password-row">
                        <div class="field">
                            <label class="field-label" for="password">Password <span class="required-mark">*</span></label>
                            <div class="field-input-wrap">
                                <i class="bi bi-lock field-icon"></i>
                                <input type="password" id="password" name="password" class="field-input" required minlength="8" autocomplete="new-password">
                                <button type="button" class="field-suffix" data-toggle-password data-target="#password" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="field-help-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="password_confirmation">Confirm password <span class="required-mark">*</span></label>
                            <div class="field-input-wrap">
                                <i class="bi bi-shield-lock field-icon"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="field-input" required minlength="8" autocomplete="new-password">
                                <button type="button" class="field-suffix" data-toggle-password data-target="#password_confirmation" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="terms-row">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a><span class="required-mark">*</span>.</label>
                    </div>

                    <button type="submit" class="btn-submit">
                        Create my account
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms of Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Career Insights provides educational career content, peer evaluations, and messaging features to support informed career decisions.</p>
                    <p>By creating an account, you agree to provide accurate information and keep your profile up to date. You are responsible for all activity that occurs under your account.</p>
                    <p>You agree to post respectful, truthful, and non-harmful content. Harassment, hate speech, spam, fraudulent activity, and privacy violations are not allowed and may lead to account suspension.</p>
                    <p>Purchases and subscriptions unlock access to insights and related features as described in the platform. Access terms may vary by plan and are managed through your account.</p>
                    <p>Career Insights may moderate, remove, or restrict content and accounts to protect users, platform quality, and compliance with applicable laws and policies.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Career Insights collects account data (such as name, email, and optional profile fields), platform activity, and purchase records to provide core services and support.</p>
                    <p>Messages and evaluation content are stored to enable communication, moderation, and service improvements. We use reasonable safeguards to protect user data.</p>
                    <p>We do not sell personal data. Information may be shared with authorized administrators and service providers only when needed for operations, support, legal compliance, or security.</p>
                    <p>You can request profile updates or account deletion through account settings. Some records may be retained where required for legal, financial, or security obligations.</p>
                    <p>By using Career Insights, you consent to this data processing for service delivery, platform integrity, and user safety.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
