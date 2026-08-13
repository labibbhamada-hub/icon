<aside class="app-sidebar shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('reviewer.dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON Logo" class="brand-image">
            <div class="brand-text">
                <span class="brand-text fw-semibold">
                    ICON 2026
                </span>
                <small class="d-block text-secondary">
                    Reviewer Panel
                </small>
            </div>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-3">
            <ul class="nav sidebar-menu flex-column" role="menu">
                <li class="nav-item">
                    <a href="{{ route('reviewer.dashboard') }}"
                        class="nav-link {{ request()->routeIs('reviewer.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">
                    REVIEW
                </li>
                <li class="nav-item">
                    <a href="{{ route('reviewer.reviews.index') }}"
                        class="nav-link {{ request()->routeIs('reviewer.reviews.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>
                            My Reviews
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" class="brand-image opacity-75 shadow">
            <span class="brand-text fw-bold">BHAMADA ICON</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('reviewer.dashboard') }}"
                        class="nav-link {{ request()->routeIs('reviewer.dashboard') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-header">REVIEW</li>
                <li class="nav-item">
                    <a href="{{ route('reviewer.reviews.index') }}"
                        class="nav-link {{ request()->routeIs('reviewer.reviews.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>My Reviews</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
