@extends('layouts.dashboard')

@section('page-title', 'Role Details')
@section('page-subtitle', 'Role insights and evaluations')

@push('styles')
    <style>
        @import url('https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css');

        .role-page-shell {
            max-width: 1240px;
            margin: 0 auto;
        }

        .role-hero-panel {
            border: 1px solid #dce7fb;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
        }

        .similar-role-list {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }

        .similar-role-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            border: 1px solid #e5ecfa;
            border-radius: 10px;
            background: #fbfdff;
            padding: 0.55rem 0.65rem;
            text-decoration: none;
            transition: border-color 0.18s ease, background-color 0.18s ease, transform 0.15s ease;
        }

        .similar-role-item:hover {
            border-color: #cddbf8;
            background: #f5f9ff;
            transform: translateY(-1px);
        }

        .similar-role-name {
            font-weight: 600;
            color: #0f172a;
            line-height: 1.2;
        }

        .similar-role-meta {
            font-size: 0.76rem;
            color: #64748b;
            margin-top: 0.2rem;
        }

        .similar-role-arrow {
            color: #3b82f6;
            font-size: 0.88rem;
            flex: 0 0 auto;
        }

        .panel-card,
        .panel-card .panel-body,
        .panel-card .panel-head,
        .evaluation-item,
        .similar-role-item,
        .similar-role-name,
        .similar-role-meta {
            overflow-wrap: anywhere;
            word-break: break-word;
        }
    </style>
@endpush

