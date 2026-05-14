@extends('layouts.dashboard')

@section('page-title', 'Payment Failed')
@section('page-subtitle', 'Your transaction could not be completed')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="panel-card text-center">
                <div class="panel-body py-5">
                    <div class="confirm-icon-badge bg-danger-subtle text-danger mx-auto mb-3">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <h3 class="mb-2">Payment Failed</h3>
                    <p class="text-muted mb-4">No charge was processed. Please try again using another simulated method.</p>
                    <div class="d-flex justify-content-center gap-2">
                        @php($dashboardRoute = ($isProfessionalUser ?? false) ? route('employee.dashboard') : route('dashboard'))
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">Try Again</a>
                        <a href="{{ $dashboardRoute }}" class="btn btn-outline-secondary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
