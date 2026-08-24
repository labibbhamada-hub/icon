@extends('layouts.participant')
@section('title', 'Camera Ready')
@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.show', $submission) }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Camera Ready Submission
                </h1>
            </div>
            <p class="text-muted mb-0 mt-1">
                Upload the final camera-ready version of your accepted paper.
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
                    Camera Ready
                </li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-file-earmark-check me-2"></i>
                Camera Ready Paper
            </h3>
        </div>
        <div class="card-body">
            @if ($submission->camera_ready_correction_reason)
                <div class="alert alert-warning rounded-0 mb-2">
                    <i class="bi bi-arrow-repeat me-2"></i>
                    Your camera-ready paper requires correction.
                    Please review the correction reason below and upload the corrected version.
                </div>
            @else
                <div class="alert alert-success rounded-0 mb-2">
                    <i class="bi bi-check-circle me-2"></i>
                    Your paper has been accepted.
                    Please upload the final camera-ready version of your paper.
                </div>
            @endif
            @if ($submission->camera_ready_correction_reason)
                <div class="alert alert-warning rounded-0 mb-2">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Correction Required
                    </div>
                    <div>
                        Your previously submitted camera-ready paper requires correction.
                        Please review the following comments before uploading the new version.
                    </div>
                    <div class="border rounded-0 bg-white p-3 mt-3">
                        <div class="small text-muted mb-1">
                            Correction Reason
                        </div>
                        <div>
                            {!! nl2br(e($submission->camera_ready_correction_reason)) !!}
                        </div>
                    </div>
                </div>
            @endif
            @if ($cameraReadyDeadline)
                <div class="alert alert-info rounded-0 mb-2">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-calendar-event fs-5"></i>
                        <div>
                            <strong>
                                {{ $cameraReadyDeadline->title }}
                            </strong>
                            <div class="mt-1">
                                @if ($cameraReadyDeadline->end_date)
                                    {{ $cameraReadyDeadline->date->format('d M Y') }}
                                    -
                                    {{ $cameraReadyDeadline->end_date->format('d M Y') }}
                                @else
                                    Deadline:
                                    {{ $cameraReadyDeadline->date->format('d M Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($submission->camera_ready_file)
                <div class="alert alert-warning rounded-0 mb-2">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Your previous camera-ready file can be replaced by uploading
                    a new version.
                </div>
            @endif
        </div>
        <div class="card-body border-top">
            <div class="border rounded-0 p-3 mb-2 bg-light">
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
            @if ($submission->camera_ready_file)
                <div class="alert alert-info rounded-0 mb-2">
                    <i class="bi bi-info-circle me-2"></i>
                    A camera-ready file already exists.
                    Uploading a new file will replace it.
                </div>
            @endif
        </div>
        <form action="{{ route('participant.submissions.camera-ready.upload', $submission) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body border-top">
                <div class="mb-4">
                    <label class="form-label">
                        Camera-Ready File
                        <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="camera_ready_file" accept="application/pdf"
                        class="form-control @error('camera_ready_file') is-invalid @enderror rounded-0">
                    <div class="form-text">
                        PDF only. Maximum 10 MB.
                    </div>
                    @error('camera_ready_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success rounded-0">
                    <i class="bi bi-upload me-1"></i>
                    Upload Camera Ready
                </button>
            </div>
        </form>
    </div>
@endsection
