<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    {{-- ============================================================
        BRAND
    ============================================================= --}}
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" class="brand-image opacity-75 shadow"
                alt="Bhamada ICON">

            <span class="brand-text fw-bold">
                BHAMADA ICON
            </span>
        </a>
    </div>

    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                {{-- ========================================================
                    MAIN
                ========================================================= --}}
                <li class="nav-header">
                    MAIN
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link rounded-0
                            {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                {{-- ========================================================
                    CONFERENCE MANAGEMENT
                ========================================================= --}}
                @php
                    $conferenceMenuOpen =
                        request()->routeIs('admin.conferences.*') ||
                        request()->routeIs('admin.conference-online-meetings.*') ||
                        request()->routeIs('admin.conference-whatsapp-groups.*');
                @endphp

                <li class="nav-header">
                    CONFERENCE MANAGEMENT
                </li>

                <li class="nav-item
                        {{ $conferenceMenuOpen ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link rounded-0
                            {{ $conferenceMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-event"></i>

                        <p>
                            Conference
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- Conference List --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.conferences.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.conferences.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>Conference List</p>
                            </a>
                        </li>

                        {{-- Create Conference --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.conferences.create') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.conferences.create') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-plus-circle"></i>
                                <p>Create Conference</p>
                            </a>
                        </li>

                        {{-- Configuration --}}
                        @if ($activeConference)
                            <li class="nav-item">
                                <a href="{{ route('admin.conferences.configuration.edit', $activeConference) }}"
                                    class="nav-link rounded-0
                                        {{ request()->routeIs('admin.conferences.configuration.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-palette"></i>
                                    <p>Configuration</p>
                                </a>
                            </li>

                            {{-- Settings --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.conferences.settings.edit', $activeConference) }}"
                                    class="nav-link rounded-0
                                        {{ request()->routeIs('admin.conferences.settings.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-sliders"></i>
                                    <p>Settings</p>
                                </a>
                            </li>

                            {{-- Online Meetings --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.conference-online-meetings.index') }}"
                                    class="nav-link rounded-0
                                        {{ request()->routeIs('admin.conference-online-meetings.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-camera-video"></i>
                                    <p>Online Meetings</p>
                                </a>
                            </li>

                            {{-- WhatsApp Groups --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.conference-whatsapp-groups.index') }}"
                                    class="nav-link rounded-0 {{ request()->routeIs('admin.conference-whatsapp-groups.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-whatsapp"></i>
                                    <p>WhatsApp Groups</p>
                                </a>
                            </li>

                            {{-- Payment Methods --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.conferences.payment-methods.index', $activeConference) }}"
                                    class="nav-link rounded-0
                                        {{ request()->routeIs('admin.conferences.payment-methods.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-bank"></i>
                                    <p>Payment Methods</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>


                {{-- ========================================================
                    CONTENT MANAGEMENT
                ========================================================= --}}
                @php
                    $contentMenuOpen =
                        request()->routeIs('admin.topics.*') ||
                        request()->routeIs('admin.speakers.*') ||
                        request()->routeIs('admin.partners.*') ||
                        request()->routeIs('admin.important-dates.*');
                @endphp

                <li class="nav-header">
                    CONTENT MANAGEMENT
                </li>

                <li class="nav-item
                        {{ $contentMenuOpen ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link rounded-0
                            {{ $contentMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon bi bi-collection"></i>

                        <p>
                            Content
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('admin.topics.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.topics.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-diagram-3"></i>
                                <p>Topics</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.speakers.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.speakers.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-mic"></i>
                                <p>Speakers</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.partners.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-buildings"></i>
                                <p>Partners</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.important-dates.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.important-dates.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-calendar-week"></i>
                                <p>Important Dates</p>
                            </a>
                        </li>

                    </ul>
                </li>


                {{-- ========================================================
                    REGISTRATION MANAGEMENT
                ========================================================= --}}
                @php
                    $registrationMenuOpen =
                        request()->routeIs('admin.participants.*') || request()->routeIs('admin.registration-types.*');
                @endphp

                <li class="nav-header">
                    REGISTRATION MANAGEMENT
                </li>

                <li class="nav-item
                        {{ $registrationMenuOpen ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link rounded-0
                            {{ $registrationMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-vcard"></i>

                        <p>
                            Registration
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('admin.participants.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Participants</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.registration-types.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.registration-types.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-tags"></i>
                                <p>Registration Types</p>
                            </a>
                        </li>

                    </ul>
                </li>


                {{-- ========================================================
                    SUBMISSION MANAGEMENT
                ========================================================= --}}
                <li class="nav-header">
                    SUBMISSION MANAGEMENT
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.submissions.index') }}"
                        class="nav-link rounded-0
                            {{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>Submissions</p>
                    </a>
                </li>


                {{-- ========================================================
                    REVIEW MANAGEMENT
                ========================================================= --}}
                @php
                    $reviewMenuOpen = request()->routeIs('admin.reviewers.*') || request()->routeIs('admin.reviews.*');
                @endphp

                <li class="nav-header">
                    REVIEW MANAGEMENT
                </li>

                <li class="nav-item
                        {{ $reviewMenuOpen ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link rounded-0
                            {{ $reviewMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard-check"></i>

                        <p>
                            Review
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('admin.reviewers.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.reviewers.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person-check"></i>
                                <p>Reviewers</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reviews.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-file-earmark-check"></i>
                                <p>Reviews</p>
                            </a>
                        </li>

                    </ul>
                </li>


                {{-- ========================================================
                    PAYMENT MANAGEMENT
                ========================================================= --}}
                @php
                    $paymentMenuOpen =
                        request()->routeIs('admin.payments.*') ||
                        request()->routeIs('admin.conferences.payment-methods.*');
                @endphp

                <li class="nav-header">
                    PAYMENT MANAGEMENT
                </li>

                <li class="nav-item
                    {{ $paymentMenuOpen ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link rounded-0
                            {{ $paymentMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon bi bi-credit-card"></i>

                        <p>
                            Payment
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('admin.payments.index') }}"
                                class="nav-link rounded-0
                                    {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-wallet2"></i>
                                <p>Payments</p>
                            </a>
                        </li>

                        @if ($activeConference)
                            <li class="nav-item">
                                <a href="{{ route('admin.conferences.payment-methods.index', $activeConference) }}"
                                    class="nav-link rounded-0
                                        {{ request()->routeIs('admin.conferences.payment-methods.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-bank"></i>
                                    <p>Payment Methods</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>


                {{-- ========================================================
                    CERTIFICATE
                ========================================================= --}}
                <li class="nav-header">
                    CERTIFICATE
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.certificates.index') }}"
                        class="nav-link rounded-0
                            {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-award"></i>
                        <p>Certificates</p>
                    </a>
                </li>


                {{-- ========================================================
                    REPORTS
                ========================================================= --}}
                <li class="nav-header">
                    REPORTS
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}"
                        class="nav-link rounded-0
                            {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-line"></i>
                        <p>Reports</p>
                    </a>
                </li>


                {{-- ========================================================
                    SYSTEM
                ========================================================= --}}
                <li class="nav-header">
                    SYSTEM
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link rounded-0
                            {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Users</p>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</aside>
