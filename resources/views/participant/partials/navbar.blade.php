<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            {{-- Notification --}}
            @php
                $unreadCount = auth()->user()->unreadNotifications()->count();
                $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get();
            @endphp

            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#"
                    aria-label="Notifications: {{ $unreadCount }} unread">
                    <i class="bi bi-bell-fill"></i>

                    @if ($unreadCount > 0)
                        <span class="navbar-badge badge text-bg-warning rounded-0">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end rounded-0 overflow-hidden">
                    <span class="dropdown-item dropdown-header">
                        {{ $unreadCount }} {{ $unreadCount === 1 ? 'Notification' : 'Notifications' }}
                    </span>

                    @forelse ($recentNotifications as $notification)
                        @php
                            $data = $notification->data;
                            $type = $data['type'] ?? 'info';
                            $icon = match ($type) {
                                'success' => 'bi-check-circle',
                                'warning' => 'bi-exclamation-circle',
                                'danger' => 'bi-x-circle',
                                default => 'bi-info-circle',
                            };
                            $iconColor = match ($type) {
                                'success' => 'text-success',
                                'warning' => 'text-warning',
                                'danger' => 'text-danger',
                                default => 'text-info',
                            };
                        @endphp

                        <div class="dropdown-divider"></div>

                        <a href="{{ route('participant.notifications.show', $notification->id) }}"
                            class="dropdown-item d-flex align-items-center gap-2 {{ $notification->read_at ? '' : 'fw-semibold' }}">
                            <i class="bi {{ $icon }} {{ $iconColor }}"></i>

                            <span class="text-truncate flex-grow-1">
                                {{ $data['title'] ?? 'Notification' }}
                            </span>

                            <span class="text-secondary fs-7 text-nowrap">
                                {{ $notification->created_at?->diffForHumans() }}
                            </span>
                        </a>
                    @empty
                        <div class="dropdown-divider"></div>

                        <div class="dropdown-item text-center text-muted py-3">
                            <i class="bi bi-bell-slash d-block fs-4 mb-1"></i>
                            No notifications
                        </div>
                    @endforelse

                    <div class="dropdown-divider"></div>

                    <a href="{{ route('participant.notifications.index') }}" class="dropdown-item dropdown-footer">
                        See All Notifications
                    </a>
                </div>
            </li>

            {{-- User --}}
            <li class="nav-item dropdown user-menu">
                <a href="#" class="btn btn-outline-secondary rounded-0 d-flex align-items-center gap-1"
                    data-bs-toggle="dropdown" aria-expanded="false" title="Open user menu">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-md-inline">
                        {{ auth()->user()->name }}
                    </span>
                    <i class="bi bi-chevron-down ms-1"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end rounded-0 overflow-hidden">
                    <li class="user-header text-bg-primary">
                        <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" class="rounded-circle shadow"
                            alt="{{ auth()->user()->name }}" />

                        <p>
                            {{ auth()->user()->name }} - {{ ucfirst(auth()->user()->role) }}
                        </p>
                    </li>

                    <li class="user-footer">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <a href="#" class="btn btn-outline-secondary rounded-0">
                                Profile
                            </a>

                            <button class="btn btn-outline-danger rounded-0 float-end">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

</nav>
