<section class="important-dates section-padding" id="dates">

    <div class="container">

        <div class="section-title text-center">

            <span class="section-badge">
                Important Dates
            </span>

            <h2>
                Conference
                <span>Timeline</span>
            </h2>

            <p>
                Keep track of every important milestone of BHAMADA ICON 2026,
                from preparation and call for papers to the conference and publication.
            </p>

        </div>


        @php

            /*
            |--------------------------------------------------------------------------
            | BHAMADA ICON 2026 - OFFICIAL TIMELINE
            |--------------------------------------------------------------------------
            | Source: Terms of Reference (TOR) BHAMADA ICON 2026
            */

            $timeline = [
                [
                    'label' => 'Preparation',
                    'title' => 'Preparation of TOR and Proposal',
                    'description' => 'Preparation of the Terms of Reference and conference proposal.',
                    'date' => '10–25 April 2026',
                ],

                [
                    'label' => 'Invited Speakers',
                    'title' => 'Invitations for Invited Speakers and Reviewers',
                    'description' =>
                        'Invitation and coordination with invited speakers and reviewers for the conference.',
                    'date' => '15–31 May 2026',
                ],

                [
                    'label' => 'Partnership',
                    'title' => 'Journal Partner Collaboration',
                    'description' => 'Establishing cooperation with journal partners for publication recommendations.',
                    'date' => 'May–July 2026',
                ],

                [
                    'label' => 'Call for Papers',
                    'title' => 'Call for Papers Publication',
                    'description' =>
                        'Publication of the conference announcement, theme, conference tracks, important dates, submission requirements, and registration mechanism.',
                    'date' => '29 July 2026',
                ],

                [
                    'label' => 'Submission',
                    'title' => 'Abstract Submission and Review',
                    'description' => 'Presenter abstract submission followed by review by the Scientific Committee.',
                    'date' => '29 July–13 August 2026',
                ],

                [
                    'label' => 'Acceptance',
                    'title' => 'Abstract Acceptance Announcement',
                    'description' =>
                        'Announcement of accepted abstracts. Authors of accepted abstracts may proceed to full paper submission.',
                    'date' => '15 August 2026',
                ],

                [
                    'label' => 'Full Paper',
                    'title' => 'Full Paper Submission',
                    'description' =>
                        'Accepted presenters submit their full paper according to the conference template.',
                    'date' => '16–22 August 2026',
                ],

                [
                    'label' => 'Technical Meeting',
                    'title' => 'Technical Meeting for Presenters',
                    'description' =>
                        'Technical briefing covering the virtual conference platform, presentation procedures, and presentation schedule.',
                    'date' => '23 August 2026',
                ],

                [
                    'label' => 'Conference',
                    'title' => 'BHAMADA ICON 2026',
                    'description' =>
                        'International conference conducted online through Zoom Meeting or the virtual conference platform designated by the committee.',
                    'date' => '27 August 2026',
                ],

                [
                    'label' => 'Publication',
                    'title' => 'Book of Abstract Publication',
                    'description' => 'Publication of the BHAMADA ICON 2026 Book of Abstract.',
                    'date' => 'August 2026',
                ],

                [
                    'label' => 'Publication',
                    'title' => 'Publication Recommendation to Journal Partners',
                    'description' =>
                        'Eligible scientific papers are recommended for publication in partner journals according to the scope and requirements of each journal.',
                    'date' => 'August–September 2026',
                ],

                [
                    'label' => 'Reporting',
                    'title' => 'Conference Activity Report',
                    'description' => 'Preparation of the BHAMADA ICON 2026 activity report.',
                    'date' => 'September 2026',
                ],
            ];

        @endphp


        <div class="timeline">

            @foreach ($timeline as $item)
                <div class="timeline-item">

                    <div class="timeline-dot"></div>


                    <div class="timeline-content">

                        <span class="timeline-label">
                            {{ $item['label'] }}
                        </span>


                        <h5>
                            {{ $item['title'] }}
                        </h5>


                        <p class="timeline-description">
                            {{ $item['description'] }}
                        </p>


                        <div class="timeline-date">

                            <i class="bi bi-calendar-event"></i>

                            {{ $item['date'] }}

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

</section>
