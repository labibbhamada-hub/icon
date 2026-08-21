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
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.conferences.*') ? 'active' : '' }} rounded-0">
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
                <li
                    class="nav-item {{ request()->routeIs('admin.topics.*') || request()->routeIs('admin.speakers.*') || request()->routeIs('admin.partners.*') || request()->routeIs('admin.important-dates.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.topics.*') || request()->routeIs('admin.speakers.*') || request()->routeIs('admin.partners.*') || request()->routeIs('admin.important-dates.*') ? 'active' : '' }} rounded-0">
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
                            <a href="{{ route('admin.speakers.index') }}"
                                class="nav-link {{ request()->routeIs('admin.speakers.*') ? 'active' : '' }} rounded-0">
                                <i class="nav-icon bi bi-mic"></i>
                                <p>
                                    Speakers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.partners.index') }}"
                                class="nav-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }} rounded-0">
                                <i class="nav-icon bi bi-buildings"></i>
                                <p>
                                    Partners
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.important-dates.index') }}"
                                class="nav-link {{ request()->routeIs('admin.important-dates.*') ? 'active' : '' }} rounded-0">
                                <i class="nav-icon bi bi-calendar-week"></i>
                                <p>
                                    Important Dates
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Submission --}}
                <li class="nav-header">
                    SUBMISSION MANAGEMENT
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.participants.index') }}"
                        class="nav-link {{ request()->routeIs('admin.participants.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-people"></i>
                        <p>
                            Participants
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.submissions.index') }}"
                        class="nav-link {{ request()->routeIs('admin.submissions.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>
                            Submissions
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.payments.index') }}"
                        class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-credit-card"></i>
                        <p>Payments</p>
                    </a>
                </li>
                <li
                    class="nav-item {{ request()->routeIs('admin.reviewers.*') || request()->routeIs('admin.reviews.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.reviewers.*') || request()->routeIs('admin.reviews.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>
                            Review Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.reviewers.index') }}"
                                class="nav-link {{ request()->routeIs('admin.reviewers.*') ? 'active' : '' }} rounded-0">
                                <i class="nav-icon bi bi-person-check"></i>
                                <p>
                                    Reviewers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reviews.index') }}"
                                class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }} rounded-0">
                                <i class="nav-icon bi bi-file-earmark-check"></i>
                                <p>Reviews</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.certificates.index') }}"
                        class="nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-award"></i>
                        <p>Certificates</p>
                    </a>
                </li>
                <li class="nav-header">
                    SYSTEM
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} rounded-0">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
