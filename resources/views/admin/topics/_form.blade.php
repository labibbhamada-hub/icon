<div class="card-body">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">
                Conference
            </label>
            <select name="conference_id" class="form-select @error('conference_id') is-invalid @enderror rounded-0">
                @foreach ($conferences as $conference)
                    <option value="{{ $conference->id }}" @selected(old('conference_id', $topic->conference_id ?? '') == $conference->id)>
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
        <div class="col-md-6 mb-3">
            <label class="form-label">
                Topic Name
            </label>
            <input type="text" name="name" value="{{ old('name', $topic->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror rounded-0">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">
                Description
            </label>
            <textarea rows="4" name="description" class="form-control rounded-0">{{ old('description', $topic->description ?? '') }}</textarea>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Bootstrap Icon
            </label>
            <input type="text" name="icon" value="{{ old('icon', $topic->icon ?? '') }}"
                class="form-control rounded-0">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Color
            </label>
            <select name="color" class="form-select rounded-0">
                @foreach (['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'] as $color)
                    <option value="{{ $color }}" @selected(old('color', $topic->color ?? 'primary') == $color)>
                        {{ ucfirst($color) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">
                Sort
            </label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $topic->sort_order ?? 0) }}"
                class="form-control rounded-0">
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label d-block">
                Status
            </label>
            <div class="form-check form-switch mt-2">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    @checked(old('is_active', $topic->is_active ?? true))>
                <label class="form-check-label">
                    Active
                </label>
            </div>
        </div>
    </div>
</div>
