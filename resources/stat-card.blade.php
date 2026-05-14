@props([
    'label',
    'value',
    'icon' => 'bi-bar-chart',
    'color' => 'primary',
    'suffix' => null,
    'href' => null,
])

@if($href)
    <a href="{{ $href }}" class="text-decoration-none text-reset d-block h-100">
@endif
        <div class="dashboard-stat-card stat-card-center h-100{{ $href ? ' cursor-pointer' : '' }}">
            <div class="dashboard-stat-icon bg-{{ $color }}-subtle text-{{ $color }}">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div>
                <div class="dashboard-stat-label">{{ $label }}</div>
                <div class="dashboard-stat-value">
                    {{ $value }}@if($suffix) <span class="fs-6 fw-semibold">{{ $suffix }}</span>@endif
                </div>
            </div>
        </div>
@if($href)
    </a>
@endif
