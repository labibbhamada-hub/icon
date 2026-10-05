<section id="topics" class="section topics-template">

    <div class="container">

        <div class="row">
            <div class="col-md-12">

                <h3 class="section-title topics-template-title">
                    Conference Tracks
                </h3>

            </div>
        </div>

        @php
            $conferenceTopics = $conference?->topics ?? collect();
        @endphp

        @if ($conferenceTopics->isNotEmpty())
            <div class="row topics-template-grid">

                @foreach ($conferenceTopics as $index => $topic)
                    <div class="col-md-4">

                        <article class="topics-template-item">

                            <div class="topics-template-number">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <div class="topics-template-icon" aria-hidden="true">
                                <i class="{{ $topic->icon ?: 'bi bi-bookmark' }}"></i>
                            </div>

                            <h4 class="topics-template-name">
                                {{ $topic->name }}
                            </h4>

                            @if ($topic->description)
                                <p class="topics-template-description">
                                    {{ $topic->description }}
                                </p>
                            @endif

                        </article>

                    </div>
                @endforeach

            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <p class="topics-template-empty">
                        Conference tracks will be announced soon.
                    </p>
                </div>
            </div>
        @endif

    </div>

</section>
