@php
    $targetUrl = $note['url'] ?? '#';
@endphp

<li>
    <a href="{{ $targetUrl }}" class="dropdown-item d-flex align-items-start gap-2 notification-item">
        <span class="notification-icon">
            <i class="bi {{ $note['icon'] }}"></i>
        </span>
        <span class="small">{{ $note['text'] }}</span>
    </a>
</li>
