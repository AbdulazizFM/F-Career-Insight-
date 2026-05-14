@props([
    'title',
    'subtitle' => null,
    'breadcrumbs' => [],
])

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        @if(! empty($breadcrumbs))
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    @foreach($breadcrumbs as $crumb)
                        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" @if($loop->last) aria-current="page" @endif>
                            @if(! $loop->last && ! empty($crumb['url']))
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                            @else
                                {{ $crumb['label'] }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
        <h2 class="fw-bold mb-1">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
    </div>
    {{ $slot }}
</div>
