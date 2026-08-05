@php
    $academicPartners = [
        [
            'name' => 'Universitas Bhamada',
            'logo' => asset('assets/images/sponsors/bhamada.png'),
            'short' => 'BHAMADA',
        ],
        [
            'name' => 'University of Melbourne',
            'logo' => asset('assets/images/sponsors/melbourne.png'),
            'short' => 'MELBOURNE',
        ],
        [
            'name' => 'Kyoto University',
            'logo' => asset('assets/images/sponsors/kyoto.png'),
            'short' => 'KYOTO',
        ],
        [
            'name' => 'National University of Singapore',
            'logo' => asset('assets/images/sponsors/nus.png'),
            'short' => 'NUS',
        ],
    ];
@endphp

<section id="sponsors" class="sponsors-section">
    <div class="container">
        {{-- Section Header --}}
        <div class="section-heading text-center">
            <span class="section-badge">
                <span class="badge-dot"></span>
                Our Partners
            </span>
            <h2>
                Supporting <span>Global Innovation</span>
            </h2>
            <p>
                We collaborate with universities, research institutions,
                publishers, and organizations to support meaningful
                academic collaboration.
            </p>
        </div>

        {{-- Academic Partners --}}
        <div class="partner-group">
            <div class="partner-group-heading text-center">
                <span>Academic Partners</span>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach ($academicPartners as $partner)
                    <div class="col-6 col-md-3">
                        <div class="partner-card">
                            <div class="partner-logo">
                                <img src="{{ $partner['logo'] }}" alt="{{ $partner['name'] }}">
                            </div>
                            <span class="partner-short">
                                {{ $partner['short'] }}
                            </span>
                            <h5>
                                {{ $partner['name'] }}
                            </h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
