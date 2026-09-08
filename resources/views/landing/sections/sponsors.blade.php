<section id="sponsors" class="sponsors-section">

    <div class="container">

        {{-- Section Header --}}
        <div class="section-heading text-center">

            <span class="section-badge">

                <span class="badge-dot"></span>

                Our Partners

            </span>

            <h2>
                Building
                <span>Academic Collaboration</span>
            </h2>

            <p>
                BHAMADA ICON 2026 brings together journal partners,
                academic institutions, research organizations, and
                other collaborators to strengthen research, innovation,
                and academic networking at national and international levels.
            </p>

        </div>


        @if ($conference && $conference->partners->isNotEmpty())

            @php
                $partners = $conference->partners
                    ->where('is_active', true)
                    ->sortBy([['sort_order', 'asc'], ['name', 'asc']]);

                $typeLabels = [
                    'university' => 'University',
                    'government' => 'Government Institution',
                    'sponsor' => 'Sponsor',
                    'media_partner' => 'Media Partner',
                    'community' => 'Community',
                    'institution' => 'Institution',
                    'partner' => 'Partner',
                    'other' => 'Partner',
                ];
            @endphp


            @if ($partners->isNotEmpty())

                <div class="partner-group">

                    <div class="partner-group-heading text-center">

                        <span>
                            Conference Partners
                        </span>

                    </div>


                    <div class="row g-4 justify-content-center">

                        @foreach ($partners as $partner)
                            @php

                                $partnerLogo = $partner->logo ? asset('storage/' . $partner->logo) : null;

                                $partnerType =
                                    $typeLabels[$partner->type] ?? ucfirst(str_replace('_', ' ', $partner->type));

                            @endphp


                            <div class="col-6 col-md-4 col-lg-3">

                                @if ($partner->website)
                                    <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer"
                                        class="partner-link">
                                @endif


                                <div class="partner-card">

                                    <div class="partner-logo">

                                        @if ($partnerLogo)
                                            <img src="{{ $partnerLogo }}" alt="{{ $partner->name }}" loading="lazy">
                                        @else
                                            <div class="partner-logo-placeholder">

                                                <i class="bi bi-building"></i>

                                            </div>
                                        @endif

                                    </div>


                                    <span class="partner-short">

                                        {{ $partnerType }}

                                    </span>


                                    <h5>
                                        {{ $partner->name }}
                                    </h5>


                                    @if ($partner->description)
                                        <p>

                                            {{ \Illuminate\Support\Str::limit($partner->description, 90) }}

                                        </p>
                                    @endif


                                    @if ($partner->website)
                                        <span class="partner-website">

                                            Visit Website

                                            <i class="bi bi-arrow-up-right"></i>

                                        </span>
                                    @endif

                                </div>


                                @if ($partner->website)
                                    </a>
                                @endif

                            </div>
                        @endforeach

                    </div>

                </div>
            @else
                <div class="text-center py-5">

                    <i class="bi bi-building" style="font-size: 3rem;"></i>

                    <h4 class="mt-3">
                        Partners Coming Soon
                    </h4>

                    <p class="text-muted mb-0">
                        Conference partners will be announced here soon.
                    </p>

                </div>

            @endif
        @else
            <div class="text-center py-5">

                <i class="bi bi-building" style="font-size: 3rem;"></i>

                <h4 class="mt-3">
                    Partners Coming Soon
                </h4>

                <p class="text-muted mb-0">
                    Conference partners will be announced here soon.
                </p>

            </div>

        @endif

    </div>

</section>
