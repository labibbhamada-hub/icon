@extends('layouts.participant')

@section('title', 'Register Conference')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.registration.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Register Conference
                </h1>
            </div>
            <p class="text-muted mb-0">
                Register for an available conference.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.registration.index') }}">Registration</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if ($conferences->isEmpty())
        <div class="alert alert-info rounded-0">
            <i class="bi bi-info-circle me-2"></i>
            There are currently no conferences open for registration.
        </div>
    @else
        <form action="{{ route('participant.registration.store') }}" method="POST">
            @csrf
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-calendar-check me-2"></i>
                        Registration Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
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
                                    <option value="{{ $conference->id }}"
                                        data-regular-fee="{{ $conference->configuration?->regular_fee ?? 0 }}"
                                        data-student-fee="{{ $conference->configuration?->student_fee ?? 0 }}"
                                        @selected(old('conference_id') == $conference->id)>
                                        {{ $conference->name }} — {{ $conference->year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('conference_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-body border-top" id="registration-fee-card" style="display: none;">
                    <div class="alert alert-info rounded-0">
                        <div class="fw-semibold">
                            Registration Fee
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block">
                                    Regular
                                </small>
                                <strong id="regular-fee">
                                    Rp 0
                                </strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">
                                    Student
                                </small>
                                <strong id="student-fee">
                                    Rp 0
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">
                                Participant Type
                                <span class="text-danger">*</span>
                            </label>
                            <select name="participant_type"
                                class="form-select @error('participant_type') is-invalid @enderror rounded-0">
                                <option value="regular" @selected(old('participant_type', 'regular') === 'regular')>
                                    Regular
                                </option>
                                <option value="student" @selected(old('participant_type') === 'student')>
                                    Student
                                </option>
                            </select>
                            @error('participant_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">
                                Attendance Type
                                <span class="text-danger">*</span>
                            </label>
                            <select name="attendance_type"
                                class="form-select @error('attendance_type') is-invalid @enderror rounded-0">
                                <option value="offline" @selected(old('attendance_type', 'offline') === 'offline')>
                                    Offline
                                </option>
                                <option value="online" @selected(old('attendance_type') === 'online')>
                                    Online
                                </option>
                                <option value="hybrid" @selected(old('attendance_type') === 'hybrid')>
                                    Hybrid
                                </option>
                            </select>
                            @error('attendance_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">
                                Phone Number
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror rounded-0"
                                placeholder="+62 812 3456 7890">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">
                                Institution
                            </label>
                            <input type="text" name="institution" value="{{ old('institution') }}"
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
                            <input type="text" name="department" value="{{ old('department') }}"
                                class="form-control @error('department') is-invalid @enderror rounded-0">
                            @error('department')
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
                            <input type="text" name="country" value="{{ old('country', 'Indonesia') }}"
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
                            <input type="text" name="city" value="{{ old('city') }}"
                                class="form-control @error('city') is-invalid @enderror rounded-0">
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-success rounded-0">
                        <i class="bi bi-check-circle me-1"></i>
                        Submit Registration
                    </button>
                </div>
            </div>
        </form>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const conferenceSelect =
                document.getElementById('conference_id');
            const feeCard =
                document.getElementById('registration-fee-card');
            const regularFee =
                document.getElementById('regular-fee');
            const studentFee =
                document.getElementById('student-fee');

            function formatRupiah(value) {
                return 'Rp ' +
                    new Intl.NumberFormat(
                        'id-ID'
                    ).format(
                        Number(value || 0)
                    );
            }

            function updateFee() {
                const option =
                    conferenceSelect.options[
                        conferenceSelect.selectedIndex
                    ];
                if (!option || !option.value) {
                    feeCard.style.display = 'none';
                    return;
                }
                regularFee.textContent =
                    formatRupiah(
                        option.dataset.regularFee
                    );
                studentFee.textContent =
                    formatRupiah(
                        option.dataset.studentFee
                    );
                feeCard.style.display = '';
            }
            conferenceSelect.addEventListener(
                'change',
                updateFee
            );
            updateFee();
        });
    </script>
@endpush
