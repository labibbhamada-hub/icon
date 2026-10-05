@php
    $conferenceTopics = $conference?->topics ?? collect();
@endphp

<section id="topics" class="section topics-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="section-title">Conference Tracks</h3>
            </div>
        </div>

        @if ($conferenceTopics->isNotEmpty())
            <div class="row g-4">
                @foreach ($conferenceTopics as $index => $topic)
                    <div class="col-md-4 col-sm-6">
                        <article class="topic-item">
                            <div class="topic-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="topic-icon">
                                <i class="{{ $topic->icon ?: 'bi bi-journal-text' }}" aria-hidden="true"></i>
                            </div>
                            <h4>{{ $topic->name }}</h4>
                            @if ($topic->description)
                                <p>{{ $topic->description }}</p>
                            @endif
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <p>Conference tracks will be announced soon.</p>
        @endif
    </div>
</section>
