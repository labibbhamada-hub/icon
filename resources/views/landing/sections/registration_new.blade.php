<section class="registration-template section" id="registration">

    @php
        $registrationTypes = $conference?->registrationTypes ?? collect();

        $formatFee = function ($fee, $currency) {
            if (is_null($fee)) {
                return 'Contact us';
            }

            $currency = strtoupper(trim($currency ?: 'IDR'));
            return $currency . ' ' . number_format((float) $fee, 0, ',', '.');
        };

        $formatText = function ($value) {
            return ucfirst(str_replace('_', ' ', $value));
        };
    @endphp

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h3 class="registration-template-title">Registration &amp; Pricing</h3>
            </div>
        </div>

        @if ($registrationTypes->isNotEmpty())
            <div class="row registration-template-content">

                <div class="col-md-5">
                    <div class="registration-template-intro">

                        <span class="registration-template-kicker">
                            BHAMADA ICON {{ $conference?->year ?? 2026 }}
                        </span>

                        <h4>
                            Choose the participation category that matches your role.
                        </h4>

                        <p>
                            Registration categories are provided for presenters, general participants,
                            students, academics, researchers, and practitioners.
                        </p>

                        @if ($conference?->registration_deadline)
                            <div class="registration-template-meta">
                                <i class="bi bi-calendar-event" aria-hidden="true"></i>
                                <div>
                                    <span>Registration closes</span>
                                    <strong>{{ $conference->registration_deadline->format('d F Y') }}</strong>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('register') }}" class="registration-template-submit">
                            Register Now
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>

                    </div>
                </div>

                <div class="col-md-7">
                    <div class="registration-template-list" aria-label="Registration categories">

                        @foreach ($registrationTypes as $registration)
                            @php
                                $benefits = [];

                                if ($registration->benefits) {
                                    $decodedBenefits = json_decode($registration->benefits, true);

                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedBenefits)) {
                                        $benefits = collect($decodedBenefits)->filter()->values()->all();
                                    } else {
                                        $benefits = preg_split('/\r\n|\r|\n/', $registration->benefits);
                                        $benefits = collect($benefits)
                                            ->map(fn ($item) => trim($item))
                                            ->filter()
                                            ->values()
                                            ->all();
                                    }
                                }

                                $category = $registration->category
                                    ? $formatText($registration->category)
                                    : null;

                                $paymentTiming = $registration->payment_timing
                                    ? $formatText($registration->payment_timing)
                                    : null;
                            @endphp

                            <article class="registration-template-item">

                                <div class="registration-template-item-main">
                                    <div class="registration-template-index">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </div>

                                    <div class="registration-template-item-copy">
                                        @if ($category)
                                            <span class="registration-template-category">
                                                {{ $category }}
                                            </span>
                                        @endif

                                        <h4>{{ $registration->name }}</h4>

                                        @if ($registration->description)
                                            <p>{{ $registration->description }}</p>
                                        @endif

                                        <div class="registration-template-details">
                                            @if (!is_null($registration->included_papers))
                                                <span>
                                                    <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                                    {{ $registration->included_papers }}
                                                    {{ $registration->included_papers == 1 ? 'paper' : 'papers' }}
                                                </span>
                                            @endif

                                            @if ($paymentTiming)
                                                <span>
                                                    <i class="bi bi-clock" aria-hidden="true"></i>
                                                    {{ $paymentTiming }}
                                                </span>
                                            @endif

                                            @if (count($benefits))
                                                <span>
                                                    <i class="bi bi-check2" aria-hidden="true"></i>
                                                    {{ count($benefits) }} benefits
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="registration-template-item-side">
                                    <strong>{{ $formatFee($registration->fee, $registration->currency) }}</strong>

                                    @if (!is_null($registration->additional_paper_fee) && (float) $registration->additional_paper_fee > 0)
                                        <small>
                                            + {{ $formatFee($registration->additional_paper_fee, $registration->currency) }} / additional paper
                                        </small>
                                    @endif

                                    <a href="{{ route('register') }}" class="registration-template-link">
                                        Register
                                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>

                            </article>
                        @endforeach

                    </div>
                </div>

            </div>
        @else
            <div class="registration-template-empty">
                <i class="bi bi-person-vcard" aria-hidden="true"></i>
                <h4>Registration Information Coming Soon</h4>
                <p>
                    Registration categories, fees, and participation details will be announced here soon.
                </p>
            </div>
        @endif

    </div>

</section>
