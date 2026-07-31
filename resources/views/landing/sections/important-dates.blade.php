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
        <div class="timeline">
            @php
                $dates = [
                    [
                        'title' => 'Abstract Submission Opens',
                        'date' => '01 January 2027',
                    ],
                    [
                        'title' => 'Abstract Deadline',
                        'date' => '15 March 2027',
                    ],
                    [
                        'title' => 'Notification of Acceptance',
                        'date' => '30 March 2027',
                    ],
                    [
                        'title' => 'Full Paper Submission',
                        'date' => '20 April 2027',
                    ],
                    [
                        'title' => 'Early Bird Registration',
                        'date' => '30 April 2027',
                    ],
                    [
                        'title' => 'Conference Day',
                        'date' => '15–16 July 2027',
                    ],
                ];
            @endphp
            @foreach ($dates as $item)
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h5>{{ $item['title'] }}</h5>
                        <p>{{ $item['date'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
