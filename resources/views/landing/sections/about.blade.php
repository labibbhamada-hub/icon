<!--======================================
    ABOUT
=======================================-->

@php
    $conferenceTitle = $conference?->short_name ?? 'BHAMADA ICON';

    $conferenceYear = $conference?->year ?? 2026;

    $conferenceTheme = 'Advancing Interdisciplinary Research and Innovation for Sustainable Development.';

    $conferenceName = 'Bhamada International Conference on Interdisciplinary Innovation';

    $conferenceLocation = collect([$conference?->city, $conference?->country])
        ->filter()
        ->implode(', ');

    $conferenceTracks = 5;

@endphp


<section class="about section-padding" id="about">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- Illustration -->

            <div class="col-lg-6">

                <div class="about-image-card">

                    <img src="{{ asset('assets/images/about/about.webp') }}" class="img-fluid about-image"
                        alt="About BHAMADA ICON 2026">


                    <!-- Floating Info -->

                    <div class="about-floating-card">

                        <h4>
                            {{ $conferenceTracks }}
                        </h4>

                        <span>
                            Conference Tracks
                        </span>

                    </div>

                </div>

            </div>


            <!-- Content -->

            <div class="col-lg-6">

                <span class="section-badge">
                    About Conference
                </span>


                <h2 class="section-title mt-3">

                    About {{ $conferenceTitle }}

                    <span>
                        {{ $conferenceYear }}
                    </span>

                </h2>


                <p class="section-description fw-semibold">

                    {{ $conferenceTheme }}

                </p>


                <p class="section-description">

                    {{ $conferenceName }} is an international scientific
                    forum organized by Universitas Bhamada Slawi as part of
                    the 5th Dies Natalis celebration. The conference brings
                    together academics, researchers, students, practitioners,
                    and professionals from various disciplines to present
                    research, exchange ideas, and strengthen interdisciplinary
                    collaboration for sustainable development.

                </p>


                <p class="section-description">

                    BHAMADA ICON 2026 is designed to strengthen research and
                    scientific publication, expand national and international
                    academic networks, and encourage collaboration between
                    higher education institutions, research organizations,
                    industry, government, and other partners.

                </p>


                @if ($conferenceLocation)
                    <p class="section-description">

                        <i class="bi bi-geo-alt me-1"></i>

                        {{ $conferenceLocation }}

                    </p>
                @endif


                <div class="row g-3 mt-2">

                    {{-- Interdisciplinary Collaboration --}}
                    <div class="col-sm-6">

                        <div class="feature-card">

                            <div class="feature-icon">

                                <i class="bi bi-diagram-3"></i>

                            </div>

                            <div>

                                <h5>
                                    Interdisciplinary Collaboration
                                </h5>

                                <p>
                                    Bringing together researchers and
                                    professionals from different disciplines
                                    to develop collaborative solutions.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Research & Publication --}}
                    <div class="col-sm-6">

                        <div class="feature-card">

                            <div class="feature-icon">

                                <i class="bi bi-journal-richtext"></i>

                            </div>

                            <div>

                                <h5>
                                    Research & Publication
                                </h5>

                                <p>
                                    Disseminate research through the conference,
                                    Book of Abstract, and publication
                                    recommendations to partner journals.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- International Network --}}
                    <div class="col-sm-6">

                        <div class="feature-card">

                            <div class="feature-icon">

                                <i class="bi bi-globe2"></i>

                            </div>

                            <div>

                                <h5>
                                    International Network
                                </h5>

                                <p>
                                    Expand academic and professional networks
                                    through collaboration with national and
                                    international partners.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Sustainable Development --}}
                    <div class="col-sm-6">

                        <div class="feature-card">

                            <div class="feature-icon">

                                <i class="bi bi-leaf"></i>

                            </div>

                            <div>

                                <h5>
                                    Sustainable Development
                                </h5>

                                <p>
                                    Encourage research and innovation that
                                    contribute to sustainable development
                                    across health, technology, business,
                                    and occupational safety.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
