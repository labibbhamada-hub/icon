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
                    <option value="{{ $conference->id }}" @selected(old('conference_id', $importantDate->conference_id ?? '') == $conference->id)>
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
        <div class="col-md-6">
            <label class="form-label">
                Event / Date Title
                <span class="text-danger">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $importantDate->title ?? '') }}"
                class="form-control @error('title') is-invalid @enderror rounded-0"
                placeholder="e.g. Abstract Submission Deadline">
            @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Type
                <span class="text-danger">*</span>
            </label>
            @php
                $types = [
                    'abstract_submission' => 'Abstract Submission',
                    'full_paper_submission' => 'Full Paper Submission',
                    'registration' => 'Registration',
                    'conference' => 'Conference',
                    'camera_ready' => 'Camera Ready',
                    'other' => 'Other',
                ];
            @endphp
            <select name="type" class="form-select @error('type') is-invalid @enderror rounded-0">
                @foreach ($types as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $importantDate->type ?? 'other') === $value)>
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
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Sort Order
            </label>
            <input type="number" name="sort_order" min="0"
                value="{{ old('sort_order', $importantDate->sort_order ?? 0) }}"
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
                    @checked(old('is_active', $importantDate->is_active ?? true))>
                <label class="form-check-label">
                    Active
                </label>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Start Date
                <span class="text-danger">*</span>
            </label>
            <input type="date" name="date"
                value="{{ old('date', isset($importantDate->date) ? $importantDate->date->format('Y-m-d') : '') }}"
                class="form-control @error('date') is-invalid @enderror rounded-0">
            @error('date')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                End Date
                <small class="text-muted">
                    (Optional)
                </small>
            </label>
            <input type="date" name="end_date"
                value="{{ old('end_date', isset($importantDate->end_date) ? $importantDate->end_date->format('Y-m-d') : '') }}"
                class="form-control @error('end_date') is-invalid @enderror rounded-0">
            @error('end_date')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <div class="form-text">
                Leave empty if this event only has one date.
            </div>
        </div>
        <div class="col-12 mb-2">
            <label class="form-label">
                Description
            </label>
            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror rounded-0"
                placeholder="Write additional information about this date...">{{ old('description', $importantDate->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
