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
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-person-circle fs-5"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end rounded-0">
                    <li>
                        <span class="dropdown-item-text">
                            {{ auth()->user()->name }}
                        </span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

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
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}"
                        class="user-image rounded-circle shadow" alt="{{ auth()->user()->name }}" />
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
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
                            <a href="#" class="btn btn-outline-secondary rounded-0">Profile</a>
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
