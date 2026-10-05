@php
    $speakerCount = $conference?->speakers?->count() ?? 0;
    $topicCount = $conference?->topics?->count() ?? 0;
    $registrationCount = $conference?->registrationTypes?->count() ?? 0;

    $conferenceDate = null;
    if ($conference?->start_date) {
        $conferenceDate = $conference->start_date->translatedFormat('d M Y');
        if ($conference->end_date && $conference->end_date->ne($conference->start_date)) {
            $conferenceDate .= ' – ' . $conference->end_date->translatedFormat('d M Y');
        }
    }
@endphp

<section id="facts" class="section bg-image-1 facts text-center">
    <div class="container">
        <div class="row gy-5">
            <div class="col-sm-3">
                <i class="bi bi-calendar3" aria-hidden="true"></i>
                <h3>{{ $conferenceDate ?: 'TBA' }}<br><span>Conference Date</span></h3>
            </div>
            <div class="col-sm-3">
                <i class="bi bi-diagram-3" aria-hidden="true"></i>
                <h3>{{ $topicCount }}<br><span>Research Tracks</span></h3>
            </div>
            <div class="col-sm-3">
                <i class="bi bi-person-video3" aria-hidden="true"></i>
                <h3>{{ $speakerCount }}<br><span>Speakers</span></h3>
            </div>
            <div class="col-sm-3">
                <i class="bi bi-card-checklist" aria-hidden="true"></i>
                <h3>{{ $registrationCount }}<br><span>Registration Types</span></h3>
            </div>
        </div>
    </div>
</section>
