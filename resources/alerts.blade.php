<div class="toast-container position-fixed top-0 end-0 p-3">
    <x-toast type="success" :message="session('success', '')" />
    <x-toast type="danger" :message="session('error', '')" />
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <div class="fw-semibold mb-1">Please review the highlighted fields.</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
