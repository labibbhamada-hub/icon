<!--======================================
    HOME
======================================-->

@php
    $conferenceName = $conference?->name ?? 'BHAMADA ICON';
    $conferenceShortName = $conference?->short_name ?? 'ICON';
    $conferenceYear = $conference?->year ?? 2026;
    $conferenceTheme = $conference?->theme ??
        'Advancing Interdisciplinary Research and Innovation for Sustainable Development';

    $conferenceDate = null;

    if ($conference?->start_date) {
        $conferenceDate = $conference->start_date->translatedFormat('d F Y');

        if ($conference->end_date && $conference->end_date->ne($conference->start_date)) {
            $conferenceDate .= ' – ' . $conference->end_date->translatedFormat('d F Y');
        }
    }

    $conferenceLocation = collect([
        $conference?->venue,
        $conference?->city,
        $conference?->country,
    ])
        ->filter()
        ->implode(' — ');

    $conferenceDateLabel = $conferenceDate ?: $conferenceYear;
    $conferenceLocationLabel = $conferenceLocation ?: 'Online Conference';
@endphp

<section class="hero site-header" id="home">
    <div class="hero-overlay" aria-hidden="true"></div>

    <div class="intro">
        <div class="container">
            <div class="hero-content">
                <h2>
                    {{ $conferenceDateLabel }}
                    @if ($conferenceLocationLabel)
                        <span class="hero-separator">/</span>
                        {{ $conferenceLocationLabel }}
                    @endif
                </h2>

                <h1>
                    {{ $conferenceName }} {{ $conferenceYear }}
                </h1>

                <p>
                    {{ $conferenceTheme }}
                </p>

                <div class="hero-actions">
                    <a class="btn btn-white rounded-0" href="{{ route('register') }}">
                        Register Now
                    </a>

                    <a class="btn btn-outline-light rounded-0" href="{{ route('login') }}">
                        Login
                    </a>
                </div>

                <div class="hero-organizer">
                    <span>Organized by</span>
                    <strong>Universitas Bhamada Slawi</strong>
                </div>
            </div>
        </div>
    </div>
</section>
