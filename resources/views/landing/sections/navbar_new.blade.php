@php
    $conferenceShortName = $conference?->short_name ?? 'BHAMADA ICON';
    $conferenceYear = $conference?->year ?? date('Y');
    $conferenceLogo = $conference?->logo
        ? asset('storage/' . $conference->logo)
        : asset('assets/images/logo/logo-bhamada.png');
@endphp

<nav id="site-nav" class="navbar navbar-expand-lg navbar-dark site-nav">
    <div class="container">
        <a class="navbar-brand site-branding" href="{{ url('/') }}">
            <img src="{{ $conferenceLogo }}" alt="{{ $conferenceShortName }}" class="site-logo">
            <span class="site-brand-text">
                {{ $conferenceShortName }} {{ $conferenceYear }}
            </span>
        </a>

        <button class="navbar-toggler rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-items"
            aria-controls="navbar-items" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar-items">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a data-scroll href="#about" class="nav-link active">About</a></li>
                <li class="nav-item"><a data-scroll href="#topics" class="nav-link">Topics</a></li>
                <li class="nav-item"><a data-scroll href="#speakers" class="nav-link">Speakers</a></li>
                <li class="nav-item"><a data-scroll href="#schedule" class="nav-link">Schedule</a></li>
                <li class="nav-item"><a data-scroll href="#registration" class="nav-link">Registration</a></li>
                <li class="nav-item"><a data-scroll href="#partner" class="nav-link">Partner</a></li>
                <li class="nav-item"><a data-scroll href="#faq" class="nav-link">FAQ</a></li>
                <li class="nav-item"><a data-scroll href="#contact" class="nav-link">Contact</a></li>
            </ul>

            <div class="site-nav-actions">
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
