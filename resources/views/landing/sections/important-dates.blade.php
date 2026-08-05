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
                Keep track of every important milestone before the conference.
            </p>
        </div>
        @php
            $dates = [
                [
                    'label' => 'Submission',
                    'title' => 'Abstract Submission Opens',
                    'description' => 'Authors can begin submitting their research abstracts.',
                    'date' => '01 January 2026',
                ],
                [
                    'label' => 'Submission',
                    'title' => 'Abstract Deadline',
                    'description' => 'Final date for submitting conference abstracts.',
                    'date' => '15 March 2026',
                ],
                [
                    'label' => 'Acceptance',
                    'title' => 'Notification of Acceptance',
                    'description' => 'Authors will receive the review and acceptance notification.',
                    'date' => '30 March 2026',
                ],
                [
                    'label' => 'Paper',
                    'title' => 'Full Paper Submission',
                    'description' => 'Accepted authors submit their complete research paper.',
                    'date' => '20 April 2026',
                ],
                [
                    'label' => 'Registration',
                    'title' => 'Early Bird Registration',
                    'description' => 'Take advantage of the early bird registration period.',
                    'date' => '30 April 2026',
                ],
                [
                    'label' => 'Conference',
                    'title' => 'Conference Day',
                    'description' => 'Join researchers, academics, and professionals at ICON 2026.',
                    'date' => '15–16 July 2026',
                ],
            ];
        @endphp
        <div class="timeline">
            @foreach ($dates as $item)
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
