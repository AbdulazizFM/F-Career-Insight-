<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <title>{{ $title ?? 'Career Insights' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @include('layouts.partials.theme')
    @stack('styles')
</head>
<body class="{{ request()->is('login') || request()->is('register') ? 'auth-page' : 'bg-light' }}">
    @php
        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        $backFallback = ($isUserLoggedIn && ($currentUser->user_type ?? 'graduate') === 'professional')
            ? route('employee.dashboard')
            : ($isUserLoggedIn ? route('dashboard') : route('home'));
        $backUrl = $previousUrl && $previousUrl !== $currentUrl ? $previousUrl : $backFallback;
    @endphp
    @unless(request()->is('login') || request()->is('register'))
        @include('layouts.partials.header')
    @endunless

    <main>
        <div class="container pt-3">

            @include('layouts.partials.alerts')
        </div>
        @yield('content')
    </main>

    @unless(request()->is('login') || request()->is('register'))
        @include('layouts.partials.footer')
    @endunless

    @if($isUserLoggedIn || $isAdminLoggedIn)
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
    @endif

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
