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
                    <option value="{{ $conference->id }}" @selected(old('conference_id', $participant->conference_id ?? '') == $conference->id)>
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
                Registration Number
                <span class="text-danger">*</span>
            </label>
            <input type="text" name="registration_number"
                value="{{ old('registration_number', $participant->registration_number ?? '') }}"
                class="form-control @error('registration_number') is-invalid @enderror rounded-0"
                placeholder="e.g. ICON2026-0001">
            @error('registration_number')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Participant Type
                <span class="text-danger">*</span>
            </label>
            @php
                $participantTypes = [
                    'regular' => 'Regular',
                    'student' => 'Student',
                    'speaker' => 'Speaker',
                    'committee' => 'Committee',
                ];
            @endphp
            <select name="participant_type"
                class="form-select @error('participant_type') is-invalid @enderror rounded-0">
                @foreach ($participantTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('participant_type', $participant->participant_type ?? 'regular') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('participant_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Attendance Type
                <span class="text-danger">*</span>
            </label>
            @php
                $attendanceTypes = [
                    'offline' => 'Offline',
                    'online' => 'Online',
                    'hybrid' => 'Hybrid',
                ];
            @endphp
            <select name="attendance_type" class="form-select @error('attendance_type') is-invalid @enderror rounded-0">
                @foreach ($attendanceTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('attendance_type', $participant->attendance_type ?? 'offline') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('attendance_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Registration Status
                <span class="text-danger">*</span>
            </label>
            @php
                $registrationStatuses = [
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'cancelled' => 'Cancelled',
                ];
            @endphp
            <select name="registration_status"
                class="form-select @error('registration_status') is-invalid @enderror rounded-0">
                @foreach ($registrationStatuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('registration_status', $participant->registration_status ?? 'pending') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('registration_status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Registered At
            </label>
            <input type="datetime-local" name="registered_at"
                value="{{ old(
                    'registered_at',
                    isset($participant->registered_at) ? $participant->registered_at->format('Y-m-d\TH:i') : '',
                ) }}"
                class="form-control @error('registered_at') is-invalid @enderror rounded-0">
            @error('registered_at')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="card-body border-top">
    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Full Name
                <span class="text-danger">*</span>
            </label>
            <input type="text" name="full_name" value="{{ old('full_name', $participant->full_name ?? '') }}"
                class="form-control @error('full_name') is-invalid @enderror rounded-0" placeholder="Full name">
            @error('full_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Email Address
                <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email', $participant->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror rounded-0" placeholder="name@example.com">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Phone Number
            </label>
            <input type="text" name="phone" value="{{ old('phone', $participant->phone ?? '') }}"
                class="form-control @error('phone') is-invalid @enderror rounded-0" placeholder="+62 812 3456 7890">
            @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Country
                <span class="text-danger">*</span>
            </label>
            <input type="text" name="country" value="{{ old('country', $participant->country ?? 'Indonesia') }}"
                class="form-control @error('country') is-invalid @enderror rounded-0">
            @error('country')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">
                City
            </label>
            <input type="text" name="city" value="{{ old('city', $participant->city ?? '') }}"
                class="form-control @error('city') is-invalid @enderror rounded-0">
            @error('city')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="card-body border-top">
    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Institution
            </label>
            <input type="text" name="institution" value="{{ old('institution', $participant->institution ?? '') }}"
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
                Department
            </label>
            <input type="text" name="department" value="{{ old('department', $participant->department ?? '') }}"
                class="form-control @error('department') is-invalid @enderror rounded-0"
                placeholder="Department / Faculty">
            @error('department')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="card-body border-top">
    <div class="row">
        <div class="col-12 mb-2">
            <label class="form-label">
                Notes
            </label>
            <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror rounded-0"
                placeholder="Additional notes about this participant...">{{ old('notes', $participant->notes ?? '') }}</textarea>
            @error('notes')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
