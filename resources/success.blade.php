@extends('layouts.dashboard')

@section('page-title', 'Payment Success')
@section('page-subtitle', 'Your transaction has been completed')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="panel-card text-center">
                <div class="panel-body py-5">
                    <div class="confirm-icon-badge bg-success-subtle text-success mx-auto mb-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h3 class="mb-2">Payment Successful</h3>
                    <p class="text-muted mb-4">Your access has been activated and is now available in your account.</p>
                    <div class="d-flex justify-content-center gap-2">
                        @php($dashboardRoute = ($isProfessionalUser ?? false) ? route('employee.dashboard') : route('dashboard'))
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">Go to Subscriptions</a>
                        <a href="{{ $dashboardRoute }}" class="btn btn-outline-secondary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
