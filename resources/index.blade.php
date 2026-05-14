@extends('layouts.dashboard')

@section('page-title', 'My Purchases')
@section('page-subtitle', 'Manage your subscription and purchased job titles')

@section('content')
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 subscriptions-header">
        <div>
            <h2 class="fw-bold mb-1">Subscriptions & Purchases</h2>
            <p class="text-muted mb-0">Choose monthly access or buy a single job title.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-4">
            <div class="pricing-card h-100 subscriptions-pricing-card">
                <div class="pricing-badge">Single Job</div>
                <h4 class="mb-2">One title access</h4>
                <div class="pricing-value">10 <span>SAR</span></div>
                <p class="text-muted">Unlock one job title and its evaluations.</p>
                <a href="{{ route('dashboard.majors') }}" class="btn btn-outline-primary w-100">Browse job titles</a>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="pricing-card pricing-card-featured h-100 subscriptions-pricing-card">
                <div class="pricing-badge bg-primary text-white">Monthly</div>
                <h4 class="mb-2">Full access</h4>
                <div class="pricing-value text-primary">29 <span>SAR</span></div>
                <p class="text-muted">Access all titles for one month.</p>
                <form method="POST" action="{{ route('checkout.start') }}">
                    @csrf
                    <input type="hidden" name="type" value="monthly">
                    <button type="submit" class="btn btn-primary w-100">Subscribe monthly</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="panel-card h-100 subscriptions-panel-card">
                <div class="panel-head">
                    <h5 class="mb-0">Current subscription</h5>
                </div>
                <div class="panel-body">
                    @if($currentSubscription)
                        <div class="mb-2"><strong>Plan:</strong> {{ $currentSubscription->plan_type }}</div>
                        <div class="mb-2"><strong>Status:</strong> {{ $currentSubscription->status }}</div>
                        <div class="mb-2"><strong>Price:</strong> {{ $currentSubscription->price }} SAR</div>
                        <div class="mb-0"><strong>Ends:</strong> {{ $currentSubscription->end_date }}</div>
                    @else
                        <p class="text-muted mb-0">No active subscription.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel-card h-100 subscriptions-panel-card">
                <div class="panel-head">
                    <h5 class="mb-0">Recent purchases</h5>
                </div>
                <div class="panel-body">
                    @forelse($recentPurchases as $purchase)
                        <div class="evaluation-item">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $purchase->subMajor->sub_major_name ?? 'Purchase' }}</strong>
                                <span>{{ $purchase->price }} SAR</span>
                            </div>
                            <small class="text-muted">{{ $purchase->purchase_date }}</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No purchases yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .subscriptions-header h2 {
            font-size: 1.65rem;
            letter-spacing: 0;
        }

        .subscriptions-pricing-card,
        .subscriptions-panel-card {
            border-radius: 14px;
            box-shadow: 0 14px 34px rgba(15, 42, 95, 0.08);
            border: 1px solid rgba(15, 42, 95, 0.1);
        }

        .subscriptions-pricing-card h4,
        .subscriptions-panel-card .panel-head h5 {
            color: #0f172a;
            font-weight: 700;
            letter-spacing: 0;
        }

        .subscriptions-pricing-card .text-muted,
        .subscriptions-panel-card .text-muted {
            color: #64748b !important;
        }

        .subscriptions-panel-card .panel-head {
            border-bottom-color: rgba(15, 42, 95, 0.12);
        }

        .subscriptions-panel-card .panel-body strong {
            color: #0f2a5f;
            font-weight: 700;
        }

        .subscriptions-panel-card .evaluation-item {
            padding: 0.9rem 0;
        }
    </style>
@endpush
