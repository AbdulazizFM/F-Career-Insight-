@props([
    'type' => 'success',
    'message' => '',
])

@if($message !== '')
    <div class="toast align-items-center text-bg-{{ $type }} border-0 show" role="status" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">{{ $message }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif
