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
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-calendar-check me-2"></i>
                        Registration Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label">
                                Conference
                                <span class="text-danger">*</span>
                            </label>
                            <select name="conference_id" class="form-select @error('conference_id') is-invalid @enderror">
                                <option value="">
                                    Select Conference
                                </option>
                                @foreach ($conferences as $conference)
                                    <option value="{{ $conference->id }}" @selected(old('conference_id') == $conference->id)>
                                        {{ $conference->name }}
                                        —
                                        {{ $conference->year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('conference_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Participant Type
                                <span class="text-danger">*</span>
                            </label>
                            <select name="participant_type"
                                class="form-select @error('participant_type') is-invalid @enderror">
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
                        <div class="col-md-6">
                            <label class="form-label">
                                Attendance Type
                                <span class="text-danger">*</span>
                            </label>
                            <select name="attendance_type"
                                class="form-select @error('attendance_type') is-invalid @enderror">
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
                        <div class="col-md-6">
                            <label class="form-label">
                                Phone Number
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror" placeholder="+62 812 3456 7890">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Institution
                            </label>
                            <input type="text" name="institution" value="{{ old('institution') }}"
                                class="form-control @error('institution') is-invalid @enderror"
                                placeholder="University / Institution">
                            @error('institution')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Department
                            </label>
                            <input type="text" name="department" value="{{ old('department') }}"
                                class="form-control @error('department') is-invalid @enderror">
                            @error('department')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                Country
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="country" value="{{ old('country', 'Indonesia') }}"
                                class="form-control @error('country') is-invalid @enderror">
                            @error('country')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                City
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                class="form-control @error('city') is-invalid @enderror">
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('participant.registration.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Submit Registration
                    </button>
                </div>
            </div>
        </form>
    @endif
@endsection
