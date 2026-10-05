@php
    $conferenceShortName = $conference?->short_name ?? 'BHAMADA ICON';
    $conferenceYear = $conference?->year ?? 2026;
    $conferenceName = $conference?->name ?? 'Bhamada International Conference on Interdisciplinary Innovation';
    $conferenceLogo = $conference?->logo
        ? asset('storage/' . $conference->logo)
        : asset('assets/images/logo/logo-bhamada.png');
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <a href="{{ url('/') }}" class="footer-brand">
                    <img src="{{ $conferenceLogo }}" alt="{{ $conferenceShortName }}" class="footer-logo">
                    <span>{{ $conferenceShortName }} {{ $conferenceYear }}</span>
                </a>

                <p class="site-info">
                    {{ $conferenceName }}<br>
                    Universitas Bhamada Slawi
                </p>

                <p class="site-copy">&copy; {{ $conferenceYear }} {{ $conferenceShortName }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
