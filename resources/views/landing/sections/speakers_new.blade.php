<section id="speakers" class="section speakers speakers-template">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="section-title">Speakers</h3>
            </div>
        </div>

        @if ($conference && $conference->speakers->isNotEmpty())
            <div class="row speakers-template-grid">
                @foreach ($conference->speakers as $speaker)
                    <div class="col-md-4 col-sm-6">
                        <div class="speaker">
                            <figure class="speaker-figure">
                                @if ($speaker->photo)
                                    <img
                                        src="{{ asset('storage/' . $speaker->photo) }}"
                                        alt="{{ $speaker->name }}"
                                        class="img-responsive center-block speaker-image"
                                    >
                                @else
                                    <div class="speaker-placeholder center-block" aria-label="{{ $speaker->name }}">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </figure>

                            <h4>{{ $speaker->name }}</h4>

                            @php
                                $speakerPosition = collect([
                                    $speaker->title,
                                    $speaker->position,
                                    $speaker->institution,
                                ])->filter()->implode(' · ');
                            @endphp

                            <p>{{ $speakerPosition ?: 'BHAMADA ICON Speaker' }}</p>

                            @if ($speaker->linkedin || $speaker->website || $speaker->email)
                                <ul class="social-block" aria-label="Speaker links">
                                    @if ($speaker->linkedin)
                                        <li>
                                            <a href="{{ $speaker->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($speaker->website)
                                        <li>
                                            <a href="{{ $speaker->website }}" target="_blank" rel="noopener noreferrer" aria-label="Website">
                                                <i class="bi bi-globe2"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($speaker->email)
                                        <li>
                                            <a href="mailto:{{ $speaker->email }}" aria-label="Email">
                                                <i class="bi bi-envelope"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="speakers-empty">
                        <i class="bi bi-mic-mute"></i>
                        <h4>Speakers Coming Soon</h4>
                        <p>Keynote and invited speaker information will be published here soon.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
