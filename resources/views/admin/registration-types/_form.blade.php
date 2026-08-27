<div class="card-body">
    <div class="row">
        <div class="col-md-6 mb-2">
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
                        {{ $conference->short_name }} ({{ $conference->year }})
                    </option>
                @endforeach
            </select>
            @error('conference_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
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
        <div class="col-md-6 mb-2">
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
        <div class="col-md-6 mb-2">
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
        <div class="col-md-6 mb-2">
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
        <div class="col-md-6 mb-2">
            <div class="border border-info rounded-0 p-3 h-100 bg-info-subtle">
                <div class="small">
                    <strong>
                        Presenter registration
                    </strong>
                    <div class="mt-1">
                        If payment is set to
                        <strong>Pay after paper acceptance</strong>,
                        participants can submit papers before making payment.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                @if (old('payment_timing', $conferenceRegistrationType->payment_timing ?? '') === 'after_acceptance')
                    Base Registration Fee
                @else
                    Registration Fee
                @endif
                <span class="text-danger">*</span>
            </label>
            <input type="number" name="fee" min="0" step="0.01"
                value="{{ old('fee', $conferenceRegistrationType->fee ?? 0) }}"
                class="form-control @error('fee') is-invalid @enderror rounded-0" placeholder="0">
            @error('fee')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3 mb-2">
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
        <div class="col-md-3 mb-2">
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
        <div class="col-md-6 mb-2">
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
        <div class="col-md-12 mb-2">
            <label class="form-label">
                Description
            </label>
            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror rounded-0"
                placeholder="Explain who this registration type is for...">{{ old('description', $conferenceRegistrationType->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-12 mb-2">
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
        <div class="col-md-3 mb-2">
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
        <div class="col-md-3 mb-2">
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
