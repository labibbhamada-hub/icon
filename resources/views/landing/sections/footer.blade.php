<footer class="footer">

    @php

        $conferenceShortName = $conference?->short_name ?? 'BHAMADA ICON';

        $conferenceYear = $conference?->year ?? 2026;

        $conferenceName = $conference?->name ?? 'Bhamada International Conference on Interdisciplinary Innovation';

        $conferenceDate = '27 August 2026';

        $conferenceMethod = 'Online Conference';

        $conferencePlatform = 'Zoom Meeting / Virtual Conference Platform';

    @endphp


    <div class="container">

        <div class="row gy-5">

            {{-- ====================================================
                BRAND
            ===================================================== --}}

            <div class="col-lg-4">

                <a href="{{ url('/') }}" class="footer-brand">

                    <img src="{{ asset($conference?->logo ? 'storage/' . $conference->logo : 'assets/images/logo/logo-bhamada.png') }}"
                        alt="{{ $conferenceShortName }}" class="footer-logo">


                    <div>

                        <h5>
                            {{ $conferenceShortName }}
                        </h5>

                        <span>
                            {{ $conferenceName }}
                        </span>

                    </div>

                </a>


                <p class="footer-description">

                    {{ $conferenceName }}

                    brings together academics, researchers,
                    students, practitioners, and professionals
                    to share research, foster interdisciplinary
                    collaboration, and contribute to sustainable
                    development through innovation.

                </p>

            </div>


            {{-- ====================================================
                QUICK LINKS
            ===================================================== --}}

            <div class="col-6 col-lg-2">

                <h6 class="footer-title">
                    Quick Links
                </h6>


                <ul class="footer-links">

                    <li>
                        <a href="#home">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="#about">
                            About
                        </a>
                    </li>

                    <li>
                        <a href="#topics">
                            Topics
                        </a>
                    </li>

                    <li>
                        <a href="#speakers">
                            Speakers
                        </a>
                    </li>

                    <li>
                        <a href="#registration">
                            Registration
                        </a>
                    </li>

                </ul>

            </div>


            {{-- ====================================================
                CONFERENCE
            ===================================================== --}}

            <div class="col-6 col-lg-3">

                <h6 class="footer-title">
                    Conference
                </h6>


                <ul class="footer-links">

                    <li>
                        <a href="#dates">
                            Important Dates
                        </a>
                    </li>

                    <li>
                        <a href="#paper">
                            Call for Papers
                        </a>
                    </li>

                    <li>
                        <a href="#sponsors">
                            Our Partners
                        </a>
                    </li>

                    <li>
                        <a href="#contact">
                            Contact
                        </a>
                    </li>

                </ul>

            </div>


            {{-- ====================================================
                CONFERENCE INFORMATION
            ===================================================== --}}

            <div class="col-lg-3">

                <h6 class="footer-title">
                    Conference Information
                </h6>


                <ul class="footer-contact">

                    {{-- Conference Method --}}
                    <li>

                        <i class="bi bi-camera-video"></i>

                        <span>
                            {{ $conferenceMethod }}
                        </span>

                    </li>


                    {{-- Conference Platform --}}
                    <li>

                        <i class="bi bi-laptop"></i>

                        <span>
                            {{ $conferencePlatform }}
                        </span>

                    </li>


                    {{-- Conference Date --}}
                    <li>

                        <i class="bi bi-calendar-event"></i>

                        <span>
                            {{ $conferenceDate }}
                        </span>

                    </li>

                </ul>

            </div>

        </div>


        {{-- ========================================================
            BOTTOM
        ========================================================= --}}

        <div class="footer-bottom">

            <p>

                &copy;
                {{ $conferenceYear }}
                {{ $conferenceShortName }}.

                All rights reserved.

            </p>


            <div class="footer-bottom-links">

                <span>
                    {{ $conferenceShortName }} {{ $conferenceYear }}
                </span>

            </div>

        </div>

    </div>

</footer>
