@php
    $conferenceShortName = $conference?->short_name ?? 'BHAMADA ICON';
    $conferenceYear = $conference?->year ?? 2026;
    $conferenceDate = null;
    if ($conference?->start_date) {
        $conferenceDate = $conference->start_date->translatedFormat('d F Y');
        if ($conference->end_date && $conference->end_date->ne($conference->start_date)) {
            $conferenceDate .= ' – ' . $conference->end_date->translatedFormat('d F Y');
        }
    }
    $meeting = $conference?->onlineMeeting;
@endphp

<section id="contact" class="section contact-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-sm-3">
                <h3 class="section-title">Conference Information</h3>
                <address>
                    <p class="mb-0">
                        {{ $conferenceShortName }} {{ $conferenceYear }}<br>
                        Online Conference<br>
                        Universitas Bhamada Slawi<br>
                        @if ($conferenceDate)
                            {{ $conferenceDate }}
                        @endif
                    </p>
                </address>
            </div>

            <div class="col-sm-9">
                <div class="online-panel">
                    <div>
                        <span class="online-label">Online Conference</span>
                        <h4>{{ $meeting?->title ?? 'Virtual Conference Platform' }}</h4>
                        <p>
                            Access to the conference meeting room is provided to eligible participants according to the
                            conference schedule and registration status.
                        </p>
                    </div>

                    @if ($meeting?->meeting_url)
                        <a href="{{ $meeting->meeting_url }}" class="btn btn-black rounded-0" target="_blank"
                            rel="noopener noreferrer">
                            Join Conference
                        </a>
                    @else
                        <span class="contact-note">Meeting link will be published by the conference committee.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
