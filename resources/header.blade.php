<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('logo.png') }}" alt="Career Insights" class="me-2" width="40" height="40">
            <span class="fw-bold text-primary fs-5">Career Insights</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('jobs.index') }}">Browse Jobs</a></li>

                @if($currentAdmin)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ $currentAdmin->department ?? 'Admin' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">Logout</button></li>
                        </ul>
                    </li>
                @elseif($currentUser)
                    @php($userDashboardRoute = ($currentUser->user_type ?? 'graduate') === 'professional' ? route('employee.dashboard') : route('dashboard'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ $currentUser->full_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ $userDashboardRoute }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">Logout</button></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-2" href="{{ route('register') }}">Register</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
