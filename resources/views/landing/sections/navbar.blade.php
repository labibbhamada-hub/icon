<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="Logo Universitas Bhamada"
                    class="navbar-logo">
                <div class="ms-3">
                    <h5 class="logo-title mb-0">
                        Universitas Bhamada
                    </h5>
                    <small class="logo-subtitle">
                        International Conference
                    </small>
                </div>
            </a>
            <!-- Tombol Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a href="#home" class="nav-link active">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#about" class="nav-link">
                            About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#topics" class="nav-link">
                            Topics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#speakers" class="nav-link">
                            Speakers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#dates" class="nav-link">
                            Important Dates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#contact" class="nav-link">
                            Contact
                        </a>
                    </li>
                </ul>
                <!-- Action Button -->
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ url('login') }}" class="btn btn-login">
                        Login
                    </a>
                    <a href="#" class="btn btn-register">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
