@extends('layouts.participant')

@section('title', 'Submit Full Paper')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.show', $submission) }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h1 class="mb-0 fs-3">
                    Submit Full Paper
                </h1>
            </div>

            <p class="text-muted mb-0">
                Submit the full paper for your accepted abstract.
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
                    <a href="{{ route('participant.submissions.index') }}">
                        My Submissions
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Full Paper
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
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Submission Information --}}
    <div class="card rounded-0 mb-3">

        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Submission Information
            </h3>
        </div>

        <div class="card-body">

            <div class="border rounded-0 p-3 bg-light">

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Submission Code
                    </small>

                    <strong>
                        {{ $submission->submission_code }}
                    </strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Topic
                    </small>

                    <strong>
                        {{ $submission->topic?->name ?? '—' }}
                    </strong>
                </div>

                <div>
                    <small class="text-muted d-block">
                        Paper Title
                    </small>

                    <strong class="fs-5">
                        {{ $submission->title }}
                    </strong>
                </div>

            </div>

            <div class="alert alert-success rounded-0 mt-3 mb-0">

                <div class="d-flex align-items-start gap-2">

                    <i class="bi bi-check-circle fs-5"></i>

                    <div>
                        <strong>
                            Abstract Accepted
                        </strong>

                        <div class="small mt-1">
                            Your abstract has been accepted. You may now submit the full paper for further review.
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Full Paper Deadline --}}
    @if ($fullPaperDeadline)
        <div class="card rounded-0 mb-3">

            <div class="card-body">

                <div class="d-flex align-items-start gap-3">

                    <div class="text-primary fs-4">
                        <i class="bi bi-calendar-event"></i>
                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Full Paper Submission Deadline
                        </small>

                        <strong>
                            {{ $fullPaperDeadline->title }}
                        </strong>

                        <div class="mt-1">
                            {{ $fullPaperDeadline->date->format('d M Y') }}

                            @if ($fullPaperDeadline->end_date)
                                –
                                {{ $fullPaperDeadline->end_date->format('d M Y') }}
                            @endif
                        </div>

                    </div>

                </div>

            </div>

        </div>
    @endif

    {{-- Full Paper Upload --}}
    <form action="{{ route('participant.submissions.full-paper.upload', $submission) }}" method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="card rounded-0 mb-3">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-upload me-2"></i>
                    Full Paper File
                </h3>
            </div>

            <div class="card-body">

                <div class="alert alert-info rounded-0">
                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-info-circle fs-5"></i>

                        <div>
                            <strong>
                                Before Uploading
                            </strong>

                            <div class="small mt-1">
                                Make sure the file is the final full paper version prepared according to the conference
                                requirements.
                            </div>
                        </div>

                    </div>
                </div>

                <div>
                    <label class="form-label">
                        Full Paper
                        <span class="text-danger">*</span>
                    </label>

                    <input type="file" name="paper_file" accept="application/pdf"
                        class="form-control @error('paper_file') is-invalid @enderror rounded-0">

                    <div class="form-text">
                        PDF only. Maximum 10 MB.
                    </div>

                    @error('paper_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

        </div>

        {{-- Submit --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

            <div class="text-muted small">
                <i class="bi bi-shield-check me-1"></i>
                Please make sure you upload the correct full paper before submitting.
            </div>

            <button type="submit" class="btn btn-success rounded-0">
                <i class="bi bi-send me-1"></i>
                Submit Full Paper
            </button>

        </div>

    </form>

@endsection
