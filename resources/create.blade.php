@extends('layouts.dashboard')

@section('page-title', isset($evaluation) ? 'Edit Evaluation' : 'Create Evaluation')
@section('page-subtitle', 'Write structured insights with a clear and professional format')

@push('styles')
    <style>
        .evaluation-compose {
            width: 100%;
        }

        .evaluation-card {
            border: 1px solid #e6ebf5;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(11, 23, 55, 0.04);
            overflow: hidden;
        }

        .evaluation-card-head {
            padding: 1.15rem 1.25rem;
            border-bottom: 1px solid #edf1f7;
            background: linear-gradient(180deg, #fcfdff 0%, #f8faff 100%);
        }

        .evaluation-card-body {
            padding: 1.25rem;
        }

        .rating-stars {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
            padding: 0.35rem 0 0.5rem;
        }

        .rating-star-btn {
            border: 0;
            background: transparent;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            cursor: pointer;
            transition: transform 0.16s ease, color 0.18s ease, box-shadow 0.18s ease;
        }

        .rating-star-btn i {
            font-size: 1.45rem;
        }

        .rating-star-btn:hover {
            color: #f59e0b;
            transform: translateY(-1px) scale(1.06);
            box-shadow: 0 8px 18px rgba(245, 158, 11, 0.2);
        }

        .rating-star-btn.is-active {
            color: #f59e0b;
            text-shadow: 0 2px 10px rgba(245, 158, 11, 0.35);
        }

        .rating-star-btn.is-preview {
            color: #fbbf24;
        }

        .rating-value-badge {
            border-radius: 999px;
            padding: 0.25rem 0.7rem;
            background: #eef2ff;
            color: #1e3a8a;
            border: 1px solid rgba(30, 58, 138, 0.16);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .field-label-strong {
            font-size: 0.83rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0;
        }
    </style>
@endpush

@section('content')
    @php
        $majorOptions = $subMajors->map(function ($item) {
            return $item->major;
        })->filter()->unique('major_id')->sortBy('major_name')->values();
        $subMajorsJson = $subMajors->map(function ($job) {
            return [
                'id' => (int) $job->sub_major_id,
                'name' => $job->sub_major_name,
                'major_id' => (int) $job->major_id,
            ];
        })->values();
        $rolesJson = $roles->map(function ($role) {
            return [
                'id' => (int) $role->role_id,
                'name' => $role->role_name,
                'sub_major_id' => (int) $role->sub_major_id,
            ];
        })->values();

        $selectedSubMajorId = (int) old('sub_major_id', $evaluation->sub_major_id ?? optional($selectedSubMajor)->sub_major_id ?? 0);
        $selectedRoleId = (int) old('role_id', $evaluation->role_id ?? 0);
        $selectedMajorId = 0;
        if ($selectedSubMajorId > 0) {
            $matchedSubMajor = $subMajors->firstWhere('sub_major_id', $selectedSubMajorId);
            $selectedMajorId = (int) optional($matchedSubMajor)->major_id;
        }
    @endphp

    <section class="evaluation-compose">
        <div class="evaluation-card">
            <div class="evaluation-card-head">
                <h2 class="fw-bold mb-1">{{ isset($evaluation) ? 'Edit Evaluation' : 'Create Evaluation' }}</h2>
                <p class="text-muted mb-0">Share a clear and balanced career insight to help graduates make better decisions.</p>
            </div>
            <div class="evaluation-card-body">
                @if(isset($existingEvaluation) && $existingEvaluation && !isset($evaluation))
                    <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span>You already submitted an evaluation for this job title.</span>
                        <a href="{{ route('evaluations.edit', $existingEvaluation->evaluation_id) }}" class="btn btn-sm btn-outline-dark">Edit Existing</a>
                    </div>
                @endif

                <form method="POST" action="{{ isset($evaluation) ? route('evaluations.update', $evaluation->evaluation_id) : route('evaluations.store') }}">
                    @csrf

                    <div class="row g-4">
                        <div class="col-lg-4">
                            <label class="form-label field-label-strong">Major</label>
                            <select class="form-select form-select-lg js-major-select" required>
                                <option value="">Select major</option>
                                @foreach($majorOptions as $major)
                                    <option value="{{ $major->major_id }}" {{ $selectedMajorId === (int) $major->major_id ? 'selected' : '' }}>{{ $major->major_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label field-label-strong">Sub Major</label>
                            <select name="sub_major_id" class="form-select form-select-lg js-submajor-select" required data-selected-submajor-id="{{ $selectedSubMajorId }}">
                                <option value="">Select sub-major</option>
                            </select>
                            @error('sub_major_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label field-label-strong">Role</label>
                            <select name="role_id" class="form-select form-select-lg js-role-select" required data-selected-role-id="{{ $selectedRoleId }}">
                                <option value="">Select role</option>
                            </select>
                            @error('role_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label field-label-strong d-block">Rating</label>
                            @php
                                $selectedRating = (int) old('rating', $evaluation->rating ?? 5);
                            @endphp
                            <input type="hidden" id="ratingInput" name="rating" value="{{ $selectedRating }}">
                            <div class="rating-stars" id="ratingStars">
                                @for($rating = 1; $rating <= 5; $rating++)
                                    <button type="button" class="rating-star-btn {{ $selectedRating >= $rating ? 'is-active' : '' }}" data-value="{{ $rating }}" aria-label="Rate {{ $rating }} star{{ $rating > 1 ? 's' : '' }}">
                                        <i class="bi {{ $selectedRating >= $rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    </button>
                                @endfor
                                <span class="rating-value-badge" id="ratingValueBadge">{{ $selectedRating }}/5</span>
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label field-label-strong">Advantages</label>
                            <textarea name="advantages" class="form-control form-control-lg" rows="5" required>{{ old('advantages', $evaluation->advantages ?? '') }}</textarea>
                            @error('advantages')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label field-label-strong">Disadvantages</label>
                            <textarea name="disadvantages" class="form-control form-control-lg" rows="5" required>{{ old('disadvantages', $evaluation->disadvantages ?? '') }}</textarea>
                            @error('disadvantages')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label field-label-strong">Experience</label>
                            <textarea name="experience" class="form-control form-control-lg" rows="6" required>{{ old('experience', $evaluation->experience ?? '') }}</textarea>
                            @error('experience')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2-circle me-1"></i>{{ isset($evaluation) ? 'Update Evaluation' : 'Save Evaluation' }}
                        </button>
                        <a href="{{ route('evaluations.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var subMajors = @json($subMajorsJson);
            var roles = @json($rolesJson);

            var majorSelect = document.querySelector('.js-major-select');
            var subMajorSelect = document.querySelector('.js-submajor-select');
            var roleSelect = document.querySelector('.js-role-select');
            if (majorSelect && subMajorSelect && roleSelect) {
                var selectedSubMajorId = Number(subMajorSelect.getAttribute('data-selected-submajor-id') || 0);
                var selectedRoleId = Number(roleSelect.getAttribute('data-selected-role-id') || 0);

                var renderSubMajors = function () {
                    var majorId = Number(majorSelect.value || 0);
                    var current = Number(subMajorSelect.value || selectedSubMajorId || 0);
                    subMajorSelect.innerHTML = '<option value="">Select sub-major</option>';
                    if (!majorId) {
                        return;
                    }

                    subMajors.filter(function (item) {
                        return Number(item.major_id) === majorId;
                    }).forEach(function (item) {
                        var option = document.createElement('option');
                        option.value = String(item.id);
                        option.textContent = item.name;
                        if (current === Number(item.id)) {
                            option.selected = true;
                        }
                        subMajorSelect.appendChild(option);
                    });
                    renderRoles();
                };

                var renderRoles = function () {
                    var subMajorId = Number(subMajorSelect.value || selectedSubMajorId || 0);
                    var current = Number(roleSelect.value || selectedRoleId || 0);
                    roleSelect.innerHTML = '<option value="">Select role</option>';
                    if (!subMajorId) {
                        return;
                    }

                    roles.filter(function (item) {
                        return Number(item.sub_major_id) === subMajorId;
                    }).forEach(function (item) {
                        var option = document.createElement('option');
                        option.value = String(item.id);
                        option.textContent = item.name;
                        if (current === Number(item.id)) {
                            option.selected = true;
                        }
                        roleSelect.appendChild(option);
                    });
                };

                renderSubMajors();
                majorSelect.addEventListener('change', function () {
                    selectedSubMajorId = 0;
                    selectedRoleId = 0;
                    renderSubMajors();
                });
                subMajorSelect.addEventListener('change', function () {
                    selectedRoleId = 0;
                    renderRoles();
                });
            }

            var starsWrap = document.getElementById('ratingStars');
            var ratingInput = document.getElementById('ratingInput');
            var ratingBadge = document.getElementById('ratingValueBadge');
            if (!starsWrap || !ratingInput) {
                return;
            }

            var starButtons = starsWrap.querySelectorAll('.rating-star-btn');
            var paintStars = function (value) {
                starButtons.forEach(function (button) {
                    var current = Number(button.getAttribute('data-value'));
                    var icon = button.querySelector('i');
                    var active = current <= value;
                    button.classList.toggle('is-active', active);
                    button.classList.remove('is-preview');
                    if (icon) {
                        icon.classList.toggle('bi-star-fill', active);
                        icon.classList.toggle('bi-star', !active);
                    }
                });
                if (ratingBadge) {
                    ratingBadge.textContent = value + '/5';
                }
            };

            var previewStars = function (value) {
                starButtons.forEach(function (button) {
                    var current = Number(button.getAttribute('data-value'));
                    var icon = button.querySelector('i');
                    var active = current <= value;
                    button.classList.toggle('is-preview', active);
                    if (icon) {
                        icon.classList.toggle('bi-star-fill', active);
                        icon.classList.toggle('bi-star', !active);
                    }
                });
            };

            starButtons.forEach(function (button) {
                button.addEventListener('mouseenter', function () {
                    var value = Number(button.getAttribute('data-value'));
                    previewStars(value);
                });

                button.addEventListener('focus', function () {
                    var value = Number(button.getAttribute('data-value'));
                    previewStars(value);
                });

                button.addEventListener('click', function () {
                    var value = Number(button.getAttribute('data-value'));
                    ratingInput.value = value;
                    paintStars(value);
                });
            });

            starsWrap.addEventListener('mouseleave', function () {
                paintStars(Number(ratingInput.value || 5));
            });

            paintStars(Number(ratingInput.value || 5));
        });
    </script>
@endpush
