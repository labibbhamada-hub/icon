@extends('layouts.participant')

@section('title', 'Submit Revision')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.show', $submission) }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Submit Revision
                </h1>
            </div>
            <p class="text-muted mb-0">
                Upload the revised version of your paper.
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
                    Revision
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-arrow-repeat me-2"></i>
                Revision Submission
            </h3>
        </div>
        <form action="{{ route('participant.submissions.revision.upload', $submission) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="alert alert-warning rounded-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Your paper has been returned for revision.
                    Please upload the revised manuscript.
                </div>
                <div class="border rounded-0 p-3 mb-2 bg-light">
                    <small class="text-muted d-block">
                        Submission
                    </small>
                    <strong>
                        {{ $submission->submission_code }}
                    </strong>
                    <div>
                        {{ $submission->title }}
                    </div>
                </div>
                @if ($submission->revised_file)
                    <div class="alert alert-info rounded-0">
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        A previous revised file exists.
                        Uploading a new file will replace it.
                    </div>
                @endif
                <div class="mb-2">
                    <label class="form-label">
                        Revised Paper
                        <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="revised_file" accept="application/pdf"
                        class="form-control @error('revised_file') is-invalid @enderror rounded-0">
                    <div class="form-text">
                        PDF only. Maximum 10 MB.
                    </div>
                    @error('revised_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-upload me-1"></i>
                    Upload Revision
                </button>
            </div>
        </form>
    </div>
@endsection
