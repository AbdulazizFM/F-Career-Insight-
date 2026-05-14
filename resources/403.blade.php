@extends('layouts.dashboard')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="error-page">
                <div class="error-code">403</div>
                <h1 class="fw-bold mb-3">Access denied</h1>
                <p class="text-muted mb-4">You do not have permission to view this page.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('home') }}" class="btn btn-primary">Go home</a>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Go back</a>
                </div>
            </div>
        </div>
    </section>
@endsection
