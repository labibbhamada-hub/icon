<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">

        {{-- ============================================================
            LEFT
        ============================================================= --}}
        <ul class="navbar-nav align-items-center">

            {{-- Sidebar Toggle --}}
            <li class="nav-item">
                <a class="nav-link rounded-0" data-lte-toggle="sidebar" href="#" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </a>
            </li>

            {{-- Current Conference --}}
            @if ($activeConference)
                <li class="nav-item d-none d-lg-flex align-items-center ms-2">
                    <span class="navbar-text small text-muted">
                        Current Conference:
                    </span>

                    <span class="fw-semibold ms-1">
                        {{ $activeConference->name }}
                    </span>

                    <span class="text-muted ms-1">
                        ({{ $activeConference->year }})
                    </span>
                </li>
            @else
                <li class="nav-item d-none d-lg-flex align-items-center ms-2">
                    <span class="navbar-text text-muted small">
                        No active conference
                    </span>
                </li>
            @endif

        </ul>


        {{-- ============================================================
            RIGHT
        ============================================================= --}}
        <ul class="navbar-nav ms-auto align-items-center">

            {{-- Theme --}}
            <li class="nav-item dropdown">

                <a class="nav-link rounded-0" href="#" id="bd-theme" aria-label="Toggle color scheme"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>

                    <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>

                    <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end rounded-0" aria-labelledby="bd-theme"
                    style="--bs-dropdown-min-width: 8rem">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="light" aria-pressed="false">
                            <i class="bi bi-sun-fill me-2"></i>
                            Light
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>

                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="dark" aria-pressed="false">
                            <i class="bi bi-moon-fill me-2"></i>
                            Dark
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>

                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active"
                            data-bs-theme-value="auto" aria-pressed="true">
                            <i class="bi bi-circle-half me-2"></i>
                            Auto
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                </ul>

            </li>


            {{-- User --}}
            <li class="nav-item dropdown user-menu">

                <a href="#" class="nav-link dropdown-toggle rounded-0" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}"
                        class="user-image rounded-circle shadow" alt="{{ auth()->user()->name }}">

                    <span class="d-none d-md-inline">
                        {{ auth()->user()->name }}
                    </span>
                </a>


                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end rounded-0">

                    {{-- User Header --}}
                    <li class="user-header text-bg-primary text-center">

                        <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" class="rounded-circle shadow"
                            alt="{{ auth()->user()->name }}">

                        <p class="mb-0 mt-2">
                            {{ auth()->user()->name }}
                        </p>

                        <small>
                            {{ ucfirst(auth()->user()->role) }}
                        </small>

                    </li>


                    {{-- User Information --}}
                    <li class="p-3 border-bottom">

                        <div class="d-flex align-items-center">

                            <i class="bi bi-envelope me-2 text-muted"></i>

                            <div>
                                <div class="small text-muted">
                                    Email
                                </div>

                                <div class="fw-semibold text-break">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>

                        </div>

                    </li>


                    {{-- Footer --}}
                    <li class="user-footer">

                        <form action="{{ route('logout') }}" method="POST" class="d-flex justify-content-end">
                            @csrf

                            <button type="submit" class="btn btn-outline-danger rounded-0">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Logout
                            </button>

                        </form>

                    </li>

                </ul>

            </li>

        </ul>

    </div>
</nav>
