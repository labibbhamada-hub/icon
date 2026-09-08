<!--======================================
    HOME
======================================-->

@php

    /*
    |--------------------------------------------------------------------------
    | BHAMADA ICON 2026
    |--------------------------------------------------------------------------
    | Content below follows the official TOR.
    */

    $conferenceName = 'BHAMADA ICON';

    $conferenceShortName = 'BHAMADA ICON';

    $conferenceYear = 2026;

    $conferenceTheme = 'Advancing Interdisciplinary Research and Innovation for Sustainable Development.';

    $conferenceDescription =
        'BHAMADA ICON 2026 is an international conference that brings together academics, researchers, students, and practitioners from various disciplines to disseminate research, exchange ideas, and strengthen interdisciplinary collaboration for sustainable development.';

    /*
    |--------------------------------------------------------------------------
    | Conference Information
    |--------------------------------------------------------------------------
    */

    $conferenceDate = '27 August 2026';

    $conferenceLocation = 'Online Conference — Zoom Meeting / Virtual Conference Platform';

@endphp


<section class="hero" id="home">

    <div class="hero-pattern"></div>

    <div class="container">

        <div class="row align-items-center">

            <!-- Hero Content -->
            <div class="col-lg-6">

                <span class="hero-badge">
                    {{ $conferenceName }}
                    {{ $conferenceYear }}
                </span>


                <h1 class="hero-title mt-4">

                    {{ $conferenceShortName }}

                    <span>
                        {{ $conferenceTheme }}
                    </span>

                </h1>


                <p class="hero-description">
                    {{ $conferenceDescription }}
                </p>


                <div class="hero-button">

                    <a href="{{ route('register') }}" class="btn btn-register">
                        Submit Abstract
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-login">
                        Registration
                    </a>

                </div>


                <div class="hero-highlights">

                    <div class="highlight-item">

                        <i class="bi bi-patch-check-fill"></i>

                        International Conference

                    </div>


                    <div class="highlight-item">

                        <i class="bi bi-diagram-3"></i>

                        Interdisciplinary Innovation

                    </div>


                    <div class="highlight-item">

                        <i class="bi bi-camera-video"></i>

                        Online Conference

                    </div>

                </div>


                <div class="hero-organizer">

                    <span class="organizer-label">
                        Organized by
                    </span>

                    <div class="organizer-content">

                        <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="Universitas Bhamada Slawi"
                            class="organizer-logo">

                        <div>

                            <h6>
                                Universitas Bhamada Slawi
                            </h6>

                            <small>
                                BHAMADA ICON 2026
                            </small>

                        </div>

                    </div>

                </div>


                <div class="hero-statistics">

                    <div class="hero-stat">

                        <h3>
                            5
                        </h3>

                        <span>
                            Conference Tracks
                        </span>

                    </div>


                    <div class="hero-stat">

                        <h3>
                            1
                        </h3>

                        <span>
                            Conference Day
                        </span>

                    </div>


                    <div class="hero-stat">

                        <h3>
                            Online
                        </h3>

                        <span>
                            Conference Format
                        </span>

                    </div>


                    <div class="hero-stat">

                        <h3>
                            Annual
                        </h3>

                        <span>
                            Conference Vision
                        </span>

                    </div>

                </div>


                <div class="hero-meta">

                    <div class="meta-item">

                        <i class="bi bi-calendar-event"></i>

                        <span>
                            {{ $conferenceDate }}
                        </span>

                    </div>


                    <div class="meta-item">

                        <i class="bi bi-camera-video"></i>

                        <span>
                            {{ $conferenceLocation }}
                        </span>

                    </div>

                </div>

            </div>


            <!-- Hero Image -->
            <div class="col-lg-6">

                <div class="hero-image-wrapper">

                    <!-- Background Blob -->

                    <img src="{{ asset('assets/images/hero/background/blob-yellow.svg') }}"
                        class="hero-blob hero-blob-yellow" alt="">

                    <img src="{{ asset('assets/images/hero/background/blob-green.svg') }}"
                        class="hero-blob hero-blob-green" alt="">


                    <!-- Decoration -->

                    <div class="hero-decoration">

                        <span class="hero-circle circle-1"></span>
                        <span class="hero-circle circle-2"></span>
                        <span class="hero-circle circle-3"></span>
                        <span class="hero-circle circle-4"></span>

                    </div>


                    <!-- Floating Cards -->

                    <div class="floating-card card-1">

                        <i class="bi bi-diagram-3"></i>

                        <div>

                            <strong>
                                Interdisciplinary Research
                            </strong>

                            <small>
                                Collaboration
                            </small>

                        </div>

                    </div>


                    <div class="floating-card card-2">

                        <i class="bi bi-cpu"></i>

                        <div>

                            <strong>
                                Innovation
                            </strong>

                            <small>
                                Digital Technology & AI
                            </small>

                        </div>

                    </div>


                    <div class="floating-card card-3">

                        <i class="bi bi-globe2"></i>

                        <div>

                            <strong>
                                Sustainable Development
                            </strong>

                            <small>
                                Global Impact
                            </small>

                        </div>

                    </div>


                    <!-- Hero Image -->

                    <img src="{{ asset('assets/images/hero/hero.webp') }}" class="img-fluid hero-image"
                        alt="BHAMADA ICON 2026">

                </div>

            </div>

        </div>

    </div>

</section>
