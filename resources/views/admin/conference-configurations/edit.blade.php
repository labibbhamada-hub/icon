@extends('layouts.admin')

@section('title', 'Conference Configuration')

@section('header')
    <div class="row align-items-top">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.conferences.show', $conference) }}" class="btn btn-secondary btn-sm rounded-0"
                    title="Back">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h1 class="mb-0 fs-3">
                    Conference Configuration
                </h1>

            </div>

            <p class="text-muted mb-0 mt-1">
                Manage branding and certificate configuration.
            </p>

        </div>

        <div class="col-sm-6">

            <ol class="breadcrumb float-sm-end mb-0">

                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('admin.conferences.index') }}">
                        Conferences
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('admin.conferences.show', $conference) }}">
                        Detail
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Configuration
                </li>

            </ol>

        </div>

    </div>
@endsection

@section('content')

    @if ($errors->any())

        <div class="alert alert-danger rounded-0">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('admin.conferences.configuration.update', $conference) }}" method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-calendar-event me-2"></i>
                    Conference Information
                </h3>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-8">

                        <small class="text-muted d-block">
                            Conference Name
                        </small>

                        <strong>
                            {{ $conference->name }}
                        </strong>

                    </div>

                    <div class="col-md-2">

                        <small class="text-muted d-block">
                            Short Name
                        </small>

                        <strong>
                            {{ $conference->short_name }}
                        </strong>

                    </div>

                    <div class="col-md-2">

                        <small class="text-muted d-block">
                            Year
                        </small>

                        <strong>
                            {{ $conference->year }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-palette me-2"></i>
                    Branding
                </h3>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Conference Logo
                        </label>

                        <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp"
                            class="form-control @error('logo') is-invalid @enderror rounded-0">

                        <div class="form-text">
                            JPG, JPEG, PNG, or WebP. Maximum 2 MB.
                        </div>

                        @error('logo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        @if ($configuration?->logo)
                            <div class="border rounded-0 p-3 mt-3">

                                <small class="text-muted d-block mb-2">
                                    Current Logo
                                </small>

                                <img src="{{ asset('storage/' . $configuration->logo) }}" alt="Conference Logo"
                                    style="max-width: 220px; max-height: 120px; object-fit: contain;">

                            </div>
                        @endif

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Certificate Signature
                        </label>

                        <input type="file" name="signature_file" accept=".jpg,.jpeg,.png,.webp"
                            class="form-control @error('signature_file') is-invalid @enderror rounded-0">

                        <div class="form-text">
                            Transparent PNG is recommended. Maximum 2 MB.
                        </div>

                        @error('signature_file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        @if ($configuration?->signature_file)
                            <div class="border rounded-0 p-3 mt-3">

                                <small class="text-muted d-block mb-2">
                                    Current Signature
                                </small>

                                <img src="{{ asset('storage/' . $configuration->signature_file) }}"
                                    alt="Certificate Signature"
                                    style="max-width: 260px; max-height: 120px; object-fit: contain;">

                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-award me-2"></i>
                    Certificate Configuration
                </h3>

            </div>

            <div class="card-body">

                <div class="alert alert-secondary rounded-0">

                    <i class="bi bi-info-circle me-2"></i>

                    This information will be used on generated certificate PDFs.

                </div>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Chair Name
                        </label>

                        <input type="text" name="chair_name"
                            value="{{ old('chair_name', $configuration?->chair_name) }}"
                            class="form-control @error('chair_name') is-invalid @enderror rounded-0"
                            placeholder="Dr. Budi Santoso, S.Kom., M.T.">

                        @error('chair_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Chair Title
                        </label>

                        <input type="text" name="chair_title"
                            value="{{ old('chair_title', $configuration?->chair_title) }}"
                            class="form-control @error('chair_title') is-invalid @enderror rounded-0"
                            placeholder="Conference Chair">

                        @error('chair_title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>

        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-camera-video me-2"></i>
                    Online Meeting
                </h3>

            </div>

            <div class="card-body">

                <div class="alert alert-info rounded-0">

                    <i class="bi bi-info-circle me-2"></i>

                    Configure the main online meeting for this conference.
                    Participants will only see the meeting information when
                    access to the meeting is allowed.

                </div>

                <div class="row g-4">

                    {{-- Title --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Meeting Title
                        </label>

                        <input type="text" name="meeting_title"
                            value="{{ old('meeting_title', $conference->onlineMeeting?->title) }}"
                            class="form-control @error('meeting_title') is-invalid @enderror rounded-0"
                            placeholder="ICON 2026 Main Zoom Meeting">

                        @error('meeting_title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Meeting URL --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Meeting URL
                        </label>

                        <input type="url" name="meeting_url"
                            value="{{ old('meeting_url', $conference->onlineMeeting?->meeting_url) }}"
                            class="form-control @error('meeting_url') is-invalid @enderror rounded-0"
                            placeholder="https://zoom.us/j/123456789">

                        @error('meeting_url')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Meeting ID --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Meeting ID
                        </label>

                        <input type="text" name="meeting_id"
                            value="{{ old('meeting_id', $conference->onlineMeeting?->meeting_id) }}"
                            class="form-control @error('meeting_id') is-invalid @enderror rounded-0"
                            placeholder="123 456 789">

                        @error('meeting_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Passcode --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Passcode
                        </label>

                        <input type="text" name="passcode"
                            value="{{ old('passcode', $conference->onlineMeeting?->passcode) }}"
                            class="form-control @error('passcode') is-invalid @enderror rounded-0" placeholder="ICON2026">

                        @error('passcode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Instructions --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Meeting Instructions
                        </label>

                        <textarea name="meeting_instructions" rows="4"
                            class="form-control @error('meeting_instructions') is-invalid @enderror rounded-0"
                            placeholder="Please join 15 minutes before the conference starts.">{{ old('meeting_instructions', $conference->onlineMeeting?->instructions) }}</textarea>

                        @error('meeting_instructions')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            These instructions will be shown to eligible participants.
                        </div>

                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label d-block">
                            Meeting Status
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input type="hidden" name="meeting_is_active" value="0">

                            <input class="form-check-input" type="checkbox" name="meeting_is_active" value="1"
                                @checked(old('meeting_is_active', $conference->onlineMeeting?->is_active ?? true))>

                            <label class="form-check-label">
                                Active
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="text-end mb-3">

            <button type="submit" class="btn btn-success rounded-0">

                <i class="bi bi-check-circle me-1"></i>
                Save Configuration

            </button>

        </div>

    </form>

@endsection