@section('content')
    <section class="py-5">
        <div class="container role-page-shell">
            <div class="job-detail-card mb-4 role-hero-panel">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                    <div>
                        <a href="{{ route('dashboard.submajors.roles', $role->sub_major_id) }}" class="text-muted text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>Back to roles
                        </a>
                        <h2 class="fw-bold mt-2 mb-2">{{ $role->role_name }}</h2>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">{{ optional(optional($role->subMajor)->major)->major_name ?? 'Major' }}</span>
                            <span class="badge bg-soft-gold text-gold">{{ optional($role->subMajor)->sub_major_name ?? 'Sub Major' }}</span>
                            <span class="badge bg-primary-subtle text-primary">{{ $evaluationCount }} evaluations</span>
                        </div>
                    </div>
                    <div class="salary-pill">{{ $role->salary_range ?: 'Price on access' }}</div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rating-stars" aria-label="Average rating {{ number_format((float) $averageRating, 1) }} out of 5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= round((float) $averageRating) ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                        @endfor
                    </div>
                    <div>
                        <div class="fw-semibold">{{ number_format((float) $averageRating, 1) }}/5</div>
                        <small class="text-muted">{{ $evaluationCount }} reviews</small>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="panel-card h-100">
                            <div class="panel-head">
                                <h5 class="mb-0">Evaluations</h5>
                            </div>
                            <div class="panel-body" id="role-evaluations">
                                @if($accessGranted)
                                    @forelse($evaluations as $evaluation)
                                        <div class="evaluation-item">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>{{ optional($evaluation->user)->full_name ?? 'User' }}</strong>
                                                <span class="rating-badge">{{ $evaluation->rating }}/5</span>
                                            </div>
                                            <div class="small text-muted mb-2">{{ $evaluation->created_at ? $evaluation->created_at->format('M d, Y') : '' }}</div>
                                            <p class="mb-2"><strong>Pros:</strong> {!! $evaluation->advantages !!}</p>
                                            <p class="mb-2"><strong>Cons:</strong> {!! $evaluation->disadvantages !!}</p>
                                            <div class="text-muted mb-0">{!! $evaluation->experience !!}</div>

                                            <div class="mt-3 d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#messageEmployeeModal{{ $evaluation->evaluation_id }}">
                                                    <i class="bi bi-chat-dots me-1"></i>Message Employee
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#complaintEvalModal{{ $evaluation->evaluation_id }}">
                                                    <i class="bi bi-flag me-1"></i>Submit Complaint
                                                </button>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="messageEmployeeModal{{ $evaluation->evaluation_id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-sm">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Message {{ optional($evaluation->user)->full_name ?? 'Employee' }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('messages.send') }}">
                                                        @csrf
                                                        <input type="hidden" name="evaluation_id" value="{{ $evaluation->evaluation_id }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3"><label class="form-label">Subject</label><input type="text" name="subject" class="form-control" value="Question about {{ $role->role_name }}"></div>
                                                            <div><label class="form-label">Message</label><textarea name="body_text" rows="4" class="form-control" required></textarea></div>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Send Message</button></div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="complaintEvalModal{{ $evaluation->evaluation_id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-sm">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Submit Complaint</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('complaints.store') }}">
                                                        @csrf
                                                        <input type="hidden" name="evaluation_id" value="{{ $evaluation->evaluation_id }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" class="form-control" value="Complaint about evaluation #{{ $evaluation->evaluation_id }}" required></div>
                                                            <div><label class="form-label">Description</label><textarea name="description" rows="4" class="form-control" required></textarea></div>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Submit</button></div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">No evaluations available yet.</p>
                                    @endforelse

                                    @if($evaluations instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        <div class="mt-4">{{ $evaluations->links() }}</div>
                                    @endif
                                @else
                                    <div class="locked-panel">
                                        <div class="blurred-content">
                                            <p class="mb-3">{{ optional($role->subMajor)->description }}</p>
                                            @if($role->challenges)
                                                <div class="mb-3">
                                                    <h6 class="fw-bold mb-2">Challenges</h6>
                                                    <p class="mb-0">{{ $role->challenges }}</p>
                                                </div>
                                            @endif
                                            <div class="evaluation-item">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong>Anonymous reviewer</strong>
                                                    <span class="rating-badge">4/5</span>
                                                </div>
                                                <p class="mb-0">Unlock this role to read full evaluations and experience notes.</p>
                                            </div>
                                        </div>
                                        <div class="locked-overlay">
                                            <div class="text-center">
                                                <div class="mb-2 fw-semibold">Unlock this role</div>
                                                <p class="small text-muted mb-3">Subscription or one-time purchase required.</p>
                                                <form method="POST" action="{{ route('checkout.start') }}">
                                                    @csrf
                                                    <input type="hidden" name="type" value="single">
                                                    <input type="hidden" name="sub_major_id" value="{{ $role->sub_major_id }}">
                                                    <button type="submit" class="btn btn-primary">Buy access for 10 SAR</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="panel-card mb-4">
                            <div class="panel-head"><h5 class="mb-0">Access</h5></div>
                            <div class="panel-body">
                                <div class="mb-3"><strong>Average rating:</strong> {{ number_format($averageRating, 1) }}/5</div>
                                <div class="mb-3"><strong>Status:</strong> {{ $accessGranted ? 'Unlocked' : 'Locked' }}</div>

                                @if($accessGranted)
                                    @if($myEvaluation)
                                        <div class="alert alert-warning py-2 px-3 small mb-3">You already submitted an evaluation for this role.</div>
                                    @endif
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#roleEvaluationModal">
                                        {{ $myEvaluation ? 'Update evaluation' : 'Add evaluation' }}
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('checkout.start') }}">
                                        @csrf
                                        <input type="hidden" name="type" value="single">
                                        <input type="hidden" name="sub_major_id" value="{{ $role->sub_major_id }}">
                                        <button type="submit" class="btn btn-primary w-100">Buy access for 10 SAR</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="panel-card">
                            <div class="panel-head"><h5 class="mb-0">Similar roles</h5></div>
                            <div class="panel-body">
                                @forelse($similarRoles as $similar)
                                    <a href="{{ route('dashboard.roles.show', $similar->role_id) }}" class="similar-role-item">
                                        <div>
                                            <div class="similar-role-name">{{ $similar->role_name }}</div>
                                            <div class="similar-role-meta">{{ optional($role->subMajor)->sub_major_name }}</div>
                                        </div>
                                        <i class="bi bi-arrow-right similar-role-arrow"></i>
                                    </a>
                                @empty
                                    <p class="text-muted mb-0">No similar roles available.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($accessGranted)
            <div class="modal fade evaluation-modal" id="roleEvaluationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-sm">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $myEvaluation ? 'Update Evaluation' : 'Create Evaluation' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ $myEvaluation ? route('evaluations.update', $myEvaluation->evaluation_id) : route('evaluations.store') }}">
                            @csrf
                            <input type="hidden" name="role_id" value="{{ $role->role_id }}">
                            <input type="hidden" name="sub_major_id" value="{{ $role->sub_major_id }}">
                            <div class="modal-body">
                                <div class="evaluation-guidelines-box mb-3">
                                    <div class="evaluation-guidelines-title">Evaluation Guidelines</div>
                                    <ul class="evaluation-guidelines-list">
                                        <li>Be honest and constructive in your feedback</li>
                                        <li>Focus on professional aspects, not personal grievances</li>
                                        <li>Avoid sharing confidential company information</li>
                                        <li>Use respectful language and maintain professionalism</li>
                                    </ul>
                                </div>
                                <div class="evaluation-form-section mb-3">
                                    <div class="evaluation-form-section-title">Role Context</div>
                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="form-label">Major</label><input type="text" class="form-control form-control-lg" value="{{ optional(optional($role->subMajor)->major)->major_name }}" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Sub Major</label><input type="text" class="form-control form-control-lg" value="{{ optional($role->subMajor)->sub_major_name }}" disabled></div>
                                    </div>
                                </div>
                                <div class="evaluation-form-section mb-3">
                                    <div class="evaluation-form-section-title">Quality Rating</div>
                                    <label class="form-label d-block mb-1">Rating (1-5)</label>
                                    <input type="number" name="rating" min="1" max="5" class="form-control form-control-lg" value="{{ old('rating', $myEvaluation->rating ?? 5) }}" required>
                                </div>
                                <div class="evaluation-form-section">
                                    <div class="evaluation-form-section-title">Professional Insight</div>
                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="form-label">Advantages</label><textarea name="advantages" class="form-control js-richtext" rows="4" required>{{ old('advantages', $myEvaluation->advantages ?? '') }}</textarea></div>
                                        <div class="col-md-6"><label class="form-label">Disadvantages</label><textarea name="disadvantages" class="form-control js-richtext" rows="4" required>{{ old('disadvantages', $myEvaluation->disadvantages ?? '') }}</textarea></div>
                                        <div class="col-12"><label class="form-label">Experience</label><textarea name="experience" class="form-control js-richtext" rows="5" required>{{ old('experience', $myEvaluation->experience ?? '') }}</textarea></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">{{ $myEvaluation ? 'Save Changes' : 'Save Evaluation' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Quill === 'undefined') return;
            var form = document.querySelector('#roleEvaluationModal form');
            if (!form) return;

            var editors = [];
            form.querySelectorAll('textarea.js-richtext').forEach(function (textarea) {
                textarea.dataset.wasRequired = textarea.required ? '1' : '0';
                textarea.required = false;
                textarea.style.display = 'none';
                var shell = document.createElement('div');
                shell.className = 'job-richtext-shell';
                textarea.insertAdjacentElement('afterend', shell);
                var editor = document.createElement('div');
                shell.appendChild(editor);
                var quill = new Quill(editor, {
                    theme: 'snow',
                    modules: { toolbar: [['bold', 'italic', 'underline'], [{ list: 'ordered' }, { list: 'bullet' }], ['link'], ['clean']] }
                });
                if ((textarea.value || '').trim()) quill.root.innerHTML = textarea.value.trim();
                editors.push({ textarea: textarea, quill: quill });
            });

            form.addEventListener('submit', function (event) {
                var firstInvalid = null;
                editors.forEach(function (item) {
                    var html = item.quill.root.innerHTML.trim();
                    var text = item.quill.getText().trim();
                    item.textarea.value = html === '<p><br></p>' ? '' : html;
                    if (item.textarea.dataset.wasRequired === '1' && text.length === 0) {
                        firstInvalid = firstInvalid || item;
                    }
                });
                if (firstInvalid) {
                    event.preventDefault();
                    firstInvalid.quill.focus();
                }
            });
        });
    </script>
@endpush
