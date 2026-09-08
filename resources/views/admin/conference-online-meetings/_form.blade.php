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
                    <option value="{{ $conference->id }}" @selected(old('conference_id', $conferenceOnlineMeeting->conference_id ?? '') == $conference->id)>
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

        {{-- Meeting Title --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Meeting Title
                <span class="text-danger">*</span>
            </label>

            <input type="text" name="title" value="{{ old('title', $conferenceOnlineMeeting->title ?? '') }}"
                class="form-control @error('title') is-invalid @enderror rounded-0"
                placeholder="e.g. BHAMADA ICON 2026 Main Conference">

            @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Meeting URL --}}
        <div class="col-md-12 mb-3">

            <label class="form-label">
                Meeting URL
                <span class="text-danger">*</span>
            </label>

            <input type="url" name="meeting_url"
                value="{{ old('meeting_url', $conferenceOnlineMeeting->meeting_url ?? '') }}"
                class="form-control @error('meeting_url') is-invalid @enderror rounded-0"
                placeholder="https://zoom.us/j/...">

            @error('meeting_url')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Enter the link that participants will use to join the online meeting.
            </div>

        </div>

        {{-- Meeting ID --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Meeting ID
                <small class="text-muted">
                    (Optional)
                </small>
            </label>

            <input type="text" name="meeting_id"
                value="{{ old('meeting_id', $conferenceOnlineMeeting->meeting_id ?? '') }}"
                class="form-control @error('meeting_id') is-invalid @enderror rounded-0" placeholder="e.g. 123 456 789">

            @error('meeting_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Passcode --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Passcode
                <small class="text-muted">
                    (Optional)
                </small>
            </label>

            <input type="text" name="passcode"
                value="{{ old('passcode', $conferenceOnlineMeeting->passcode ?? '') }}"
                class="form-control @error('passcode') is-invalid @enderror rounded-0" placeholder="e.g. ICON2026">

            @error('passcode')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Instructions --}}
        <div class="col-12 mb-3">

            <label class="form-label">
                Instructions
                <small class="text-muted">
                    (Optional)
                </small>
            </label>

            <textarea name="instructions" rows="4" class="form-control @error('instructions') is-invalid @enderror rounded-0"
                placeholder="Write instructions for participants...">{{ old('instructions', $conferenceOnlineMeeting->instructions ?? '') }}</textarea>

            @error('instructions')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Additional information such as joining instructions, preparation, or reminders.
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
                    @checked(old('is_active', $conferenceOnlineMeeting->is_active ?? true))>

                <label class="form-check-label">
                    Active
                </label>

            </div>

        </div>

    </div>

</div>
