@extends('layouts.participant')
@section('title', 'Presentation Details')
@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.show', $submission) }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Presentation Details
                </h1>
            </div>
            <p class="text-muted mb-0 mt-1">
                Choose the presentation type and presenter for your accepted paper.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.submissions.index') }}">
                        My Submissions
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Presentation
                </li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <form action="{{ route('participant.submissions.presentation.update', $submission) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Accepted Paper
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success rounded-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Your paper has been accepted. Please provide the presentation details below.
                </div>
                <div class="border rounded-0 p-3 bg-light">
                    <small class="text-muted d-block">
                        Submission
                    </small>
                    <strong>
                        {{ $submission->submission_code }}
                    </strong>
                    <div class="mt-1">
                        {{ $submission->title }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-easel2 me-2"></i>
                    Presentation Type
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="d-block h-100">
                            <input type="radio" name="presentation_type" value="oral" class="btn-check"
                                @checked(old('presentation_type', $submission->presentation_type) === 'oral')>
                            <div class="border rounded-0 p-3 h-100 presentation-option">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-mic-fill fs-4 text-primary"></i>
                                    <div>
                                        <h5 class="mb-1">
                                            Oral Presentation
                                        </h5>
                                        <small class="text-muted">
                                            Present your paper as an oral presentation.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="d-block h-100">
                            <input type="radio" name="presentation_type" value="poster" class="btn-check"
                                @checked(old('presentation_type', $submission->presentation_type) === 'poster')>
                            <div class="border rounded-0 p-3 h-100 presentation-option">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-image fs-4 text-success"></i>
                                    <div>
                                        <h5 class="mb-1">
                                            Poster Presentation
                                        </h5>
                                        <small class="text-muted">
                                            Present your paper as a poster.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                @error('presentation_type')
                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="card rounded-0 mb-3">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-broadcast-pin me-2"></i>
                    Presentation Mode
                </h3>
            </div>

            <div class="card-body">

                @if ($participant->attendance_type === 'hybrid')

                    <div class="alert alert-info rounded-0 mb-3">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle fs-5"></i>

                            <div>
                                <strong>
                                    Flexible Attendance
                                </strong>

                                <div class="small mt-1">
                                    Your registration uses hybrid attendance.
                                    Please choose how this paper will be presented.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="d-block h-100">
                                <input type="radio" name="presentation_mode" value="offline" class="btn-check"
                                    @checked(old('presentation_mode', $submission->presentation_mode) === 'offline')>

                                <div class="border rounded-0 p-3 h-100 presentation-option">

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-building fs-4 text-primary"></i>

                                        <div>
                                            <h5 class="mb-1">
                                                Offline
                                            </h5>

                                            <small class="text-muted">
                                                Present at the conference venue.
                                            </small>
                                        </div>

                                    </div>

                                </div>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label class="d-block h-100">

                                <input type="radio" name="presentation_mode" value="online" class="btn-check"
                                    @checked(old('presentation_mode', $submission->presentation_mode) === 'online')>

                                <div class="border rounded-0 p-3 h-100 presentation-option">

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-camera-video-fill fs-4 text-success"></i>

                                        <div>
                                            <h5 class="mb-1">
                                                Online
                                            </h5>

                                            <small class="text-muted">
                                                Present remotely through the conference online session.
                                            </small>
                                        </div>

                                    </div>

                                </div>
                            </label>
                        </div>

                    </div>

                    @error('presentation_mode')
                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                @else
                    <input type="hidden" name="presentation_mode" value="{{ $participant->attendance_type }}">

                    <div class="border rounded-0 p-3 bg-light">

                        <div class="d-flex align-items-center gap-3">

                            @if ($participant->attendance_type === 'offline')
                                <i class="bi bi-building fs-4 text-primary"></i>
                            @else
                                <i class="bi bi-camera-video-fill fs-4 text-success"></i>
                            @endif

                            <div>

                                <small class="text-muted d-block">
                                    Based on your conference attendance
                                </small>

                                <strong>
                                    {{ ucfirst($participant->attendance_type) }}
                                </strong>

                                <div class="small text-muted mt-1">
                                    Your presentation mode is automatically set based on your registration.
                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-person-video3 me-2"></i>
                    Presenter
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info rounded-0">
                    <i class="bi bi-info-circle me-2"></i>
                    The presenter can be you or one of your co-authors.
                </div>
                <div class="row g-2">
                    @foreach ($submission->authors as $author)
                        <div class="col-md-6">
                            <label class="d-block h-100">
                                <input type="radio" name="presenter_author_id" value="{{ $author->id }}"
                                    class="btn-check" @checked(old('presenter_author_id', $submission->presenter_author_id) == $author->id)>
                                <div class="border rounded-0 p-3 h-100 presentation-option">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <strong>
                                                {{ $author->name }}
                                            </strong>
                                            @if ($author->institution)
                                                <small class="text-muted d-block">
                                                    {{ $author->institution }}
                                                </small>
                                            @endif
                                        </div>
                                        @if ($author->is_corresponding)
                                            <span class="badge text-bg-primary rounded-0">
                                                Corresponding
                                            </span>
                                        @endif
                                    </div>
                                    @if ($author->email)
                                        <small class="text-muted d-block mt-2">
                                            {{ $author->email }}
                                        </small>
                                    @endif
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('presenter_author_id')
                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="card rounded-0">
            <div class="card-footer text-end">
                <a href="{{ route('participant.submissions.show', $submission) }}"
                    class="btn btn-secondary btn-sm rounded-0 me-1">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-check-circle me-1"></i>
                    Save Presentation Details
                </button>
            </div>
        </div>
    </form>
@endsection
@push('styles')
    <style>
        .presentation-option {
            cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .presentation-option:hover {
            border-color: var(--bs-primary) !important;
        }

        .btn-check:checked+.presentation-option {
            border-color: var(--bs-primary) !important;
            box-shadow: 0 0 0 .15rem rgba(var(--bs-primary-rgb), .15);
        }
    </style>
@endpush
