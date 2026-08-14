<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('participant.dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" class="brand-image opacity-75 shadow">
            <span class="brand-text fw-bold">BHAMADA ICON</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('participant.dashboard') }}"
                        class="nav-link {{ request()->routeIs('participant.dashboard') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-header">PARTICIPANT</li>
                <li class="nav-item">
                    <a href="{{ route('participant.profile.edit') }}"
                        class="nav-link {{ request()->routeIs('participant.profile.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-person"></i>
                        <p>My Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('participant.registration.index') }}"
                        class="nav-link {{ request()->routeIs('participant.registration.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-calendar-check"></i>
                        <p>Registration</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('participant.payments.index') }}"
                        class="nav-link {{ request()->routeIs('participant.payments.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-credit-card"></i>
                        <p>Payments</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('participant.submissions.index') }}"
                        class="nav-link {{ request()->routeIs('participant.submissions.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>My Submissions</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
