<!--======================================
    FACTS / STATISTICS
=======================================-->

@php
    $conferenceYear = $conference?->year ?? date('Y');

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
        ->implode(', ');

    $conferenceTracks = $conference?->topics?->count() ?? 0;
    $conferenceSpeakers = $conference?->speakers?->count() ?? 0;
@endphp

<section class="facts-section" id="facts">

    <div class="facts-overlay"></div>

    <div class="container position-relative">

        <div class="row text-center">

            <div class="col-6 col-lg-3 facts-item">

                <i class="bi bi-calendar-event facts-icon"></i>

                <h3 class="facts-value">
                    {{ $conferenceDate ?? 'TBA' }}
                </h3>

                <span class="facts-label">
                    Conference Date
                </span>

            </div>


            <div class="col-6 col-lg-3 facts-item">

                <i class="bi bi-geo-alt facts-icon"></i>

                <h3 class="facts-value">
                    {{ $conferenceLocation ?: 'Online Conference' }}
                </h3>

                <span class="facts-label">
                    Location / Format
                </span>

            </div>


            <div class="col-6 col-lg-3 facts-item">

                <i class="bi bi-grid-3x3-gap facts-icon"></i>

                <h3 class="facts-value">
                    {{ $conferenceTracks }}
                </h3>

                <span class="facts-label">
                    Conference Tracks
                </span>

            </div>


            <div class="col-6 col-lg-3 facts-item">

                <i class="bi bi-mic facts-icon"></i>

                <h3 class="facts-value">
                    {{ $conferenceSpeakers }}
                </h3>

                <span class="facts-label">
                    Speakers
                </span>

            </div>

        </div>

    </div>

</section>
