@php
    $speakers = $conference?->speakers ?? collect();
@endphp

<section id="speakers" class="section speakers-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="section-title">Speakers</h3>
            </div>
        </div>

        @if ($speakers->isNotEmpty())
            <div class="row g-4">
                @foreach ($speakers as $speaker)
                    <div class="col-md-4 col-sm-6">
                        <article class="speaker">
                            <figure>
                                @if ($speaker->photo)
                                    <img alt="{{ $speaker->name }}" class="img-fluid mx-auto d-block speaker-photo"
                                        src="{{ asset('storage/' . $speaker->photo) }}" loading="lazy">
                                @else
                                    <div class="speaker-placeholder">
                                        <i class="bi bi-person" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </figure>

                            <h4>{{ $speaker->name }}</h4>

                            @php
                                $speakerMeta = collect([$speaker->title, $speaker->position, $speaker->institution])
                                    ->filter()
                                    ->implode(' · ');
                            @endphp

                            @if ($speakerMeta)
                                <p>{{ $speakerMeta }}</p>
                            @endif

                            <ul class="social-block">
                                @if ($speaker->website)
                                    <li><a href="{{ $speaker->website }}" target="_blank" rel="noopener noreferrer"
                                            aria-label="Website"><i class="bi bi-globe2"></i></a></li>
                                @endif
                                @if ($speaker->linkedin)
                                    <li><a href="{{ $speaker->linkedin }}" target="_blank" rel="noopener noreferrer"
                                            aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a></li>
                                @endif
                                @if ($speaker->email)
                                    <li><a href="mailto:{{ $speaker->email }}" aria-label="Email"><i
                                                class="bi bi-envelope"></i></a></li>
                                @endif
                            </ul>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <p>Speaker information will be announced soon.</p>
        @endif
    </div>
</section>
