<div class="card-body">

    <div class="row">

        {{-- Conference --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Conference
                <span class="text-danger">*</span>
            </label>

            <select name="conference_id" id="conference_id"
                class="form-select @error('conference_id') is-invalid @enderror rounded-0">
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

        {{-- Registration Type --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Registration Type
                <span class="text-danger">*</span>
            </label>

            <select name="registration_type_id" id="registration_type_id"
                class="form-select @error('registration_type_id') is-invalid @enderror rounded-0">
                <option value="">
                    Select Registration Type
                </option>

                @foreach ($conferences as $conference)
                    @if ($conference->registrationTypes->isNotEmpty())
                        <optgroup label="{{ $conference->short_name }} ({{ $conference->year }})"
                            data-conference-id="{{ $conference->id }}">

                            @foreach ($conference->registrationTypes as $registrationType)
                                <option value="{{ $registrationType->id }}" data-conference-id="{{ $conference->id }}"
                                    @selected(old('registration_type_id', $participant->registration_type_id ?? '') == $registrationType->id)>
                                    {{ $registrationType->name }}
                                    — {{ ucfirst($registrationType->category) }}
                                </option>
                            @endforeach

                        </optgroup>
                    @endif
                @endforeach

            </select>

            @error('registration_type_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Select a registration type belonging to the selected conference.
            </div>

        </div>

        {{-- Registration Number --}}
        <div class="col-md-6 mb-3">

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

        {{-- Participant Type --}}
        <div class="col-md-4 mb-3">

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

        {{-- Attendance Type --}}
        <div class="col-md-4 mb-3">

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

        {{-- Registration Status --}}
        <div class="col-md-4 mb-3">

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

        {{-- Registered At --}}
        <div class="col-md-4 mb-3">

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

        {{-- Full Name --}}
        <div class="col-md-6 mb-3">

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

        {{-- Email --}}
        <div class="col-md-6 mb-3">

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

        {{-- Phone --}}
        <div class="col-md-6 mb-3">

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

        {{-- Country --}}
        <div class="col-md-3 mb-3">

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

        {{-- City --}}
        <div class="col-md-3 mb-3">

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

        {{-- Institution --}}
        <div class="col-md-6 mb-3">

            <label class="form-label">
                Institution
            </label>

            <input type="text" name="institution"
                value="{{ old('institution', $participant->institution ?? '') }}"
                class="form-control @error('institution') is-invalid @enderror rounded-0"
                placeholder="University / Institution">

            @error('institution')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- Department --}}
        <div class="col-md-6 mb-3">

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

        <div class="col-12">

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


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const conferenceSelect =
                document.getElementById('conference_id');

            const registrationTypeSelect =
                document.getElementById('registration_type_id');

            if (
                !conferenceSelect ||
                !registrationTypeSelect
            ) {
                return;
            }

            function filterRegistrationTypes() {

                const conferenceId =
                    conferenceSelect.value;

                Array.from(
                    registrationTypeSelect.options
                ).forEach(function(option) {

                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }

                    option.hidden =
                        option.dataset.conferenceId !== conferenceId;

                });

                const selectedOption =
                    registrationTypeSelect.options[
                        registrationTypeSelect.selectedIndex
                    ];

                if (
                    selectedOption &&
                    selectedOption.value &&
                    selectedOption.dataset.conferenceId !== conferenceId
                ) {
                    registrationTypeSelect.value = '';
                }

            }

            conferenceSelect.addEventListener(
                'change',
                filterRegistrationTypes
            );

            filterRegistrationTypes();

        });
    </script>
@endpush
