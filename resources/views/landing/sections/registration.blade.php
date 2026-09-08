<section class="registration-section section-padding" id="registration">

    @php

        $registrationTypes = $conference?->registrationTypes ?? collect();

        /*
        |--------------------------------------------------------------------------
        | Helper
        |--------------------------------------------------------------------------
        */

        $formatFee = function ($fee, $currency) {
            if (is_null($fee)) {
                return 'Contact us';
            }

            $currency = strtoupper(trim($currency ?: 'IDR'));

            $formatted = number_format((float) $fee, 0, ',', '.');

            return $currency . ' ' . $formatted;
        };

        $formatText = function ($value) {
            return ucfirst(str_replace('_', ' ', $value));
        };

    @endphp


    <div class="container">

        {{-- ========================================================
            SECTION HEADER
        ========================================================= --}}

        <div class="section-heading text-center">

            <span class="section-badge">

                <span class="badge-dot"></span>

                Registration

            </span>


            <h2>

                Choose Your
                <span>Registration Type</span>

            </h2>


            <p>

                BHAMADA ICON 2026 welcomes presenters, general participants,
                students, academics, researchers, and practitioners from
                various sectors. Select the registration category that best
                matches your participation.

            </p>

        </div>


        {{-- ========================================================
            REGISTRATION TYPES
        ========================================================= --}}

        @if ($registrationTypes->isNotEmpty())

            <div class="row g-4 justify-content-center">

                @foreach ($registrationTypes as $registration)
                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Benefits
                        |--------------------------------------------------------------------------
                        | Supports JSON benefits as well as line-separated/plain text.
                        */

                        $benefits = [];

                        if ($registration->benefits) {
                            $decodedBenefits = json_decode($registration->benefits, true);

                            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedBenefits)) {
                                $benefits = collect($decodedBenefits)->filter()->values()->all();
                            } else {
                                $benefits = preg_split('/\r\n|\r|\n/', $registration->benefits);

                                $benefits = collect($benefits)
                                    ->map(fn($item) => trim($item))
                                    ->filter()
                                    ->values()
                                    ->all();
                            }
                        }

                        $registrationName = $registration->name;

                        $registrationCategory = $registration->category ? $formatText($registration->category) : null;

                        $paymentTiming = $registration->payment_timing
                            ? $formatText($registration->payment_timing)
                            : null;

                        $fee = $formatFee($registration->fee, $registration->currency);

                    @endphp


                    <div class="col-lg-4 col-md-6">

                        <div class="registration-card h-100">

                            {{-- Header --}}
                            <div class="registration-card-header">

                                <div>

                                    @if ($registrationCategory)
                                        <span class="registration-category">

                                            {{ $registrationCategory }}

                                        </span>
                                    @endif


                                    <h4 class="registration-title">

                                        {{ $registrationName }}

                                    </h4>

                                </div>

                            </div>


                            {{-- Price --}}
                            <div class="registration-price">

                                <span class="registration-price-value">

                                    {{ $fee }}

                                </span>


                                @if ($paymentTiming)
                                    <small class="registration-price-note">

                                        {{ $paymentTiming }}

                                    </small>
                                @endif

                            </div>


                            {{-- Description --}}
                            @if ($registration->description)
                                <p class="registration-description">

                                    {{ $registration->description }}

                                </p>
                            @endif


                            {{-- Included Papers --}}
                            @if (!is_null($registration->included_papers))
                                <div class="registration-detail">

                                    <i class="bi bi-file-earmark-text"></i>

                                    <span>

                                        Includes

                                        <strong>

                                            {{ $registration->included_papers }}

                                        </strong>

                                        {{ $registration->included_papers == 1 ? 'paper' : 'papers' }}

                                    </span>

                                </div>
                            @endif


                            {{-- Additional Paper --}}
                            @if (!is_null($registration->additional_paper_fee) && (float) $registration->additional_paper_fee > 0)
                                <div class="registration-detail">

                                    <i class="bi bi-plus-circle"></i>

                                    <span>

                                        Additional paper:

                                        <strong>

                                            {{ $formatFee($registration->additional_paper_fee, $registration->currency) }}

                                        </strong>

                                    </span>

                                </div>
                            @endif


                            {{-- Benefits --}}
                            @if (count($benefits))
                                <div class="registration-benefits">

                                    <h6>
                                        Benefits
                                    </h6>


                                    <ul>

                                        @foreach ($benefits as $benefit)
                                            <li>

                                                <i class="bi bi-check-circle-fill"></i>

                                                <span>
                                                    {{ $benefit }}
                                                </span>

                                            </li>
                                        @endforeach

                                    </ul>

                                </div>
                            @endif


                            {{-- CTA --}}
                            <div class="registration-card-footer">

                                <a href="{{ route('register') }}" class="btn btn-register w-100">

                                    Register Now

                                    <i class="bi bi-arrow-right ms-2"></i>

                                </a>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>


            {{-- ====================================================
                ADDITIONAL INFORMATION
            ===================================================== --}}

            <div class="registration-note text-center mt-5">

                <i class="bi bi-info-circle me-1"></i>

                Registration details, fees, and category requirements
                are provided through the conference registration system.

                @if ($conference?->registration_deadline)
                    Registration closes on

                    <strong>

                        {{ $conference->registration_deadline->format('d F Y') }}

                    </strong>.
                @endif

            </div>
        @else
            {{-- ====================================================
                EMPTY STATE
            ===================================================== --}}

            <div class="text-center py-5">

                <div class="mb-3">

                    <i class="bi bi-person-vcard" style="font-size: 3rem;"></i>

                </div>


                <h4>
                    Registration Information Coming Soon
                </h4>


                <p class="text-muted mb-0">

                    Registration categories, fees, and participation
                    details will be announced here soon.

                </p>

            </div>

        @endif

    </div>

</section>
