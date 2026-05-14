<ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 notifications-menu">
    <li class="notifications-head px-3 py-2">
        <div class="fw-semibold">Notifications</div>
    </li>
    @forelse(($topbarNotifications ?? collect()) as $note)
        @include('layouts.partials.topbar-notification-item', ['note' => $note])
    @empty
        <li><span class="dropdown-item text-muted small py-3">No new notifications</span></li>
    @endforelse
</ul>
