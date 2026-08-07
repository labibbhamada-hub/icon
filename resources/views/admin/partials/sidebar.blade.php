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
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                {{-- Conference --}}
                <li class="nav-header">
                    CONFERENCE MANAGEMENT
                </li>
                <li class="nav-item {{ request()->routeIs('admin.conferences.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.conferences.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-calendar-event"></i>
                        <p>
                            Conference
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.conferences.index') }}"
                                class="nav-link {{ request()->routeIs('admin.conferences.index') ? 'active' : '' }} rounded-0">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Conference List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.conferences.create') }}"
                                class="nav-link {{ request()->routeIs('admin.conferences.create') ? 'active' : '' }} rounded-0">
                                <i class="nav-icon bi bi-plus-circle"></i>
                                <p>Create Conference</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Master Data --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-database"></i>
                        <p>
                            Master Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.topics.index') }}"
                                class="nav-link {{ request()->routeIs('admin.topics.*') ? 'active' : '' }} rounded-0">
                                <i class="nav-icon bi bi-diagram-3"></i>
                                <p>
                                    Topics
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-mic"></i>
                                <p>Speakers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-buildings"></i>
                                <p>Partners</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-calendar-week"></i>
                                <p>Important Dates</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Submission --}}
                <li class="nav-header">
                    SUBMISSION MANAGEMENT
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Participants</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>Submissions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>Reviews</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-credit-card"></i>
                        <p>Payments</p>
                    </a>
                </li>
                {{-- System --}}
                <li class="nav-header">
                    SYSTEM
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
