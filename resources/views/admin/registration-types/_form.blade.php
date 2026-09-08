<div class="card-body">

    <div class="row">

        {{-- Conference --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Conference
                <span class="text-danger">*</span>
            </label>

            <select name="conference_id" class="form-select @error('conference_id') is-invalid @enderror rounded-0">
                <option value="">
                    Select Conference
                </option>

                @foreach ($conferences as $conference)
                    <option value="{{ $conference->id }}" @selected(old('conference_id', $conferenceRegistrationType->conference_id ?? '') == $conference->id)>
                        {{ $conference->short_name }}
                        ({{ $conference->year }})
                    </option>
                @endforeach

            </select>

            @error('conference_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Name --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Name
                <span class="text-danger">*</span>
            </label>

            <input type="text" name="name" value="{{ old('name', $conferenceRegistrationType->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror rounded-0"
                placeholder="e.g. General Participant">

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Code --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Code
                <span class="text-danger">*</span>
            </label>

            <input type="text" name="code" value="{{ old('code', $conferenceRegistrationType->code ?? '') }}"
                class="form-control @error('code') is-invalid @enderror rounded-0"
                placeholder="e.g. general_participant">

            @error('code')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Use lowercase letters, numbers, and underscores only.
            </div>

        </div>

        {{-- Category --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Category
                <span class="text-danger">*</span>
            </label>

            <select name="category" class="form-select @error('category') is-invalid @enderror rounded-0">
                <option value="participant" @selected(old('category', $conferenceRegistrationType->category ?? 'participant') === 'participant')>
                    Participant
                </option>

                <option value="presenter" @selected(old('category', $conferenceRegistrationType->category ?? '') === 'presenter')>
                    Presenter
                </option>

            </select>

            @error('category')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Payment Timing --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Payment Timing
                <span class="text-danger">*</span>
            </label>

            <select name="payment_timing" class="form-select @error('payment_timing') is-invalid @enderror rounded-0">
                <option value="">
                    Select Payment Timing
                </option>

                <option value="immediate" @selected(old('payment_timing', $conferenceRegistrationType->payment_timing ?? '') === 'immediate')>
                    Pay during registration
                </option>

                <option value="after_acceptance" @selected(old('payment_timing', $conferenceRegistrationType->payment_timing ?? '') === 'after_acceptance')>
                    Pay after paper acceptance
                </option>

            </select>

            @error('payment_timing')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Determine when participants are required to make payment.
            </div>

        </div>

        {{-- Presenter information --}}
        <div class="col-md-6 mb-3">

            <div class="border border-info rounded-0 p-3 h-100 bg-info-subtle">

                <div class="small">

                    <strong>
                        Presenter Registration
                    </strong>

                    <div class="mt-1">
                        If payment is set to
                        <strong>
                            Pay after paper acceptance
                        </strong>,
                        participants can submit papers before making payment.
                    </div>

                </div>

            </div>

        </div>

        {{-- Pricing --}}
        <div class="col-12 mb-3">

            @php
                $selectedCategory = old('category', $conferenceRegistrationType->category ?? 'participant');
            @endphp

            <div id="participant-fee-section" @class([
                'border rounded-0 p-3',
                'd-none' => $selectedCategory === 'presenter',
            ])>

                <label class="form-label">
                    Registration Fee
                    <span class="text-danger">*</span>
                </label>

                <div class="row">

                    <div class="col-md-6">

                        <input type="number" name="fee" min="0" step="0.01"
                            value="{{ old('fee', $conferenceRegistrationType->fee ?? 0) }}"
                            class="form-control @error('fee') is-invalid @enderror rounded-0" placeholder="250000">

                        @error('fee')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <div class="form-text mt-md-2">
                            Registration fee for non-presenter participants.
                        </div>

                    </div>

                </div>

            </div>

            @php
                $presentationPrices = $conferenceRegistrationType?->presentationPrices ?? collect();

                $oralPrice = $presentationPrices->firstWhere('presentation_type', 'oral');

                $posterPrice = $presentationPrices->firstWhere('presentation_type', 'poster');

                $oralFee = old('oral_fee', $oralPrice?->fee ?? 0);

                $posterFee = old('poster_fee', $posterPrice?->fee ?? 0);

                $pricingCurrency =
                    $oralPrice?->currency ??
                    ($posterPrice?->currency ?? ($conferenceRegistrationType?->currency ?? 'IDR'));
            @endphp

            <div id="presenter-pricing-section" @class([
                'border rounded-0 p-3',
                'd-none' => $selectedCategory !== 'presenter',
            ])>

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <label class="form-label mb-0">
                            Presentation Pricing
                            <span class="text-danger">*</span>
                        </label>

                        <div class="form-text">
                            Set fees for each presentation type.
                        </div>

                    </div>

                    <span class="badge text-bg-primary rounded-0">
                        Presenter
                    </span>

                </div>

                <div class="row g-3">

                    {{-- Oral --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <label for="oral_fee" class="form-label">
                                <i class="bi bi-mic me-1"></i>
                                Oral Presentation
                            </label>

                            <div class="input-group">

                                <input type="number" id="oral_fee" name="oral_fee" min="0" step="0.01"
                                    value="{{ $oralFee }}"
                                    class="form-control @error('oral_fee') is-invalid @enderror rounded-0"
                                    placeholder="350000">

                                <span class="input-group-text rounded-0">
                                    {{ $pricingCurrency }}
                                </span>

                            </div>

                            @error('oral_fee')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    {{-- Poster --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <label for="poster_fee" class="form-label">
                                <i class="bi bi-image me-1"></i>
                                Poster Presentation
                            </label>

                            <div class="input-group">

                                <input type="number" id="poster_fee" name="poster_fee" min="0" step="0.01"
                                    value="{{ $posterFee }}"
                                    class="form-control @error('poster_fee') is-invalid @enderror rounded-0"
                                    placeholder="250000">

                                <span class="input-group-text rounded-0">
                                    {{ $pricingCurrency }}
                                </span>

                            </div>

                            @error('poster_fee')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

                <div class="form-text mt-3">
                    These fees are used when participants select Oral or Poster
                    Presentation after their paper is accepted.
                </div>

            </div>

        </div>

        {{-- Included Papers --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                Included Papers
                <span class="text-danger">*</span>
            </label>

            <input type="number" name="included_papers" min="0"
                value="{{ old('included_papers', $conferenceRegistrationType->included_papers ?? 0) }}"
                class="form-control @error('included_papers') is-invalid @enderror rounded-0">

            @error('included_papers')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Number of accepted papers included in this registration.
            </div>

        </div>

        {{-- Additional Paper Fee --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                Additional Paper Fee
                <span class="text-danger">*</span>
            </label>

            <input type="number" name="additional_paper_fee" min="0" step="0.01"
                value="{{ old('additional_paper_fee', $conferenceRegistrationType->additional_paper_fee ?? 0) }}"
                class="form-control @error('additional_paper_fee') is-invalid @enderror rounded-0">

            @error('additional_paper_fee')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Fee for each additional accepted paper.
            </div>

        </div>

        {{-- Currency --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                Currency
                <span class="text-danger">*</span>
            </label>

            <input type="text" name="currency"
                value="{{ old('currency', $conferenceRegistrationType->currency ?? 'IDR') }}"
                class="form-control @error('currency') is-invalid @enderror rounded-0" placeholder="IDR">

            @error('currency')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Sort Order --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                Sort Order
            </label>

            <input type="number" name="sort_order" min="0"
                value="{{ old('sort_order', $conferenceRegistrationType->sort_order ?? 0) }}"
                class="form-control @error('sort_order') is-invalid @enderror rounded-0">

            @error('sort_order')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Description --}}
        <div class="col-12 mb-3">

            <label class="form-label">
                Description
            </label>

            <textarea name="description" rows="4"
                class="form-control @error('description') is-invalid @enderror rounded-0"
                placeholder="Explain who this registration type is for...">{{ old('description', $conferenceRegistrationType->description ?? '') }}</textarea>

            @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Benefits --}}
        <div class="col-12 mb-3">

            <label class="form-label">
                Benefits
            </label>

            <textarea name="benefits" rows="5" class="form-control @error('benefits') is-invalid @enderror rounded-0"
                placeholder="One benefit per line...">{{ old('benefits', $conferenceRegistrationType->benefits ?? '') }}</textarea>

            @error('benefits')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Write one benefit per line.
            </div>

        </div>

        {{-- Status --}}
        <div class="col-md-3 mb-3">

            <label class="form-label d-block">
                Status
            </label>

            <div class="form-check form-switch mt-2">

                <input type="hidden" name="is_active" value="0">

                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    @checked(old('is_active', $conferenceRegistrationType->is_active ?? true))>

                <label class="form-check-label">
                    Active
                </label>

            </div>

        </div>

    </div>

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const categorySelect = document.querySelector(
                'select[name="category"]'
            );

            const participantFeeSection =
                document.getElementById(
                    'participant-fee-section'
                );

            const presenterPricingSection =
                document.getElementById(
                    'presenter-pricing-section'
                );

            if (
                !categorySelect ||
                !participantFeeSection ||
                !presenterPricingSection
            ) {
                return;
            }

            function togglePricingSections() {

                const isPresenter =
                    categorySelect.value === 'presenter';

                participantFeeSection.classList.toggle(
                    'd-none',
                    isPresenter
                );

                presenterPricingSection.classList.toggle(
                    'd-none',
                    !isPresenter
                );
            }

            categorySelect.addEventListener(
                'change',
                togglePricingSections
            );

            togglePricingSections();

        });
    </script>
@endpush
