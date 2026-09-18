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
            $importantDates = $conference?->importantDates ?? collect();

            $formatDate = function ($date) {
                if (!$date) {
                    return null;
                }

                return $date->translatedFormat('d F Y');
            };
        @endphp


        <div class="timeline">

            @foreach ($importantDates as $item)
                <div class="timeline-item">

                    <div class="timeline-dot"></div>

                    <div class="timeline-content">

                        <span class="timeline-label">
                            {{ ucfirst(str_replace('_', ' ', $item->type)) }}
                        </span>

                        <h5>
                            {{ $item->title }}
                        </h5>

                        @if ($item->description)
                            <p class="timeline-description">
                                {{ $item->description }}
                            </p>
                        @endif

                        <div class="timeline-date">
                            <i class="bi bi-calendar-event"></i>

                            {{ $formatDate($item->date) }}

                            @if ($item->end_date && $item->end_date->ne($item->date))
                                – {{ $formatDate($item->end_date) }}
                            @endif
                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

</section>
