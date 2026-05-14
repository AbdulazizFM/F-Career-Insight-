<style>
    :root {
        --bs-primary: #036;
        --bs-primary-rgb: 30, 58, 138;
        --primary-light: #dbe6ff;
        --primary-dark: #036;
        --ci-radius-card: 8px;
        --ci-radius-button: 6px;
        --ci-radius-input: 4px;
    }

    .card,
    .panel-card,
    .job-card,
    .pricing-card,
    .form-shell,
    .auth-form-card,
    .stat-card,
    .dashboard-stat-card,
    .dashboard-chart-card,
    .dashboard-side-card {
        border-radius: var(--ci-radius-card);
    }

    .btn {
        border-radius: var(--ci-radius-button);
    }

    .form-control,
    .form-select,
    .input-group-text {
        border-radius: var(--ci-radius-input);
    }

    .confirm-icon-badge {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 1.35rem;
    }

    .toast-container {
        z-index: 1090;
    }

    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d9e2ef;
        border-radius: 4px;
        padding: 0.35rem 0.55rem;
        background: #fff;
    }

    .dataTables_wrapper .dt-buttons {
        display: inline-flex;
        gap: 0.4rem;
        margin-right: 0.5rem;
    }

    .dataTables_wrapper .dt-button.btn {
        border-radius: 6px !important;
        border: 1px solid #c8d6f0 !important;
        background: #f6f9ff !important;
        color: #1e3a8a !important;
        font-weight: 600;
        padding: 0.35rem 0.6rem !important;
    }

    .dataTables_wrapper .dt-button.btn:hover {
        background: #e8f0ff !important;
        border-color: #b4c8f0 !important;
    }

    .dataTables_wrapper .dt-button .bi {
        margin-right: 0.28rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
        color: #fff !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--primary-light) !important;
        border-color: var(--primary-light) !important;
        color: var(--primary-dark) !important;
    }

    .breadcrumb {
        background: #f4f7ff;
        border: 1px solid #dbe6fb;
        border-radius: 6px;
        padding: 0.45rem 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 0.15rem;
    }

    .breadcrumb-item {
        font-size: 0.84rem;
    }

    .breadcrumb-item a {
        color: #1e3a8a;
        text-decoration: none;
        font-weight: 600;
    }

    .breadcrumb-item.active {
        color: #64748b;
        font-weight: 600;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: #94a3b8;
    }

    .pagination {
        gap: 0.35rem;
        flex-wrap: wrap;
    }

    .pagination .page-item .page-link {
        border-radius: 6px !important;
        border: 1px solid #d7e3f7;
        color: #1e3a8a;
        min-width: 34px;
        text-align: center;
        font-weight: 600;
        background: #fff;
    }

    .pagination .page-item.active .page-link {
        background: #1e3a8a;
        border-color: #1e3a8a;
        color: #fff;
    }

    .pagination .page-item .page-link:hover {
        background: #edf3ff;
        border-color: #c3d3f2;
        color: #132f74;
    }

    .user-details-modal {
        border-radius: 10px;
        overflow: hidden;
    }

    .user-details-header {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border-bottom: 1px solid #e5ecf8;
    }

    .user-details-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: #1e3a8a;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
    }

    .user-meta-tile,
    .user-stat-card {
        border: 1px solid #e5ecf8;
        border-radius: 8px;
        background: #fff;
        padding: 0.8rem 0.9rem;
    }

    .user-stat-card {
        background: #f8fbff;
    }

    .user-section-card {
        border: 1px solid #e5ecf8;
        border-radius: 8px;
        background: #fff;
        padding: 0.9rem;
    }

    .user-section-table thead th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0;
        color: #4b5563;
    }

    .dashboard-sidebar {
        width: 290px;
        transition: width 0.25s ease, transform 0.25s ease;
    }

    .dashboard-main {
        margin-left: 290px;
        transition: margin-left 0.25s ease;
    }

    .sidebar-toggle-btn {
        width: 40px;
        height: 40px;
        border: 1px solid #dbe4f3;
        background: #fff;
        color: #1e3a8a;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.6rem;
        font-size: 1.1rem;
    }

    .sidebar-toggle-btn:hover {
        background: #eef4ff;
        border-color: #cfdcf7;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-direction: row;
    }

    .topbar-back-btn,
    .app-back-btn {
        height: 40px;
        min-width: 40px;
        border: 1px solid #dbe4f3;
        background: #fff;
        color: #1e3a8a;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-weight: 700;
        gap: 0.35rem;
        padding: 0 0.8rem;
    }

    .topbar-back-btn:hover,
    .app-back-btn:hover {
        background: #eef4ff;
        border-color: #cfdcf7;
        color: #1e3a8a;
    }

    .topbar-back-btn {
        width: 40px;
        padding: 0;
    }

    .topbar-heading {
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
    }

    .dropdown-menu {
        border-radius: 8px !important;
        border: 1px solid #dbe6fb !important;
        padding: 0.4rem;
        min-width: 220px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12) !important;
    }

    .dropdown-item {
        border-radius: 6px;
        font-weight: 600;
        color: #1e293b;
        padding: 0.5rem 0.65rem;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        background: #edf3ff;
        color: #1e3a8a;
    }

    .dropdown-divider {
        margin: 0.35rem 0;
        border-color: #e3ebfb;
    }

    .notifications-menu {
        min-width: 300px;
        max-width: 360px;
        padding: 0.45rem;
    }

    .notifications-menu .dropdown-item {
        white-space: normal;
        align-items: flex-start;
    }

    .sidebar-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.38);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
        z-index: 1035;
    }

    .sidebar-brand {
        text-align: center;
        margin-bottom: 1.35rem;
    }

    .sidebar-logo {
        flex-direction: column;
        justify-content: center;
        gap: 0.75rem;
    }

    .sidebar-logo-img-lg {
        /* width: 120px !important; */
        height: 70px !important;
        border-radius: 12%;
        background: #ecebeb !important;
        border: 1px solid rgba(30, 58, 138, 0.12);
        /* box-shadow: 0 14px 32px rgba(0, 0, 0, 0.18); */
        padding: 3px;
    /* object-fit: contain; */
    /* display: block; */

    }

    .sidebar-logo-text {
        align-items: center;
    }

    .sidebar-logo-title {
        font-size: 1.02rem !important;
        font-weight: 800 !important;
    }

    .sidebar-logo-subtitle {
        font-size: 0.8rem !important;
        color: rgba(255, 255, 255, 0.82) !important;
    }

    .sidebar-tag {
        margin-left: auto;
        margin-right: auto;
    }

    .jobs-filter-card {
        padding: 1rem 1rem 1.1rem;
    }

    .jobs-filter-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #e8f0ff;
        color: #1e3a8a;
        flex: 0 0 34px;
    }

    .jobs-filter-form {
        display: grid;
        grid-template-columns: minmax(170px, 1fr) minmax(170px, 1fr) minmax(280px, 2fr) minmax(180px, 220px);
        gap: 0.75rem;
        align-items: end;
    }

    .jobs-filter-search {
        min-width: 0;
    }

    .jobs-filter-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.55rem;
    }

    .evaluation-modal {
        border-radius: 10px;
        overflow: hidden;
    }

    .evaluation-modal .modal-header {
        border-bottom: 1px solid #e7eefb;
        background: #f8fbff;
    }

    .evaluation-modal .modal-footer {
        border-top: 1px solid #e7eefb;
        background: #fcfdff;
    }

    .messages-shell {
        display: grid;
        grid-template-columns: 320px minmax(0, 1fr);
        gap: 1rem;
        align-items: stretch;
    }

    .messages-threads,
    .messages-chat {
        min-height: 660px;
    }

    .messages-thread-list {
        max-height: 595px;
        overflow-y: auto;
    }

    .messages-thread-list .list-group-item {
        border: 0;
        border-bottom: 1px solid #edf2fb;
        padding: 0.8rem 0.9rem;
        background: #fff;
    }

    .messages-thread-list .list-group-item:hover {
        background: #f8fbff;
    }

    .messages-thread-list .list-group-item.active {
        background: #eef4ff;
        color: #0f172a;
        border-color: #dce8ff;
    }

    .thread-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: #e9f0ff;
        color: #1e3a8a;
        flex: 0 0 34px;
    }

    .thread-title {
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .thread-time,
    .thread-preview {
        color: #64748b;
    }

    .thread-preview {
        display: block;
        line-height: 1.3;
    }

    .message-thread {
        min-height: 460px;
        max-height: 530px;
        overflow-y: auto;
        padding-right: 0.15rem;
    }

    .message-row {
        display: flex;
        margin-bottom: 0.75rem;
    }

    .message-bubble {
        max-width: min(80%, 640px);
        border-radius: 14px;
        padding: 0.78rem 0.95rem;
        border: 1px solid transparent;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
    }

    .message-sent {
        background: linear-gradient(135deg, #1e3a8a 0%, #1f4cb6 100%);
        color: #fff;
    }

    .message-received {
        background: #f8fbff;
        color: #0f172a;
        border-color: #e3ecfb;
    }

    .message-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.65rem;
        font-size: 0.73rem;
        opacity: 0.88;
        margin-bottom: 0.28rem;
    }

    .message-sender {
        font-weight: 700;
    }

    .message-text {
        line-height: 1.45;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .message-compose {
        border-top: 1px solid #e8eef9;
        padding-top: 0.85rem;
    }

    .message-reply .input-group-text {
        border-radius: 12px 0 0 12px;
    }

    .message-reply .form-control {
        border-radius: 0;
    }

    .message-reply .btn {
        border-radius: 0 12px 12px 0;
        min-width: 114px;
    }

    .floating-chat-widget {
        position: fixed;
        right: 1.2rem;
        bottom: 1.2rem;
        z-index: 1040;
    }

    .floating-chat-trigger {
        width: 56px;
        height: 56px;
        border-radius: 50% !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 16px 34px rgba(30, 58, 138, 0.32);
    }

    .floating-chat-menu {
        width: min(300px, calc(100vw - 2rem));
        border-radius: 10px !important;
        border: 1px solid #dbe6fb !important;
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.14) !important;
    }

    .floating-chat-head {
        padding: 0.8rem 0.9rem 0.7rem;
        border-bottom: 1px solid #edf2fb;
        background: #f8fbff;
    }

    .floating-chat-head small {
        color: #64748b;
    }

    .floating-chat-body {
        padding: 0.45rem;
        display: grid;
        gap: 0.28rem;
    }

    .floating-chat-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        text-decoration: none;
        color: #1e293b;
        font-weight: 600;
        padding: 0.58rem 0.62rem;
        border-radius: 8px;
    }

    .floating-chat-item:hover {
        background: #edf3ff;
        color: #1e3a8a;
    }

    .floating-chat-item-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: #e9f0ff;
        color: #1e3a8a;
        flex: 0 0 28px;
    }

    .dashboard-chart-wrap {
        min-height: 300px;
    }

    .dashboard-chart-wrap-sm {
        min-height: 240px;
    }

    .stat-card-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 0.25rem;
    }

    .stat-card-center .dashboard-stat-icon {
        margin-bottom: 0.5rem;
    }

    body.sidebar-collapsed .dashboard-sidebar {
        width: 96px;
    }

    body.sidebar-collapsed .dashboard-main {
        margin-left: 96px;
    }

    body.sidebar-collapsed .sidebar-logo {
        gap: 0.35rem;
    }

    body.sidebar-collapsed .sidebar-logo-text,
    body.sidebar-collapsed .sidebar-tag,
    body.sidebar-collapsed .sidebar-item span,
    body.sidebar-collapsed .sidebar-logout span {
        display: none;
    }

    body.sidebar-collapsed .sidebar-item,
    body.sidebar-collapsed .sidebar-logout {
        justify-content: center;
        padding-left: 0.7rem;
        padding-right: 0.7rem;
    }

    body.sidebar-collapsed .sidebar-logo-img-lg {
        width: 56px !important;
        height: 56px !important;
        padding: 7px;
        border-radius: 14px !important;
    }

    @media (max-width: 1199.98px) {
        .dashboard-sidebar {
            width: 260px;
        }

        .dashboard-main {
            margin-left: 260px;
        }
    }

    @media (max-width: 991.98px) {
        .dashboard-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: min(86vw, 320px);
            max-width: 320px;
            height: 100vh;
            transform: translateX(-100%);
            z-index: 1045;
        }

        body.sidebar-open .dashboard-sidebar {
            transform: translateX(0);
        }

        body.sidebar-open .sidebar-backdrop {
            opacity: 1;
            visibility: visible;
        }

        .dashboard-main {
            margin-left: 0 !important;
        }

        .sidebar-logo {
            flex-direction: row;
            justify-content: flex-start;
        }

        .sidebar-logo-text {
            align-items: flex-start;
        }

        .sidebar-logo-img-lg {
            width: 82px !important;
            height: 82px !important;
            border-radius: 16px !important;
            padding: 10px;
        }

        .dashboard-content-area {
            padding: 1rem;
        }

        .topbar-left {
            width: 100%;
            flex-wrap: wrap;
            gap: 0.45rem;
            flex-direction: row;
        }

        .topbar-heading {
            width: calc(100% - 48px);
        }

        .topbar-subtitle {
            width: 100%;
        }

        .topbar-right {
            width: 100%;
            justify-content: flex-end;
        }

        .jobs-filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .jobs-filter-search,
        .jobs-filter-actions {
            grid-column: 1 / -1;
        }

        .messages-shell {
            grid-template-columns: 1fr;
        }

        .messages-threads,
        .messages-chat {
            min-height: auto;
        }

        .messages-thread-list,
        .message-thread {
            max-height: none;
        }
    }

    @media (max-width: 575.98px) {
        .dashboard-content-area {
            padding: 0.8rem;
        }

        .dashboard-stat-value {
            font-size: 1.45rem;
        }

        .dashboard-chart-wrap {
            min-height: 240px;
        }

        .dashboard-chart-wrap-sm {
            min-height: 210px;
        }

        .app-back-btn {
            width: 100%;
            justify-content: center;
        }

        .jobs-filter-form {
            grid-template-columns: 1fr;
        }

        .floating-chat-widget {
            right: 0.8rem;
            bottom: 0.8rem;
        }

        .floating-chat-trigger {
            width: 50px;
            height: 50px;
        }
    }
</style>
