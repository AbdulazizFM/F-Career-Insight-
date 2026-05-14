<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <title>{{ $title ?? 'Dashboard - Career Insights' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .notifications-menu {
            width: 360px;
            max-width: calc(100vw - 24px);
            padding: 0.35rem 0;
            border-radius: 12px;
        }

        .notifications-head {
            border-bottom: 1px solid #edf2fb;
            margin-bottom: 0.2rem;
        }

        .notification-item {
            white-space: normal;
            align-items: flex-start;
            padding-top: 0.55rem;
            padding-bottom: 0.55rem;
        }

        .notification-icon {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #eef3ff;
            color: #1d4ed8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 28px;
            margin-top: 1px;
        }
    </style>
    @include('layouts.partials.theme')
    @stack('styles')
</head>
@php
        $isAdminPanel = (bool) $currentAdmin && request()->is('admin/*');
        $isProfessionalAccount = ! $isAdminPanel && ($isProfessionalUser ?? false);
        $sidebarHomeRoute = $isAdminPanel ? route('admin.dashboard') : route('dashboard');
        $sidebarSubtitle = $isAdminPanel ? 'Admin Console' : 'Career Platform';
        $sidebarTag = $isAdminPanel ? 'Administration' : 'User';
        $displayName = $isAdminPanel ? ($currentAdmin->department ?? 'Admin') : ($currentUser->full_name ?? 'User');
        $displayRole = $isAdminPanel ? 'Administrator' : 'User';
        $displayInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($displayName, 0, 1));
        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        $backFallback = $isAdminPanel ? route('admin.dashboard') : ($isProfessionalAccount ? route('employee.dashboard') : route('dashboard'));
        $backUrl = $previousUrl && $previousUrl !== $currentUrl ? $previousUrl : $backFallback;
        if (! $isAdminPanel) {
            $sidebarHomeRoute = route('dashboard');
        }
@endphp
<body class="dashboard-shell {{ $isAdminPanel ? 'admin-shell' : '' }}">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar {{ $isAdminPanel ? 'admin-sidebar' : '' }}" id="appSidebar">
            <div class="sidebar-brand">
                <a href="{{ $sidebarHomeRoute }}" class="sidebar-logo text-decoration-none">
                    <img src="{{ asset('logo.png') }}" alt="Career Insights" class="sidebar-logo-img sidebar-logo-img-lg">
                    <span class="sidebar-logo-text">
                        <span class="sidebar-logo-title">Career Insights</span>
                        <span class="sidebar-logo-subtitle">{{ $sidebarSubtitle }}</span>
                    </span>
                </a>
                <div class="sidebar-tag">{{ $sidebarTag }}</div>
            </div>

            <nav class="sidebar-nav">
                @if($isAdminPanel)
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->is('admin/users') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.catalog.majors') }}" class="sidebar-item {{ request()->routeIs('admin.catalog.majors') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark"></i>
                        <span>Majors</span>
                    </a>
                    <a href="{{ route('admin.catalog.submajors') }}" class="sidebar-item {{ request()->routeIs('admin.catalog.submajors') ? 'active' : '' }}">
                        <i class="bi bi-diagram-2"></i>
                        <span>Sub Majors</span>
                    </a>
                    <a href="{{ route('admin.catalog.roles') }}" class="sidebar-item {{ request()->routeIs('admin.catalog.roles') ? 'active' : '' }}">
                        <i class="bi bi-briefcase"></i>
                        <span>Roles</span>
                    </a>
                    <a href="{{ route('admin.complaints') }}" class="sidebar-item {{ request()->is('admin/complaints') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Complaints</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="sidebar-item {{ request()->is('admin/reports') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Reports</span>
                    </a>
                   
                    <a href="{{ route('admin.payments') }}" class="sidebar-item {{ request()->is('admin/payments') ? 'active' : '' }}">
                        <i class="bi bi-credit-card"></i>
                        <span>Payments</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') || request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('evaluations.index') }}" class="sidebar-item {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Insights</span>
                    </a>
                    <a href="{{ route('dashboard.majors') }}" class="sidebar-item {{ request()->routeIs('jobs.*') || request()->routeIs('dashboard.majors*') ? 'active' : '' }}">
                        <i class="bi bi-briefcase"></i>
                        <span>Browse Jobs</span>
                    </a>
                    <a href="{{ route('subscriptions.index') }}" class="sidebar-item {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                        <i class="bi bi-bag-check"></i>
                        <span>My Purchases</span>
                    </a>
                    <a href="{{ route('messages.index') }}" class="sidebar-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-dots"></i>
                        <span>Messages</span>
                    </a>
                    <a href="{{ route('complaints.index') }}" class="sidebar-item {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-left-text"></i>
                        <span>My Complaints</span>
                    </a>
                    <a href="{{ route('profile.index') }}" class="sidebar-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i>
                        <span>Profile</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <button type="button" class="sidebar-logout" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </div>
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div class="topbar-left">
                    <button type="button" class="sidebar-toggle-btn" data-sidebar-toggle aria-label="Toggle sidebar">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="topbar-heading">
                        <div class="topbar-title">@yield('page-title', $isAdminPanel ? 'Admin Dashboard' : 'Dashboard')</div>
                        <div class="topbar-subtitle">@yield('page-subtitle', $isAdminPanel ? 'Platform management and operations overview' : 'Monitor your activity and insights')</div>
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="dropdown" id="topbarNotificationDropdown">
                        <button type="button" class="topbar-icon-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications" id="topbarNotificationButton">
                            <i class="bi bi-bell"></i>
                            @if(($topbarNotifications ?? collect())->count())
                                <span id="topbarNotificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ ($topbarNotifications ?? collect())->count() }}
                                </span>
                            @endif
                        </button>
                        @include('layouts.partials.topbar-notifications')
                    </div>

                    <div class="dropdown">
                        <button class="topbar-user dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="topbar-avatar">
                                {{ $displayInitial }}
                            </span>
                            <span class="topbar-user-meta">
                                <span class="topbar-user-name">{{ $displayName }}</span>
                                <span class="topbar-user-role">{{ $displayRole }}</span>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            @if($isAdminPanel)
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ $isAdminPanel ? route('admin.profile.index') : route('profile.index') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">Logout</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="dashboard-content-area">
                @include('layouts.partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>
    <div class="sidebar-backdrop" data-sidebar-backdrop></div>

    <x-confirm-modal
        id="logoutConfirmModal"
        title="Log Out?"
        body="You will need to sign in again to continue."
        action-label="Logout"
        action-color="danger"
        icon="bi-box-arrow-right"
        icon-color="danger"
        :form-action="route('logout')"
        form-method="POST"
    />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var notificationButton = document.getElementById('topbarNotificationButton');
            if (notificationButton) {
                notificationButton.addEventListener('shown.bs.dropdown', function () {
                    var badge = document.getElementById('topbarNotificationBadge');
                    if (badge) {
                        badge.classList.add('d-none');
                    }
                });
            }

            if (!window.jQuery || !jQuery.fn.DataTable) {
                return;
            }

            var normalizeDataTableStructure = function ($table) {
                var $headRow = $table.find('thead tr').last();
                var columnCount = $headRow.children('th,td').length;
                if (!columnCount) {
                    return false;
                }

                $table.children('tbody').children('tr').each(function () {
                    var $row = jQuery(this);
                    var $cells = $row.children('th,td');
                    var cellCount = $cells.length;

                    if (!cellCount) {
                        return;
                    }

                    var hasColspan = false;
                    var normalizedCells = [];
                    $cells.each(function () {
                        var $cell = jQuery(this);
                        var span = parseInt($cell.attr('colspan') || '1', 10);
                        if (!span || span < 1) {
                            span = 1;
                        }

                        if (span > 1) {
                            hasColspan = true;
                        }

                        var $baseCell = $cell.clone();
                        $baseCell.attr('colspan', 1);
                        normalizedCells.push($baseCell);

                        for (var s = 1; s < span; s++) {
                            normalizedCells.push(jQuery('<td></td>'));
                        }
                    });

                    if (hasColspan) {
                        $row.empty();
                        normalizedCells.forEach(function ($cell) {
                            $row.append($cell);
                        });
                        $cells = $row.children('th,td');
                        cellCount = $cells.length;
                    }

                    if (cellCount < columnCount) {
                        for (var i = cellCount; i < columnCount; i++) {
                            $row.append('<td></td>');
                        }
                    } else if (cellCount > columnCount) {
                        $cells.slice(columnCount).remove();
                    }
                });

                return true;
            };

            jQuery('.js-datatable').each(function () {
                var $table = jQuery(this);
                if (jQuery.fn.DataTable.isDataTable($table)) {
                    return;
                }

                $table.find('.modal').each(function () {
                    var $modal = jQuery(this);
                    if (!$modal.data('dtDetached')) {
                        $modal.appendTo(document.body);
                        $modal.data('dtDetached', true);
                    }
                });

                var $tbody = $table.children('tbody');
                var $tbodyRows = $tbody.children('tr');
                var hasCustomEmptyState = $tbodyRows.length === 1 && $tbodyRows.eq(0).find('.empty-state').length > 0;
                if (hasCustomEmptyState) {
                    return;
                }

                if ($tbodyRows.length === 1) {
                    var $firstRowCells = $tbodyRows.eq(0).children('th,td');
                    var columnCount = $table.find('thead tr').last().children('th,td').length;
                    if ($firstRowCells.length === 1) {
                        var span = parseInt($firstRowCells.eq(0).attr('colspan') || '1', 10);
                        if (span >= columnCount) {
                            $tbody.empty();
                        }
                    }
                }
                if (!normalizeDataTableStructure($table)) {
                    return;
                }

                var exportable = $table.data('exportable') === true || $table.data('exportable') === 1 || $table.data('exportable') === '1';
                var options = {
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: []
                };

                if (exportable) {
                    options.dom = 'Bfrtip';
                    options.buttons = [
                        {
                            extend: 'csvHtml5',
                            text: '<i class="bi bi-filetype-csv"></i>CSV',
                            className: 'btn btn-sm',
                            exportOptions: { columns: ':visible' }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="bi bi-filetype-pdf"></i>PDF',
                            className: 'btn btn-sm',
                            exportOptions: { columns: ':visible' }
                        },
                        {
                            extend: 'print',
                            text: '<i class="bi bi-printer"></i>Print',
                            className: 'btn btn-sm',
                            exportOptions: { columns: ':visible' },
                            customize: function (win) {
                                var $body = jQuery(win.document.body);
                                $body.css('font-size', '12px');
                                $body.prepend('<h3 style="margin-bottom:14px;color:#1e3a8a;">Career Insights Report</h3>');
                                jQuery(win.document.body).find('table').addClass('table table-striped table-bordered').css('font-size', '11px');
                            }
                        }
                    ];
                }

                $table.DataTable(options);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
