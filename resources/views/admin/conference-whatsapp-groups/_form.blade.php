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
                    <option value="{{ $conference->id }}" @selected(old('conference_id', $conferenceWhatsappGroup->conference_id ?? '') == $conference->id)>
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

        {{-- Group Title --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Group Title
                <span class="text-danger">*</span>
            </label>

            <input type="text" name="title" value="{{ old('title', $conferenceWhatsappGroup->title ?? '') }}"
                class="form-control @error('title') is-invalid @enderror rounded-0"
                placeholder="e.g. BHAMADA ICON 2026 Presenter Group">

            @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Group URL --}}
        <div class="col-md-12 mb-3">

            <label class="form-label">
                WhatsApp Group URL
                <span class="text-danger">*</span>
            </label>

            <input type="url" name="group_url"
                value="{{ old('group_url', $conferenceWhatsappGroup->group_url ?? '') }}"
                class="form-control @error('group_url') is-invalid @enderror rounded-0"
                placeholder="https://chat.whatsapp.com/...">

            @error('group_url')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Enter the WhatsApp group invitation link that participants will use to join the group.
            </div>

        </div>

        {{-- Description --}}
        <div class="col-12 mb-3">

            <label class="form-label">
                Description
                <small class="text-muted">
                    (Optional)
                </small>
            </label>

            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror rounded-0"
                placeholder="Write information for participants...">{{ old('description', $conferenceWhatsappGroup->description ?? '') }}</textarea>

            @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Additional information such as group purpose, communication guidelines, or reminders.
            </div>

        </div>

        {{-- Status --}}
        <div class="col-md-12 mb-2">

            <label class="form-label d-block">
                Status
            </label>

            <div class="form-check form-switch mt-2">

                <input type="hidden" name="is_active" value="0">

                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    @checked(old('is_active', $conferenceWhatsappGroup->is_active ?? true))>

                <label class="form-check-label">
                    Active
                </label>

            </div>

        </div>

    </div>

</div>
