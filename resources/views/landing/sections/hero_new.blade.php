@php
    $conferenceShortName = $conference?->short_name ?? 'BHAMADA ICON';
    $conferenceYear = $conference?->year ?? 2026;
    $conferenceTheme =
        $conference?->theme ?? 'Advancing Interdisciplinary Research and Innovation for Sustainable Development.';

    $conferenceDate = null;
    if ($conference?->start_date) {
        $conferenceDate = $conference->start_date->translatedFormat('d F Y');
        if ($conference->end_date && $conference->end_date->ne($conference->start_date)) {
            $conferenceDate .= ' – ' . $conference->end_date->translatedFormat('d F Y');
        }
    }
@endphp

<header id="site-header" class="site-header">
    <div class="intro">
        @if ($conferenceDate)
            <h2>{{ $conferenceDate }} / Online Conference</h2>
        @else
            <h2>Online Conference / Universitas Bhamada Slawi</h2>
        @endif

        <h1>{{ $conferenceShortName }} {{ $conferenceYear }}</h1>

        <p>{{ $conferenceTheme }}</p>

        <a class="btn btn-white rounded-0" data-scroll href="#registration">Register Now</a>
    </div>
</header>
