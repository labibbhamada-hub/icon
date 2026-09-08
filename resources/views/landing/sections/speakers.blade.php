<section class="speakers section-padding" id="speakers">

    <div class="container">

        {{-- ========================================================
            SECTION HEADER
        ========================================================= --}}

        <div class="section-heading text-center">

            <span class="section-badge">
                Keynote & Invited Speakers
            </span>

            <h2 class="section-title mt-3">

                Meet Our
                <span>Distinguished Speakers</span>

            </h2>

            <p>

                Gain insights from distinguished keynote and invited speakers
                who will share their knowledge, perspectives, and experience
                in support of interdisciplinary research, innovation,
                and sustainable development.

            </p>

        </div>


        @if ($conference && $conference->speakers->isNotEmpty())

            @php

                $featuredSpeaker = $conference->speakers->first();

                $gridSpeakers = $conference->speakers->skip(1)->take(4);

            @endphp


            {{-- ====================================================
                FEATURED SPEAKER
            ===================================================== --}}

            <div class="featured-speaker">

                <div class="row align-items-center g-4">

                    {{-- Photo --}}
                    <div class="col-lg-5">

                        <div class="featured-speaker-image">

                            @if ($featuredSpeaker->photo)
                                <img src="{{ asset('storage/' . $featuredSpeaker->photo) }}"
                                    alt="{{ $featuredSpeaker->name }}">
                            @else
                                <div class="featured-speaker-placeholder">

                                    <i class="bi bi-person"></i>

                                </div>
                            @endif

                        </div>

                    </div>


                    {{-- Information --}}
                    <div class="col-lg-7">

                        <span class="featured-label">

                            Featured Keynote Speaker

                        </span>


                        <h3 class="featured-name">

                            {{ $featuredSpeaker->name }}

                        </h3>


                        @if ($featuredSpeaker->title || $featuredSpeaker->position || $featuredSpeaker->institution)

                            <div class="featured-position">

                                @if ($featuredSpeaker->title)
                                    <span>
                                        {{ $featuredSpeaker->title }}
                                    </span>
                                @endif


                                @if ($featuredSpeaker->title && $featuredSpeaker->position)
                                    <span>
                                        ·
                                    </span>
                                @endif


                                @if ($featuredSpeaker->position)
                                    <span>
                                        {{ $featuredSpeaker->position }}
                                    </span>
                                @endif


                                @if (($featuredSpeaker->title || $featuredSpeaker->position) && $featuredSpeaker->institution)
                                    <span>
                                        ·
                                    </span>
                                @endif


                                @if ($featuredSpeaker->institution)
                                    <span>
                                        {{ $featuredSpeaker->institution }}
                                    </span>
                                @endif

                            </div>

                        @endif


                        @if ($featuredSpeaker->bio)
                            <p class="featured-description">

                                {{ $featuredSpeaker->bio }}

                            </p>
                        @else
                            <p class="featured-description">

                                We are pleased to welcome
                                {{ $featuredSpeaker->name }}
                                as a keynote speaker at BHAMADA ICON 2026,
                                contributing perspectives to the conference
                                theme of interdisciplinary research,
                                innovation, and sustainable development.

                            </p>
                        @endif


                        <div class="featured-tags">

                            @if ($featuredSpeaker->position)
                                <span>
                                    {{ $featuredSpeaker->position }}
                                </span>
                            @endif


                            @if ($featuredSpeaker->institution)
                                <span>
                                    {{ $featuredSpeaker->institution }}
                                </span>
                            @endif


                            @if ($featuredSpeaker->title)
                                <span>
                                    {{ $featuredSpeaker->title }}
                                </span>
                            @endif

                        </div>


                        @if ($featuredSpeaker->website || $featuredSpeaker->linkedin)

                            <div class="featured-actions">

                                @if ($featuredSpeaker->website)
                                    <a href="{{ $featuredSpeaker->website }}" target="_blank" rel="noopener noreferrer"
                                        class="btn btn-register">

                                        <i class="bi bi-globe me-1"></i>

                                        Website

                                    </a>
                                @endif


                                @if ($featuredSpeaker->linkedin)
                                    <a href="{{ $featuredSpeaker->linkedin }}" target="_blank"
                                        rel="noopener noreferrer" class="btn btn-login">

                                        <i class="bi bi-linkedin me-1"></i>

                                        LinkedIn

                                    </a>
                                @endif

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ====================================================
                SPEAKER GRID
            ===================================================== --}}

            @if ($gridSpeakers->isNotEmpty())

                <div class="speaker-grid">

                    <div class="row g-4">

                        @foreach ($gridSpeakers as $speaker)
                            <div class="col-lg-3 col-md-6">

                                @php

                                    $speakerPhoto = $speaker->photo ? asset('storage/' . $speaker->photo) : null;

                                @endphp


                                <x-speaker-card :photo="$speakerPhoto" :name="$speaker->name" :university="$speaker->institution ?? ($speaker->position ?? '-')" />

                            </div>
                        @endforeach

                    </div>

                </div>

            @endif
        @else
            <div class="text-center py-5">

                <i class="bi bi-mic-mute display-5"></i>

                <h4 class="mt-3">
                    Speakers Coming Soon
                </h4>

                <p class="text-muted mb-0">

                    Keynote and invited speaker information
                    will be published here soon.

                </p>

            </div>

        @endif

    </div>

</section>
