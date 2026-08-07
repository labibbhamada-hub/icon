<div class="card-body">
    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Conference Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $conference->name ?? '') }}"
                class="form-control rounded-0 @error('name') is-invalid @enderror">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Short Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="short_name" value="{{ old('short_name', $conference->short_name ?? '') }}"
                class="form-control rounded-0 @error('short_name') is-invalid @enderror">
            @error('short_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Year <span class="text-danger">*</span>
            </label>
            <input type="number" name="year" value="{{ old('year', $conference->year ?? date('Y')) }}"
                class="form-control rounded-0 @error('year') is-invalid @enderror">
            @error('year')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label">
                Theme
            </label>
            <textarea rows="4" name="theme" class="form-control rounded-0 @error('theme') is-invalid @enderror">{{ old('theme', $conference->theme ?? '') }}</textarea>
            @error('theme')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="card-body border-top">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">
                Venue
            </label>
            <input type="text" name="venue" value="{{ old('venue', $conference->venue ?? '') }}"
                class="form-control rounded-0">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">
                City
            </label>
            <input type="text" name="city" value="{{ old('city', $conference->city ?? '') }}"
                class="form-control rounded-0">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">
                Country
            </label>
            <input type="text" name="country" value="{{ old('country', $conference->country ?? 'Indonesia') }}"
                class="form-control rounded-0">
        </div>
    </div>
</div>
<div class="card-body border-top">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">
                Start Date
            </label>
            <input type="date" name="start_date"
                value="{{ old('start_date', isset($conference) && $conference->start_date ? $conference->start_date->format('Y-m-d') : '') }}"
                class="form-control rounded-0">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">
                End Date
            </label>
            <input type="date" name="end_date"
                value="{{ old('end_date', isset($conference) && $conference->end_date ? $conference->end_date->format('Y-m-d') : '') }}"
                class="form-control rounded-0">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Abstract Deadline
            </label>
            <input type="date" name="abstract_deadline"
                value="{{ old('abstract_deadline', isset($conference) && $conference->abstract_deadline ? $conference->abstract_deadline->format('Y-m-d') : '') }}"
                class="form-control rounded-0">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Full Paper Deadline
            </label>
            <input type="date" name="fullpaper_deadline"
                value="{{ old('fullpaper_deadline', isset($conference) && $conference->fullpaper_deadline ? $conference->fullpaper_deadline->format('Y-m-d') : '') }}"
                class="form-control rounded-0">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Registration Deadline
            </label>
            <input type="date" name="registration_deadline"
                value="{{ old('registration_deadline', isset($conference) && $conference->registration_deadline ? $conference->registration_deadline->format('Y-m-d') : '') }}"
                class="form-control rounded-0">
        </div>
    </div>
</div>
<div class="card-body border-top">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">
                Logo
            </label>
            @if (isset($conference) && $conference->logo)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $conference->logo) }}" class="img-thumbnail rounded-0"
                        style="max-height:200px;">
                </div>
            @endif
            <input type="file" name="logo" class="form-control rounded-0">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">
                Banner
            </label>
            @if (isset($conference) && $conference->banner)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $conference->banner) }}" class="img-thumbnail rounded-0"
                        style="max-height:200px;">
                </div>
            @endif
            <input type="file" name="banner" class="form-control rounded-0">
        </div>
    </div>
</div>
<div class="card-body border-top">
    <div class="row">
        <div class="col-md-4">
            <label class="form-label">
                Status
            </label>
            <select name="status" class="form-select rounded-0">
                @foreach (['draft' => 'Draft', 'registration_open' => 'Registration Open', 'submission_open' => 'Submission Open', 'review' => 'Review', 'camera_ready' => 'Camera Ready', 'closed' => 'Closed', 'archived' => 'Archived'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $conference->status ?? 'draft') == $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
