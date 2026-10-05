<header class="site-header">

    @php
        $conferenceShortName = $conference?->short_name ?? 'ICON';
        $conferenceYear = $conference?->year ?? date('Y');
        $conferenceLogo = $conference?->logo
            ? asset('storage/' . $conference->logo)
            : asset('assets/images/logo/logo-bhamada.png');
    @endphp

    <nav id="site-nav" class="navbar navbar-expand-lg site-navbar">
        <div class="container">

            <a class="navbar-brand" href="{{ url('/') }}" aria-label="{{ $conferenceShortName }} {{ $conferenceYear }}">
                <img src="{{ $conferenceLogo }}" alt="{{ $conferenceShortName }}" class="navbar-logo">

                <div class="navbar-brand-text">
                    <span class="logo-title">
                        {{ $conferenceShortName }} {{ $conferenceYear }}
                    </span>
                    <span class="logo-subtitle">
                        International Conference
                    </span>
                </div>
            </a>

            <button class="navbar-toggler rounded-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="#about" class="nav-link active">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="#topics" class="nav-link">Topics</a>
                    </li>
                    <li class="nav-item">
                        <a href="#speakers" class="nav-link">Speakers</a>
                    </li>
                    <li class="nav-item">
                        <a href="#dates" class="nav-link">Important Dates</a>
                    </li>
                    <li class="nav-item">
                        <a href="#paper" class="nav-link">Call for Papers</a>
                    </li>
                    <li class="nav-item">
                        <a href="#registration" class="nav-link">Registration</a>
                    </li>
                    <li class="nav-item">
                        <a href="#sponsors" class="nav-link">Partners</a>
                    </li>
                    <li class="nav-item">
                        <a href="#contact" class="nav-link">Contact</a>
                    </li>
                </ul>

                <div class="navbar-actions">
                    <a href="{{ route('login') }}" class="btn btn-login rounded-0">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-register rounded-0">
                        Register
                    </a>
                </div>
            </div>

        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.site-navbar .nav-link');

        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                navLinks.forEach(function(navLink) {
                    navLink.classList.remove('active');
                });

                this.classList.add('active');

                const collapseElement = document.getElementById('navbarMenu');
                if (window.innerWidth < 992 && collapseElement.classList.contains('show')) {
                    const collapse = bootstrap.Collapse.getOrCreateInstance(collapseElement);
                    collapse.hide();
                }
            });
        });
    });
</script>
