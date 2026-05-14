@extends('layouts.dashboard')

@section('page-title', 'Choose Sub Major')
@section('page-subtitle', 'Select specialization under your chosen major')

@push('styles')
    <style>
        .submajors-shell {
            max-width: 1280px;
            margin: 0 auto;
        }

        .submajors-topbar {
            margin-bottom: 1rem;
            padding: 0.7rem 0.85rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
        }

        .submajors-breadcrumb {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 0;
        }

        .submajors-head {
            display: flex;
            gap: 0.9rem;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .submajors-head-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #eef2ff;
            color: #1e3a8a;
            display: grid;
            place-items: center;
            flex: 0 0 42px;
        }

        .submajors-head-title {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 0.25rem;
            color: #0f172a;
        }

        .submajors-head-text {
            color: #64748b;
            margin: 0;
        }

        .submajors-stepper {
            margin: 0.2rem 0 1.2rem;
            display: inline-flex;
            gap: 0.45rem;
            padding: 0.35rem 0.55rem;
            border: 1px solid #d7e3fb;
            background: #f7faff;
            border-radius: 999px;
            align-items: center;
        }

        .submajors-step {
            font-size: 0.78rem;
            color: #64748b;
        }

        .submajors-step.is-active {
            color: #0f172a;
            font-weight: 700;
        }

        .submajor-card {
            border: 1px solid #dbe6fb;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            height: 100%;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            transition: transform 0.18s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .submajor-card:hover {
            transform: translateY(-3px);
            border-color: #c5d4f7;
            box-shadow: 0 16px 30px rgba(30, 58, 138, 0.12);
        }

        .submajor-media {
            height: 138px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .submajor-pill {
            position: absolute;
            top: 0.55rem;
            right: 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            background: rgba(15, 23, 42, 0.78);
            color: #fff;
            padding: 0.2rem 0.55rem;
            font-weight: 600;
        }

        .submajor-body {
            padding: 0.9rem 0.95rem;
        }

        .submajor-title {
            font-weight: 700;
            margin-bottom: 0.35rem;
            color: #0f172a;
        }

        .submajor-desc {
            color: #64748b;
            font-size: 0.84rem;
            min-height: 42px;
            margin-bottom: 0.75rem;
        }

        .submajor-link {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1d4ed8;
            text-decoration: none;
        }

        .submajor-link:hover {
            color: #1e40af;
        }

        .submajor-link i {
            transition: transform 0.16s ease;
        }

        .submajor-link:hover i {
            transform: translateX(2px);
        }
    </style>
@endpush

@section('content')
    <div class="submajors-shell">
        <div class="submajors-topbar d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="submajors-breadcrumb">All Majors &nbsp;>&nbsp; {{ $major->major_name }}</div>
            <a href="{{ route('dashboard.majors') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to majors
            </a>
        </div>

        <div class="submajors-head">
            <div class="submajors-head-icon"><i class="bi bi-mortarboard"></i></div>
            <div>
                <div class="submajors-head-title">{{ $major->major_name }}</div>
                <p class="submajors-head-text">{{ $major->description ?: 'Choose a specialization to explore career roles and evaluations.' }}</p>
            </div>
        </div>

        <div class="submajors-stepper">
            <span class="submajors-step">Select Major</span>
            <span class="submajors-step">•</span>
            <span class="submajors-step is-active">Choose Sub Major</span>
            <span class="submajors-step">•</span>
            <span class="submajors-step">View Roles</span>
        </div>

        <h4 class="mb-3">Choose Your Specialization</h4>

        @php
            $images = [
                'https://images.unsplash.com/photo-1581092160607-ee22731d8c0a?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1565043666747-69f6646db940?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1581094271901-8022df4466f9?auto=format&fit=crop&w=1200&q=80',
            ];
        @endphp

        <div class="row g-3">
            @forelse($major->subMajors as $index => $subMajor)
                <div class="col-md-4">
                    <div class="submajor-card">
                        <div class="submajor-media" style="background-image:url('{{ $images[$index % count($images)] }}')">
                            <span class="submajor-pill">{{ $subMajor->evaluations_count }} role{{ $subMajor->evaluations_count == 1 ? '' : 's' }}</span>
                        </div>
                        <div class="submajor-body">
                            <div class="submajor-title">{{ $subMajor->sub_major_name }}</div>
                            <div class="submajor-desc">{{ \Illuminate\Support\Str::limit((string) $subMajor->description, 95) ?: 'Explore pathways, responsibilities, and evaluations for this specialization.' }}</div>
                            <a href="{{ route('dashboard.submajors.roles', $subMajor->sub_major_id) }}" class="submajor-link">Explore Roles <i class="bi bi-chevron-right small"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <x-empty-state icon="bi-diagram-3" title="No specializations available" message="No sub majors found for this major yet." />
                </div>
            @endforelse
        </div>
    </div>
@endsection
