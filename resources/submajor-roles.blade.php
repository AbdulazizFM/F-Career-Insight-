@extends('layouts.dashboard')

@section('page-title', 'Choose Role')
@section('page-subtitle', 'Select role under your chosen sub major')

@push('styles')
    <style>
        .roles-shell {
            max-width: 1280px;
            margin: 0 auto;
        }

        .roles-topbar {
            margin-bottom: 1rem;
            padding: 0.7rem 0.85rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
        }

        .roles-breadcrumb {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 0;
        }

        .roles-head {
            display: flex;
            gap: 0.9rem;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .roles-head-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #eef2ff;
            color: #1e3a8a;
            display: grid;
            place-items: center;
            flex: 0 0 42px;
        }

        .roles-head-title {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 0.25rem;
            color: #0f172a;
        }

        .roles-head-text {
            color: #64748b;
            margin: 0;
        }

        .roles-stepper {
            margin: 0.2rem 0 1.2rem;
            display: inline-flex;
            gap: 0.45rem;
            padding: 0.35rem 0.55rem;
            border: 1px solid #d7e3fb;
            background: #f7faff;
            border-radius: 999px;
            align-items: center;
        }

        .roles-step {
            font-size: 0.78rem;
            color: #64748b;
        }

        .roles-step.is-active {
            color: #0f172a;
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    <div class="roles-shell">
        <div class="roles-topbar d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="roles-breadcrumb">
                All Majors &gt; {{ optional($subMajor->major)->major_name }} &gt; {{ $subMajor->sub_major_name }}
            </div>
            <a href="{{ route('dashboard.majors.show', $subMajor->major_id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to sub majors
            </a>
        </div>

        <div class="roles-head">
            <div class="roles-head-icon"><i class="bi bi-briefcase"></i></div>
            <div>
                <div class="roles-head-title">{{ $subMajor->sub_major_name }}</div>
                <p class="roles-head-text">{{ $subMajor->description ?: 'Choose a role to view detailed evaluations and insights.' }}</p>
            </div>
        </div>

        <div class="roles-stepper">
            <span class="roles-step">Select Major</span>
            <span class="roles-step">•</span>
            <span class="roles-step">Choose Sub Major</span>
            <span class="roles-step">•</span>
            <span class="roles-step is-active">Select Role</span>
        </div>

        <h4 class="mb-3">Choose Your Role</h4>

        <div class="row g-4">
            @forelse($subMajor->roles as $role)
                <div class="col-md-6 col-xl-4">
                    <div class="job-card h-100">
                        <div class="job-card-top">
                            <span class="badge bg-soft-gold text-gold">{{ optional($subMajor->major)->major_name ?? 'Major' }}</span>
                            <span class="job-price">{{ $role->evaluations_count }}</span>
                        </div>
                        <h5 class="mt-3 mb-2">{{ $role->role_name }}</h5>
                        <p class="text-muted mb-4">{{ \Illuminate\Support\Str::limit((string) $role->challenges, 120) ?: 'Review insights, ratings, and real experiences for this role.' }}</p>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="rating-stars" aria-label="Role evaluations">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= min(5, max(0, (int) round(($role->evaluations_count > 0 ? 4 : 0)))) ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">{{ $role->evaluations_count }} reviews</small>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <a href="{{ route('dashboard.roles.show', $role->role_id) }}" class="btn btn-primary flex-fill">View details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <x-empty-state icon="bi-briefcase" title="No roles available" message="No roles found for this sub major yet." />
                </div>
            @endforelse
        </div>
    </div>
@endsection
