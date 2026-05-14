@extends('layouts.dashboard')

@section('page-title', 'Discover Majors')
@section('page-subtitle', 'Select a major and explore specialized roles')

@push('styles')
    <style>
        .majors-shell {
            max-width: 1280px;
            margin: 0 auto;
        }

        .majors-hero {
            margin-bottom: 1.2rem;
            padding: 1rem 1.1rem;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .majors-hero h2 {
            font-size: 1.7rem;
            margin-bottom: 0.35rem;
            color: #0f172a;
        }

        .majors-hero p {
            color: #64748b;
            margin: 0;
            max-width: 760px;
        }

        .majors-stepper {
            margin-top: 0.85rem;
            display: inline-flex;
            gap: 0.45rem;
            padding: 0.35rem 0.55rem;
            border: 1px solid #d7e3fb;
            background: #f7faff;
            border-radius: 999px;
            align-items: center;
        }

        .majors-step {
            font-size: 0.78rem;
            color: #64748b;
        }

        .majors-step.is-active {
            color: #0f172a;
            font-weight: 700;
        }

        .major-card {
            border: 1px solid #dbe6fb;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            height: 100%;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
            transition: transform 0.18s ease, box-shadow 0.2s ease;
        }

        .major-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(30, 58, 138, 0.11);
        }

        .major-media {
            position: relative;
            height: 154px;
            background-size: cover;
            background-position: center;
            background-color: #f1f5f9;
        }

        .major-icon {
            position: absolute;
            left: 0.65rem;
            bottom: 0.65rem;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(15, 23, 42, 0.72);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 0.9rem;
        }

        .major-body {
            padding: 0.95rem;
        }

        .major-title {
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .major-desc {
            color: #64748b;
            font-size: 0.85rem;
            min-height: 40px;
            margin-bottom: 0.7rem;
        }

        .major-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.8rem;
        }
    </style>
@endpush

@section('content')
    <div class="majors-shell">
        <div class="majors-hero">
            <h2>Discover Your Career Path</h2>
            <p>Start by selecting a major field that interests you. We will help you explore specific careers and related evaluations.</p>
            <div class="majors-stepper">
                <span class="majors-step is-active">1 Select Major</span>
                <span class="majors-step">•</span>
                <span class="majors-step">2 Choose Sub Major</span>
                <span class="majors-step">•</span>
                <span class="majors-step">3 View Roles</span>
            </div>
        </div>

        @php
            $fallbackImages = [
                'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1474631245212-32dc3c8310c6?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80',
            ];
            $icons = ['bi-laptop', 'bi-gear', 'bi-briefcase', 'bi-heart-pulse', 'bi-bank', 'bi-globe2'];
        @endphp

        <div class="row g-4">
            @forelse($majors as $index => $major)
                @php
                    $image = $major->image_url ?: $fallbackImages[$index % count($fallbackImages)];
                    $icon = $icons[$index % count($icons)];
                @endphp
                <div class="col-md-6 col-xl-4">
                    <div class="major-card">
                        <div class="major-media" style="background-image: url('{{ $image }}')">
                            <div class="major-icon"><i class="bi {{ $icon }}"></i></div>
                        </div>
                        <div class="major-body">
                            <div class="major-title">{{ $major->major_name }}</div>
                            <div class="major-desc">{{ \Illuminate\Support\Str::limit((string) $major->description, 90) ?: 'Explore career options and specializations in this major field.' }}</div>
                            <div class="major-meta">
                                <span>{{ $major->sub_majors_count }} specializations</span>
                                <span class="badge rounded-pill bg-primary-subtle text-primary">Pathways</span>
                            </div>
                            <a href="{{ route('dashboard.majors.show', $major->major_id) }}" class="btn btn-primary w-100">Explore </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <x-empty-state icon="bi-diagram-3" title="No majors available" message="Majors will appear here once they are added by admin." />
                </div>
            @endforelse
        </div>
    </div>
@endsection
