@php
    $partners = ($conference?->partners ?? collect())
        ->where('is_active', true)
        ->sortBy([['sort_order', 'asc'], ['name', 'asc']]);
@endphp

<section id="partner" class="section partner-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="section-title">Event Partner</h3>
            </div>
        </div>

        @if ($partners->isNotEmpty())
            <div class="row g-4 align-items-center">
                @foreach ($partners as $partner)
                    <div class="col-6 col-md-3">
                        @if ($partner->website)
                            <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer"
                                class="partner-box">
                            @else
                                <div class="partner-box">
                        @endif

                        @if ($partner->logo)
                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}"
                                loading="lazy">
                        @else
                            <span class="partner-placeholder" aria-label="{{ $partner->name }}">
                                <i class="bi bi-building" aria-hidden="true"></i>
                            </span>
                        @endif

                        @if ($partner->website)
                            </a>
                        @else
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <p>No partner information is available yet.</p>
    @endif
    </div>
</section>
