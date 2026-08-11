<div class="card-body">
    <div class="row">
        <div class="col-md-3">
            <label class="form-label">
                Partner Logo
            </label>
            <div class="mb-2">
                @if (isset($partner) && $partner->logo)
                    <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" id="logo-preview"
                        class="img-thumbnail d-block rounded-0" style="width: 180px; height: 180px; object-fit: contain;">
                @else
                    <div id="logo-placeholder"
                        class="border rounded-0 d-flex align-items-center justify-content-center bg-light"
                        style="width: 180px;height: 180px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-building display-4"></i>
                            <div class="small mt-2">
                                No Logo
                            </div>
                        </div>
                    </div>
                    <img src="" alt="Preview" id="logo-preview" class="img-thumbnail rounded-0 d-none"
                        style="width: 180px; height: 180px; object-fit: contain;">
                @endif
            </div>
            <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/webp"
                class="form-control @error('logo') is-invalid @enderror rounded-0">
            <div class="form-text">
                JPG, PNG, or WebP. Maximum 2 MB.
            </div>
            @error('logo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Conference
                        <span class="text-danger">*</span>
                    </label>
                    <select name="conference_id"
                        class="form-select @error('conference_id') is-invalid @enderror rounded-0">
                        <option value="">
                            Select Conference
                        </option>
                        @foreach ($conferences as $conference)
                            <option value="{{ $conference->id }}" @selected(old('conference_id', $partner->conference_id ?? '') == $conference->id)>
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
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Partner Name
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $partner->name ?? '') }}"
                        class="form-control @error('name') is-invalid @enderror rounded-0"
                        placeholder="e.g. Universitas Bhamada">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Partner Type
                        <span class="text-danger">*</span>
                    </label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror rounded-0">
                        @php
                            $types = [
                                'university' => 'University',
                                'government' => 'Government',
                                'sponsor' => 'Sponsor',
                                'media_partner' => 'Media Partner',
                                'community' => 'Community',
                                'institution' => 'Institution',
                                'partner' => 'Partner',
                                'other' => 'Other',
                            ];
                        @endphp
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $partner->type ?? 'partner') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Website
                    </label>
                    <input type="url" name="website" value="{{ old('website', $partner->website ?? '') }}"
                        class="form-control @error('website') is-invalid @enderror rounded-0"
                        placeholder="https://example.com">
                    @error('website')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">
                        Description
                    </label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror rounded-0"
                        placeholder="Write a short description about the partner...">{{ old('description', $partner->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Sort Order
            </label>
            <input type="number" name="sort_order" min="0"
                value="{{ old('sort_order', $partner->sort_order ?? 0) }}"
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
                    @checked(old('is_active', $partner->is_active ?? true))>
                <label class="form-check-label">
                    Active
                </label>
            </div>
        </div>
    </div>
</div>
