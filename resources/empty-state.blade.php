@props([
    'icon' => 'bi-inbox',
    'title' => 'Nothing to show',
    'message' => 'No records are available yet.',
    'ctaLabel' => null,
    'ctaHref' => null,
])

<div class="empty-state text-center py-5">
    <div class="empty-state-icon mb-3">
        <i class="bi {{ $icon }}"></i>
    </div>
    <h5 class="mb-2">{{ $title }}</h5>
    <p class="text-muted mb-3">{{ $message }}</p>
    @if($ctaLabel && $ctaHref)
        <a href="{{ $ctaHref }}" class="btn btn-primary">{{ $ctaLabel }}</a>
    @endif
</div>
