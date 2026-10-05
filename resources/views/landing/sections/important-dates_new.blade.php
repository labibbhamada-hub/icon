@php
    $importantDates = $conference?->importantDates ?? collect();

    $formatDate = function ($date) {
        return $date ? $date->translatedFormat('d F Y') : null;
    };
@endphp

<section id="schedule" class="section schedule-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="section-title">Event Schedule</h3>
            </div>
        </div>

        @if ($importantDates->isNotEmpty())
            <div class="row g-4">
                @foreach ($importantDates as $item)
                    <div class="col-md-4 col-sm-6">
                        <article class="schedule-box h-100">
                            <div class="time">
                                {{ $formatDate($item->date) }}
                                @if ($item->end_date && $item->end_date->ne($item->date))
                                    – {{ $formatDate($item->end_date) }}
                                @endif
                            </div>
                            <h3>{{ $item->title }}</h3>
                            @if ($item->description)
                                <p>{{ $item->description }}</p>
                            @endif
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <p>Conference schedule will be announced soon.</p>
        @endif
    </div>
</section>
