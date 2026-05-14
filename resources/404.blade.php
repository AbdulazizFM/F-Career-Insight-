@extends('layouts.dashboard')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="error-page">
                <div class="error-code">404</div>
                <h1 class="fw-bold mb-3">Page not found</h1>
                <p class="text-muted mb-4">The page you requested does not exist or has been moved.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('home') }}" class="btn btn-primary">Go home</a>
                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">Browse jobs</a>
                </div>
            </div>
        </div>
    </section>
@endsection
