@extends('layouts.participant')

@section('title', 'Register for ICON 2026')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.registration.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Register for {{ $conference?->short_name ?? 'Conference' }}
                </h1>
            </div>
            <p class="text-muted mb-0">
                Complete your conference registration.
            </p>
        </div>

        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('participant.registration.index') }}">
                        Registration
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Register
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

    @if (!$conference)

        <div class="alert alert-info rounded-0">
            <i class="bi bi-info-circle me-2"></i>
            There is currently no conference open for registration.
        </div>
    @else
        @php
            $attendanceOptions = $conference->attendanceOptions->sortBy('sort_order')->values();

            $selectedAttendance = old(
                'attendance_type',
                $attendanceOptions->count() === 1 ? $attendanceOptions->first()?->type : null,
            );
        @endphp

        <form action="{{ route('participant.registration.store') }}" method="POST">

            @csrf

            <input type="hidden" name="conference_id" value="{{ $conference->id }}">

            {{-- =========================================================
                 REGISTRATION TYPE
            ========================================================== --}}
            <div class="card rounded-0 mb-3">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-person-check me-2"></i>
                        How will you participate?
                    </h3>
                </div>
                <div class="card-body">
                    @error('registration_type_id')
                        <div class="alert alert-danger rounded-0 mb-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-exclamation-circle fs-5"></i>
                                <div>
                                    <strong>
                                        Registration Type Required
                                    </strong>
                                    <div class="small mt-1">
                                        Please choose how you would like to participate
                                        in this conference.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @enderror
                    <div class="row g-3">
                        @foreach ($conference->registrationTypes as $registrationType)
                            <div class="col-md-6">
                                <label class="d-block h-100">
                                    <input type="radio" name="registration_type_id" value="{{ $registrationType->id }}"
                                        class="btn-check registration-type-option" @checked(old('registration_type_id') == $registrationType->id)>
                                    <div class="border rounded-0 p-3 h-100 registration-type-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <h5 class="fw-bold mb-0">
                                                    {{ $registrationType->name }}
                                                </h5>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">
                                                    @if ($registrationType->category === 'presenter')
                                                        Fee After Acceptance
                                                    @else
                                                        Registration Fee
                                                    @endif
                                                </small>
                                                <strong class="text-success">
                                                    {{ $registrationType->currency }}
                                                    {{ number_format($registrationType->fee, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                        </div>
                                        @if ($registrationType->category === 'presenter')
                                            <div class="mt-3 pt-2 border-top">
                                                <div class="small text-muted">
                                                    <i class="bi bi-file-earmark-check me-1"></i>
                                                    Includes
                                                    {{ $registrationType->included_papers }}
                                                    accepted
                                                    {{ $registrationType->included_papers === 1 ? 'paper' : 'papers' }}.
                                                </div>
                                                @if ($registrationType->additional_paper_fee > 0)
                                                    <div class="small text-muted mt-1">

                                                        <i class="bi bi-plus-circle me-1"></i>

                                                        Additional accepted paper:
                                                        {{ $registrationType->currency }}
                                                        {{ number_format($registrationType->additional_paper_fee, 0, ',', '.') }}
                                                        / paper.

                                                    </div>
                                                @endif

                                                <div class="small text-muted mt-1">

                                                    <i class="bi bi-info-circle me-1"></i>

                                                    Payment is required only after your paper is accepted.

                                                </div>

                                            </div>
                                        @endif

                                        {{-- Description --}}
                                        @if ($registrationType->description)
                                            <p class="text-muted mt-3 mb-2">
                                                {{ $registrationType->description }}
                                            </p>
                                        @endif

                                        {{-- Benefits --}}
                                        @if ($registrationType->benefits)
                                            <div class="mt-2">

                                                <small class="fw-semibold d-block mb-1">
                                                    Includes:
                                                </small>

                                                <ul class="mb-0 ps-3">

                                                    @foreach (preg_split('/\r\n|\r|\n/', $registrationType->benefits) as $benefit)
                                                        @if (trim($benefit))
                                                            <li>
                                                                {{ trim($benefit) }}
                                                            </li>
                                                        @endif
                                                    @endforeach

                                                </ul>

                                            </div>
                                        @endif

                                    </div>

                                </label>

                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            {{-- =========================================================
                 YOUR INFORMATION
            ========================================================== --}}
            <div class="card rounded-0 mb-3">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-person-lines-fill me-2"></i>
                        Your Information
                    </h3>
                </div>

                <div class="card-body">

                    <div class="alert alert-info rounded-0">

                        <i class="bi bi-info-circle me-2"></i>

                        This information will be used for your conference
                        registration and certificate.

                    </div>

                    <div class="row">

                        {{-- Full Name --}}
                        <div class="col-md-6 mb-2">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input type="text" value="{{ auth()->user()->name }}" class="form-control rounded-0"
                                readonly>

                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 mb-2">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email" value="{{ auth()->user()->email }}" class="form-control rounded-0"
                                readonly>

                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6 mb-2">

                            <label for="phone" class="form-label">
                                Phone / WhatsApp
                            </label>

                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="form-control rounded-0 @error('phone') is-invalid @enderror"
                                placeholder="e.g. 081234567890">

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Used for conference notifications via WhatsApp.
                            </div>

                        </div>

                        {{-- Institution --}}
                        <div class="col-md-6 mb-2">

                            <label for="institution" class="form-label">
                                Institution
                            </label>

                            <input type="text" id="institution" name="institution" value="{{ old('institution') }}"
                                class="form-control rounded-0 @error('institution') is-invalid @enderror"
                                placeholder="University / Institution">

                            @error('institution')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Department --}}
                        <div class="col-md-6 mb-2">

                            <label for="department" class="form-label">
                                Department
                            </label>

                            <input type="text" id="department" name="department" value="{{ old('department') }}"
                                class="form-control rounded-0 @error('department') is-invalid @enderror"
                                placeholder="Department / Faculty">

                            @error('department')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Country --}}
                        <div class="col-md-3 mb-2">

                            <label for="country" class="form-label">
                                Country
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" id="country" name="country"
                                value="{{ old('country', 'Indonesia') }}"
                                class="form-control rounded-0 @error('country') is-invalid @enderror">

                            @error('country')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- City --}}
                        <div class="col-md-3 mb-2">

                            <label for="city" class="form-label">
                                City
                            </label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}"
                                class="form-control rounded-0 @error('city') is-invalid @enderror" placeholder="City">
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">
                                Attendance
                                <span class="text-danger">*</span>
                            </label>
                            @if ($attendanceOptions->isEmpty())
                                <div class="alert alert-warning rounded-0 mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Attendance options have not been configured
                                    for this conference.
                                </div>
                            @elseif ($attendanceOptions->count() === 1)
                                @php
                                    $attendance = $attendanceOptions->first();
                                @endphp
                                <input type="hidden" name="attendance_type" value="{{ $attendance->type }}">
                                <div class="border rounded-0 p-3 bg-light">
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($attendance->type === 'online')
                                            <i class="bi bi-camera-video fs-4 text-success"></i>
                                        @elseif ($attendance->type === 'offline')
                                            <i class="bi bi-building fs-4 text-primary"></i>
                                        @else
                                            <i class="bi bi-diagram-3 fs-4 text-warning"></i>
                                        @endif
                                        <div>
                                            <small class="text-muted d-block">
                                                Conference Attendance
                                            </small>
                                            <strong>
                                                {{ ucfirst($attendance->type) }}
                                            </strong>
                                            <div class="small text-muted mt-1">
                                                This conference currently supports
                                                {{ $attendance->type }} attendance.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @error('attendance_type')
                                    <div class="alert alert-danger rounded-0 mt-3 mb-0">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="bi bi-exclamation-circle fs-5"></i>
                                            <div>
                                                <strong>
                                                    Attendance Required
                                                </strong>
                                                <div class="small mt-1">
                                                    {{ $message }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @enderror
                                <div class="row g-2">
                                    @foreach ($attendanceOptions as $attendance)
                                        <div class="col-md-4">
                                            <label class="d-block h-100">
                                                <input type="radio" name="attendance_type"
                                                    value="{{ $attendance->type }}" class="btn-check"
                                                    @checked($selectedAttendance === $attendance->type)>
                                                <span class="btn btn-outline-secondary w-100 h-100 rounded-0 p-3">
                                                    @if ($attendance->type === 'online')
                                                        <i class="bi bi-camera-video me-1"></i>
                                                    @elseif ($attendance->type === 'offline')
                                                        <i class="bi bi-building me-1"></i>
                                                    @else
                                                        <i class="bi bi-diagram-3 me-1"></i>
                                                    @endif
                                                    {{ ucfirst($attendance->type) }}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Footer --}}
                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div class="text-muted small">

                            <i class="bi bi-shield-check me-1"></i>

                            Please review your information before submitting.

                        </div>

                        <button type="submit" class="btn btn-success rounded-0">

                            <i class="bi bi-check-circle me-1"></i>
                            Complete Registration

                        </button>

                    </div>

                </div>

            </div>

        </form>

    @endif

@endsection

@push('styles')
    <style>
        .registration-type-card {
            cursor: pointer;
            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }

        .registration-type-card:hover {
            border-color:
                var(--bs-primary) !important;
        }

        .btn-check:checked+.registration-type-card {
            border-color:
                var(--bs-primary) !important;

            box-shadow:
                0 0 0 .15rem rgba(var(--bs-primary-rgb), .15);
        }
    </style>
@endpush
