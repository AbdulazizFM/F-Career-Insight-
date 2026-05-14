@props([
    'id',
    'title',
    'body',
    'actionLabel' => 'Confirm',
    'actionColor' => 'primary',
    'icon' => 'bi-exclamation-triangle',
    'iconColor' => 'warning',
    'formAction' => '#',
    'formMethod' => 'POST',
])

@php
    $method = strtoupper((string) $formMethod);
    $httpMethod = in_array($method, ['GET', 'POST'], true) ? null : $method;
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-body p-4 text-center">
                <div class="confirm-icon-badge bg-{{ $iconColor }}-subtle text-{{ $iconColor }} mx-auto mb-3">
                    <i class="bi {{ $icon }}" aria-hidden="true"></i>
                </div>
                <h5 class="mb-2" id="{{ $id }}Label">{{ $title }}</h5>
                <p class="text-muted mb-0">{{ $body }}</p>
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ $formAction }}" method="POST">
                    @csrf
                    @if($httpMethod)
                        @method($httpMethod)
                    @endif
                    <button type="submit" class="btn btn-{{ $actionColor }}">{{ $actionLabel }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
