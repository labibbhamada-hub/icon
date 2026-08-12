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
                    <option value="{{ $conference->id }}" @selected(old('conference_id', $reviewer->conference_id ?? '') == $conference->id)>
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
                Reviewer User
                <span class="text-danger">*</span>
            </label>
            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror rounded-0">
                <option value="">
                    Select Reviewer
                </option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $reviewer->user_id ?? '') == $user->id)>
                        {{ $user->name }}
                        — {{ $user->email }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">
                Only users with reviewer role are shown.
            </div>
            @error('user_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Institution
            </label>
            <input type="text" name="institution" value="{{ old('institution', $reviewer->institution ?? '') }}"
                class="form-control @error('institution') is-invalid @enderror rounded-0"
                placeholder="University / Institution">
            @error('institution')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Expertise
            </label>
            <input type="text" name="expertise" value="{{ old('expertise', $reviewer->expertise ?? '') }}"
                class="form-control @error('expertise') is-invalid @enderror rounded-0"
                placeholder="AI, Machine Learning, Data Science">
            <div class="form-text">
                Separate multiple expertise areas with commas.
            </div>
            @error('expertise')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-12 mb-2">
            <label class="form-label">
                Biography
            </label>
            <textarea name="bio" rows="5" class="form-control @error('bio') is-invalid @enderror rounded-0"
                placeholder="Write reviewer biography...">{{ old('bio', $reviewer->bio ?? '') }}</textarea>
            @error('bio')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label d-block">
                Status
            </label>
            <div class="form-check form-switch">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    @checked(old('is_active', $reviewer->is_active ?? true))>
                <label class="form-check-label">
                    Active
                </label>
            </div>
        </div>
    </div>
</div>
