@php
    $conferenceShortName = $conference?->short_name ?? 'BHAMADA ICON';
    $conferenceYear = $conference?->year ?? 2026;
    $conferenceTheme =
        $conference?->theme ?? 'Advancing Interdisciplinary Research and Innovation for Sustainable Development.';
@endphp

<section id="contribution" class="section bg-image-2 contribution-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-uppercase mt0 font-400">Submit Your Contribution</h3>
                <p>
                    Submit your research to {{ $conferenceShortName }} {{ $conferenceYear }} and share work aligned with
                    the conference theme: {{ $conferenceTheme }}
                </p>
                <a class="btn btn-white rounded-0" href="{{ route('register') }}">Submit Abstract</a>
            </div>
        </div>
    </div>
</section>
