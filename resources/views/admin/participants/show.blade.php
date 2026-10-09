@extends('layouts.admin')

@section('title', 'Participant Detail')

@section('header')
    <div class="row">
        <div class="col-sm-8">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Participant Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View the details and registration information of this conference participant.
            </p>
        </div>
    </div>
@endsection

@section('content')
    {{-- Participant Summary --}}
    <div class="card rounded-0 overflow-hidden mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-person-vcard me-2"></i>
                Participant Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="border rounded-0 p-3 h-100">
                        <h5 class="fw-bold mb-3">
                            Registration
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">
                                Registration Number
                            </dt>
                            <dd class="col-sm-7 fw-semibold">
                                {{ $participant->registration_number }}
                            </dd>
                            <dt class="col-sm-5">
                                Conference
                            </dt>
                            <dd class="col-sm-7">
                                @if ($participant->conference)
                                    <strong>
                                        {{ $participant->conference->name }}
                                    </strong>
                                    <small class="text-muted d-block">
                                        {{ $participant->conference->short_name }}
                                        ({{ $participant->conference->year }})
                                    </small>
                                @else
                                    -
                                @endif
                            </dd>
                            <dt class="col-sm-5">
                                Registration Type
                            </dt>
                            <dd class="col-sm-7">
                                @if ($participant->registrationType)
                                    <strong>
                                        {{ $participant->registrationType->name }}
                                    </strong>
                                    <small class="text-muted d-block">
                                        {{ ucfirst($participant->registrationType->category) }}
                                    </small>
                                @else
                                    -
                                @endif
                            </dd>
                            <dt class="col-sm-5">
                                Attendance
                            </dt>
                            <dd class="col-sm-7">
                                {{ ucfirst($participant->attendance_type) }}
                            </dd>
                            <dt class="col-sm-5">
                                Registration Status
                            </dt>
                            <dd class="col-sm-7">
                                @if ($participant->registration_status === 'confirmed')
                                    <span class="badge text-bg-success rounded-0">
                                        Confirmed
                                    </span>
                                @elseif ($participant->registration_status === 'cancelled')
                                    <span class="badge text-bg-danger rounded-0">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="badge text-bg-warning rounded-0">
                                        Pending
                                    </span>
                                @endif
                            </dd>
                            <dt class="col-sm-5">
                                Registered At
                            </dt>
                            <dd class="col-sm-7">
                                {{ $participant->registered_at?->format('d F Y H:i') ?? '-' }}
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <div class="border rounded-0 p-3 h-100">
                        <h5 class="fw-bold mb-3">
                            Personal Information
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">
                                Full Name
                            </dt>
                            <dd class="col-sm-8">
                                {{ $participant->full_name }}
                            </dd>
                            <dt class="col-sm-4">
                                Email
                            </dt>
                            <dd class="col-sm-8">
                                <a href="mailto:{{ $participant->email }}">
                                    {{ $participant->email }}
                                </a>
                            </dd>
                            <dt class="col-sm-4">
                                Phone
                            </dt>
                            <dd class="col-sm-8">
                                {{ $participant->phone ?: '-' }}
                            </dd>
                            <dt class="col-sm-4">
                                Participant Type
                            </dt>
                            <dd class="col-sm-8">
                                {{ ucfirst($participant->participant_type) }}
                            </dd>
                            <dt class="col-sm-4">
                                Institution
                            </dt>
                            <dd class="col-sm-8">
                                {{ $participant->institution ?: '-' }}
                            </dd>
                            <dt class="col-sm-4">
                                Department
                            </dt>
                            <dd class="col-sm-8">
                                {{ $participant->department ?: '-' }}
                            </dd>
                            <dt class="col-sm-4">
                                Location
                            </dt>
                            <dd class="col-sm-8">
                                {{ $participant->city ?: '-' }},
                                {{ $participant->country }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.participants.edit', $participant) }}" class="btn btn-warning rounded-0">
                <i class="bi bi-pencil me-1"></i>
                Edit Participant
            </a>
        </div>
    </div>

    {{-- Conference Attendance --}}
    @php
        $attendance = $participant->attendances->first();
    @endphp

    <div class="card rounded-0 overflow-hidden mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-calendar-check me-2"></i>
                Conference Attendance
            </h3>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="border rounded-0 p-3 h-100">
                        <h5 class="fw-bold mb-3">
                            Attendance Status
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">
                                Status
                            </dt>
                            <dd class="col-sm-7">
                                @if (!$attendance || $attendance->attendance_status === 'not_checked_in')
                                    <span class="badge text-bg-secondary rounded-0">
                                        Not Checked In
                                    </span>
                                @elseif ($attendance->attendance_status === 'checked_in')
                                    <span class="badge text-bg-warning rounded-0">
                                        Waiting for Verification
                                    </span>
                                @elseif ($attendance->attendance_status === 'verified')
                                    <span class="badge text-bg-success rounded-0">
                                        Verified
                                    </span>
                                @else
                                    <span class="badge text-bg-secondary rounded-0">
                                        {{ ucfirst(str_replace('_', ' ', $attendance->attendance_status)) }}
                                    </span>
                                @endif
                            </dd>
                            <dt class="col-sm-5">
                                Attendance Type
                            </dt>
                            <dd class="col-sm-7">
                                {{ ucfirst($participant->attendance_type) }}
                            </dd>
                            <dt class="col-sm-5">
                                Checked In At
                            </dt>
                            <dd class="col-sm-7">
                                {{ $attendance?->checked_in_at?->format('d F Y H:i') ?? '-' }}
                            </dd>
                            <dt class="col-sm-5">
                                Verified At
                            </dt>
                            <dd class="col-sm-7">
                                {{ $attendance?->verified_at?->format('d F Y H:i') ?? '-' }}
                            </dd>
                            <dt class="col-sm-5">
                                Verified By
                            </dt>
                            <dd class="col-sm-7">
                                {{ $attendance?->verifier?->name ?? '-' }}
                            </dd>
                            <dt class="col-sm-5">
                                Verification Notes
                            </dt>
                            <dd class="col-sm-7">
                                {{ $attendance?->verification_notes ?: '-' }}
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="border rounded-0 p-3 h-100">
                        <h5 class="fw-bold mb-3">
                            Attendance Actions
                        </h5>
                        @if (!$attendance || $attendance->attendance_status === 'not_checked_in')
                            @if ($participant->registration_status !== 'confirmed')
                                <div class="alert alert-warning rounded-0">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-exclamation-triangle fs-5"></i>
                                        <div>
                                            <strong>Manual check-in is unavailable.</strong>
                                            <div class="small mt-1">
                                                Registration status is
                                                {{ ucfirst($participant->registration_status) }}.
                                                Manual check-in is only available after registration
                                                is confirmed.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">
                                    No attendance check-in has been recorded.
                                    Admin can manually record the participant's check-in.
                                </p>
                                <form method="POST"
                                    action="{{ route('admin.attendance.manual-check-in', $participant) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-3">
                                        <label for="checked_in_at" class="form-label">
                                            Check-in Time
                                        </label>
                                        <input type="datetime-local" name="checked_in_at" id="checked_in_at"
                                            class="form-control rounded-0 @error('checked_in_at') is-invalid @enderror"
                                            value="{{ old('checked_in_at', now()->format('Y-m-d\TH:i')) }}" required>
                                        @error('checked_in_at')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="verification_notes" class="form-label">
                                            Check-in Notes
                                        </label>
                                        <textarea name="verification_notes" id="verification_notes" rows="4"
                                            class="form-control rounded-0 @error('verification_notes') is-invalid @enderror"
                                            placeholder="Enter the reason or notes for manual check-in..." required>{{ old('verification_notes') }}</textarea>
                                        @error('verification_notes')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary rounded-0">
                                        <i class="bi bi-person-check me-1"></i>
                                        Manual Check-in
                                    </button>
                                </form>
                            @endif
                        @elseif ($attendance->attendance_status === 'checked_in')
                            <div class="alert alert-warning rounded-0">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-hourglass-split fs-5"></i>
                                    <div>
                                        <strong>
                                            Attendance is waiting for verification.
                                        </strong>
                                        <div class="small mt-1">
                                            The participant has already checked in.
                                            Admin can now verify the attendance.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.attendance.verify', $attendance) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success rounded-0">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Verify Attendance
                                </button>
                            </form>
                        @elseif ($attendance->attendance_status === 'verified')
                            <div class="alert alert-success rounded-0">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-patch-check-fill fs-5"></i>
                                    <div>
                                        <strong>
                                            Attendance has been verified.
                                        </strong>
                                        <div class="small mt-1">
                                            No further attendance action is required.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if ($participant->notes)
        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-sticky me-2"></i>
                    Notes
                </h3>
            </div>
            <div class="card-body">
                {!! nl2br(e($participant->notes)) !!}
            </div>
        </div>
    @endif

@endsection
