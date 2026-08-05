<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-1">
            <i class="bi bi-calendar-event me-2 text-success"></i>
            Conference Information
        </h5>
        <small class="text-muted">
            Basic information about the conference.
        </small>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    Conference Name
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Short Name
                </label>
                <input type="text" name="short_name" value="{{ old('short_name') }}"
                    class="form-control @error('short_name') is-invalid @enderror">
                @error('short_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Year
                </label>
                <input type="number" name="year" value="{{ old('year', 2026) }}"
                    class="form-control @error('year') is-invalid @enderror">
            </div>
            <div class="col-12">
                <label class="form-label">
                    Theme
                </label>
                <textarea rows="3" name="theme" class="form-control">{{ old('theme') }}</textarea>
            </div>
        </div>
    </div>
</div>
