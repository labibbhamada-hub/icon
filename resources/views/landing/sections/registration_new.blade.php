@php
    $registrationTypes = $conference?->registrationTypes ?? collect();

    $formatFee = function ($fee, $currency) {
        if (is_null($fee)) {
            return 'Contact us';
        }

        return strtoupper(trim($currency ?: 'IDR')) . ' ' . number_format((float) $fee, 0, ',', '.');
    };

    $formatText = fn($value) => ucfirst(str_replace('_', ' ', $value));
@endphp

<section id="registration" class="section registration-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="section-title">Registration &amp; Pricing</h3>
            </div>
        </div>

        @if ($registrationTypes->isNotEmpty())
            <div class="row g-4">
                @foreach ($registrationTypes as $registration)
                    @php
                        $benefits = [];
                        if ($registration->benefits) {
                            $decodedBenefits = json_decode($registration->benefits, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedBenefits)) {
                                $benefits = collect($decodedBenefits)->filter()->values()->all();
                            } else {
                                $benefits = collect(preg_split('/\r\n|\r|\n/', $registration->benefits))
                                    ->map(fn($item) => trim($item))
                                    ->filter()
                                    ->values()
                                    ->all();
                            }
                        }
                    @endphp

                    <div class="col-md-4">
                        <article class="price-item h-100">
                            @if ($registration->category)
                                <span class="price-category">{{ $formatText($registration->category) }}</span>
                            @endif

                            <h4>{{ $registration->name }}</h4>
                            <div class="price-value">{{ $formatFee($registration->fee, $registration->currency) }}</div>

                            @if ($registration->payment_timing)
                                <div class="price-timing">{{ $formatText($registration->payment_timing) }}</div>
                            @endif

                            @if ($registration->description)
                                <p>{{ $registration->description }}</p>
                            @endif

                            @if (!is_null($registration->included_papers))
                                <p class="price-detail"><strong>{{ $registration->included_papers }}</strong> included
                                    paper{{ $registration->included_papers == 1 ? '' : 's' }}</p>
                            @endif

                            @if (!is_null($registration->additional_paper_fee) && (float) $registration->additional_paper_fee > 0)
                                <p class="price-detail">Additional paper:
                                    <strong>{{ $formatFee($registration->additional_paper_fee, $registration->currency) }}</strong>
                                </p>
                            @endif

                            @if (count($benefits))
                                <ul class="price-benefits">
                                    @foreach ($benefits as $benefit)
                                        <li>{{ $benefit }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ route('register') }}" class="btn btn-black rounded-0">Register</a>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <p>Registration categories and fees will be announced soon.</p>
        @endif
    </div>
</section>
