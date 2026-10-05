<!--======================================
    ABOUT
=======================================-->

@php
    $conferenceTitle = $conference?->short_name ?? 'BHAMADA ICON';
    $conferenceYear = $conference?->year ?? 2026;

    $conferenceTheme =
        $conference?->theme ??
        'Advancing Interdisciplinary Research and Innovation for Sustainable Development.';

    $conferenceName =
        $conference?->name ??
        'Bhamada International Conference on Interdisciplinary Innovation';

    $conferenceLocation = collect([$conference?->city, $conference?->country])
        ->filter()
        ->implode(', ');
@endphp

<section class="about about-template" id="about">
    <div class="container">
        <div class="row align-items-start">

            {{-- About Us --}}
            <div class="col-md-6 about-column">

                <h3 class="about-section-title">
                    About Us
                </h3>

                <p class="about-lead">
                    {{ $conferenceName }} {{ $conferenceYear }} is an international scientific
                    forum organized by Universitas Bhamada Slawi.
                </p>

                <p class="about-copy">
                    {{ $conferenceTheme }}
                </p>

                <p class="about-copy">
                    BHAMADA ICON brings together academics, researchers, students,
                    practitioners, and professionals from various disciplines to present
                    research, exchange ideas, and strengthen interdisciplinary collaboration
                    for sustainable development.
                </p>

                <figure class="mb-0">
                    <img src="{{ asset('assets/images/landing/about-us.jpg') }}"
                        alt="About BHAMADA ICON {{ $conferenceYear }}"
                        class="img-fluid about-image rounded-0">
                </figure>

            </div>

            {{-- Conference Goal --}}
            <div class="col-md-6 about-column about-goal">

                <h3 class="about-section-title">
                    What is Our Goal?
                </h3>

                <p class="about-copy">
                    BHAMADA ICON {{ $conferenceYear }} is designed to strengthen research
                    and scientific publication, expand academic networks, and encourage
                    meaningful collaboration across higher education, research, industry,
                    government, and other partners.
                </p>

                <ul class="about-goal-list">
                    <li>
                        <i class="bi bi-chevron-right"></i>
                        <span>
                            Strengthen interdisciplinary research and scientific collaboration
                            across diverse fields.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-chevron-right"></i>
                        <span>
                            Disseminate research through conference presentations, the Book of
                            Abstract, and publication opportunities with journal partners.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-chevron-right"></i>
                        <span>
                            Expand national and international academic and professional networks.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-chevron-right"></i>
                        <span>
                            Encourage innovation and research that contributes to sustainable
                            development and real-world impact.
                        </span>
                    </li>
                </ul>

                @if ($conferenceLocation)
                    <div class="about-location">
                        <i class="bi bi-geo-alt"></i>
                        {{ $conferenceLocation }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</section>
